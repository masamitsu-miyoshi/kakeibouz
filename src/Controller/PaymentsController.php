<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsersTable;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Utility\Inflector;

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
//        'limit' => 10,
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

        foreach (['Users', 'PaymentMethods', 'CostCategories', 'Stores'] as $modelName) {
            $this->loadModel($modelName);
            $this->$modelName->find();
            $data = $this->$modelName->find('list')->toArray();
            $this->set(Inflector::variable($modelName), $data);
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

        $query->order(['date desc', 'id desc']);

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
                'date' => FrozenTime::now('Asia/Tokyo'),
            ]);
        } else {
            $payment = $this->Payments->get($id, [
                'contain' => [],
            ]);
        }

        $ref = $this->request->getQuery('ref');

        if ($this->request->is(['patch', 'post', 'put'])) {
            $payment = $this->Payments->patchEntity($payment, $this->request->getData());

            if ($this->Payments->save($payment)) {
                $this->Flash->success(__('保存に成功しました 伝票:{0} 金額:{1}', 'P' . $payment->id, $payment->amount - $payment->private_amount));
                return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
            }

            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }
        $this->set(compact('ref'));
        $this->set(compact('payment'));

        $paidUsers = $this->Users->find('list', ['order' => 'id']);

        $this->set(compact('paidUsers'));
    }

    public function duplicate($id = null)
    {
        $this->request->allowMethod(['post']);
        $payment = $this->Payments->get($id);
        $payment->setNew(true);
        $payment->id = null;

        if ($this->Payments->save($payment)) {
            $this->Flash->success(__('保存に成功しました'));
            return $this->redirect(['action' => 'edit', $payment->id]);
        } else {
            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
            return $this->redirect($this->referer());
        }
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
        $payment = $this->Payments->get($id);
        if ($this->Payments->delete($payment)) {
            $this->Flash->success(__('The payment has been deleted.'));
            return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
            return $this->redirect($this->referer());
        }
    }
}
