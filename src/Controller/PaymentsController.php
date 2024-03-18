<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Utility\Inflector;
use Cake\I18n\Number;

/**
 * Payments Controller
 *
 * @property UsersTable $Users
 * @property \App\Model\Table\PaymentsTable $Payments
 *
 * @method \App\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PaymentsController extends AppController
{
    public $paginate = [
        'order' => [
            'Payments.date' => 'desc',
        ]
    ];

    public function initialize(): void
    {
        parent::initialize();
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        if ($this->request->getSession()->read('Auth.family_id')) {
            foreach (['Users', 'CostCategories', 'Stores'] as $modelName) {
                $this->loadModel($modelName);
                $this->$modelName->find();
                $data = $this->$modelName->find('list')
                    ->where(['family_id' => $this->request->getSession()->read('Auth.family_id')])
                    ->order(['id'])
                    ->toArray();
                $this->set(Inflector::variable($modelName), $data);
            }

            foreach (['PaymentMethods'] as $modelName) {
                $this->loadModel($modelName);
                $this->$modelName->find();
                $data = $this->$modelName->find('list')
                    ->order(['id'])
                    ->toArray();
                $this->set(Inflector::variable($modelName), $data);
            }
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Payments->find();

        $today = FrozenTime::now();
        $dateFrom = FrozenTime::createFromFormat(
            'Ymd',
            $this->request->getParam('year', $today->i18nFormat('yyyy')) . $this->request->getParam('month', $today->i18nFormat('MM')) . '01',
            'Asia/Tokyo'
        );
        $dateTo = $dateFrom->addMonth(1);
        $query->where([
            'Payments.family_id' => $this->request->getSession()->read('Auth.family_id'),
            'OR' => [
                [
                    'Payments.date >=' => $dateFrom,
                    'Payments.date <' => $dateTo,
                ],
                [
                    'Payments.date IS NULL',
                ]
            ]
        ]);

        if ($this->request->getQuery('paid_user_id')) {
            $query->where(['Payments.paid_user_id' =>  $this->request->getQuery('paid_user_id')]);
        }

        $query->order(['date desc', 'amount desc', 'id desc']);

        $payments = $query->all();

        // ユーザー毎の支払金額
        $query = $this->Payments->find();
        $query
            ->select([
                'payer_id' => 'Payments.paid_user_id',
                'payer_name' => 'PaidUsers.code',
                'payment_amount' => 'SUM(Payments.amount - Payments.private_amount)',
            ])
            ->leftJoinWith('PaidUsers')
            ->where([
                'Payments.family_id' => $this->request->getSession()->read('Auth.family_id'),
                'OR' => [
                    [
                        'Payments.date >=' => $dateFrom,
                        'Payments.date <' => $dateTo,
                    ],
                    [
                        'Payments.date IS NULL',
                    ]
                ]
            ])
            ->group([
                'Payments.paid_user_id
            ']);

        // ユーザー毎の請求金額
        $totalPaymentsByPayer = $query->indexBy('payer_id')->toArray();

        $this->set(compact('totalPaymentsByPayer'));
        $this->set(compact('dateFrom'));
        $this->set(compact('payments'));
    }

    public function aggregate($costCategoryId = null)
    {
        $query = $this->Payments->find();

        $begin = FrozenTime::now()->subYear();

        $end = FrozenTime::now();

        $query->select([
            'payment_month' => "DATE_FORMAT(Payments.date, '%Y-%m')",
            'Payments.cost_category_id',
            'payment_amount' => 'SUM(Payments.amount - Payments.private_amount)'
        ]);

        $query->where([
            'Payments.family_id' => $this->request->getSession()->read('Auth.family_id'),
            'OR' => [
                [
                    'Payments.date >=' => $begin->i18nFormat('yyyy-MM-01'),
                    'Payments.date <=' => $end->i18nFormat('yyyy-MM-dd'),
                ],
                [
                    'Payments.date IS NULL',
                ]
            ]
        ]);

        if ($costCategoryId) {
            $query->where(['Payments.cost_category_id' => $costCategoryId]);
        }

        $query->group([
            'Payments.cost_category_id',
            "DATE_FORMAT(Payments.date, '%Y-%m')"
        ]);

        $payments = $query->all();
        $this->set(compact('payments'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($id == null) {
            $payment = $this->Payments->newEntity([
                'date' => new FrozenTime( $this->request->getQuery('date') ?? 'now', 'Asia/Tokyo'),
                'paid_user_id' => $this->request->getSession()->read('Auth.id'),
            ]);
        } else {
            $payment = $this->Payments->find()
                ->where(['id' => $id, 'family_id' => $this->request->getSession()->read('Auth.family_id')])
                ->firstOrFail();
        }

        $ref = $this->request->getQuery('ref');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $payment = $this->Payments->patchEntity($payment, $this->request->getData());

            $payment->family_id = $this->request->getSession()->read('Auth.family_id');

            if ($this->Payments->save($payment)) {
                $this->Flash->success(__('保存に成功しました 伝票:P{0} {1} {2}', $payment->id, $payment->date->i18nFormat('yy/MM'), Number::currency($payment->amount - $payment->private_amount)));
                return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
            }

            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }
        $this->set(compact('ref'));
        $this->set(compact('payment'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $payment = $this->Payments->find()
            ->where(['id' => $id, 'family_id' => $this->request->getSession()->read('Auth.family_id')])
            ->firstOrFail();

        if ($this->Payments->delete($payment)) {
            $this->Flash->success(__('The payment has been deleted.'));
            return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }
}
