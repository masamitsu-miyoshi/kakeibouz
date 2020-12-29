<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\PayersTable;
use Cake\Event\EventInterface;
use Cake\I18n\FrozenTime;
use Cake\Utility\Inflector;

/**
 * Payments Controller
 *
 * @property PayersTable $Payers
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

        foreach (['Payers', 'PaymentMethods', 'CostCategories', 'Stores'] as $modelName) {
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

        if ($this->request->getQuery('payer_id')) {
            $query->where(['Payments.payer_id' =>  $this->request->getQuery('payer_id')]);
        }

        $query->order(['date desc', 'id desc']);

        $payments = $query->all();

        // ユーザー毎の支払金額
        $query = $this->Payers->find();
        $query
            ->select([
                'payer_id' => 'Payers.id',
                'payer_name' => 'Payers.name',
                'payment_amount' => 'SUM(Payments.amount - Payments.private_amount)',
            ])
            ->leftJoinWith('Payments')
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
                'Payers.id
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

            $data = $this->request->getData('_receipt_image');
            if ($data && $data->getSize()) {
                $payment->receipt_image = $this->Payments->ReceiptImages->newEmptyEntity();
                $payment->receipt_image->name = $data->getClientFilename();
                $payment->receipt_image->media_type = $data->getClientMediaType();
                $payment->receipt_image->data = $data->getStream()->getContents();
            }

            if ($this->Payments->save($payment)) {
                $this->Flash->success(__('保存に成功しました 伝票:{0} 金額:{1}', 'P' . $payment->id, $payment->amount - $payment->private_amount));
                return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
            }
            $this->Flash->error(__('The payment could not be saved. Please, try again.'));
        }
        $this->set(compact('ref'));
        $this->set(compact('payment'));
    }

    /**
     * View method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $query = $this->Payments->find();
        $query
            ->contain('ReceiptImages')
            ->where([
                'Payments.id' => $id,
            ]);

        $payment = $query->firstOrFail();
        $this->set('receiptImage', $payment->receipt_image);
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
        } else {
            $this->Flash->error(__('The payment could not be deleted. Please, try again.'));
        }
        return $this->redirect(['controller' => 'payments', 'year' => $payment->date->i18nFormat('yyyy'), 'month' => $payment->date->i18nFormat('MM')]);
    }
}
