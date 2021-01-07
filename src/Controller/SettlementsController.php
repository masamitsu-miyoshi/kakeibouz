<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\Utility\Inflector;
use Laminas\Diactoros\CallbackStream;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;

/**
 * Settlements Controller
 *
 * @property \App\Model\Table\SettlementsTable $Settlements
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettlementsController extends AppController
{
    public $paginate = [
        'contain' => [
            'Debits'
        ],
        'order' => [
            'Settlements.code' => 'desc',
        ]
    ];

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        foreach (['Users'] as $modelName) {
            $this->loadModel($modelName);
            $this->$modelName->find();
            $data = $this->$modelName->find('list')->order(['id'])->toArray();
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
        $settlements = $this->paginate($this->Settlements);

        $this->set(compact('settlements'));
    }

    /**
     * View method
     *
     * @param string|null $id Settlement id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $settlement = $this->Settlements->get($id, [
            'contain' => [
                'Debits.Bills',
                'Payments',
            ],
        ]);

        $this->set(compact('settlement'));
    }

    public function create()
    {
        $settlement = $this->Settlements->newEntity(['code' => FrozenTime::now('Asia/Tokyo')->subMonth(1)->i18nFormat('yyyyMM')]);
        if ($this->request->is('post')) {
            $settlement = $this->Settlements->patchEntity($settlement, $this->request->getData());

            $settlement->family_id = $this->request->getSession()->read('Auth.family_id');

            if ($this->Settlements->save($settlement)) {
                $this->Flash->success(__('The settlement has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The settlement could not be saved. Please, try again.'));
        }
        $this->set(compact('settlement'));
    }

    public function download($id = null)
    {
        $settlement = $this->Settlements->get($id, [
            'contain' => [
                'Families.Users',
                'Payments',
                'Payments.PaymentMethods',
                'Payments.CostCategories',
                'Payments.Stores',
                'Payments.PaidUsers',
                'Payments.Bills',
            ],
        ]);

        $spreadsheet = new Spreadsheet();
        // 選択されているシートを取得
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle($settlement->code);

        // header
        $sheet->fromArray([
            array_merge(
                // 支払列
                [
                    'No',
                    '支払日',
                    '支払方法',
                    '支払種別',
                    '支払内容',
                    '支払先',
                    '支払人',
                    '支払金額',
                ],
                // 請求列
                collection($settlement->family->users)->reduce(function($accumulated, $user) {
                    return array_merge($accumulated, ["$user->code.請求割合", "$user->code.請求金額"]);
                }, [])
            ),
        ], null, 'A1');

        // Body
        $sheet->fromArray(collection($settlement->payments)->map(function ($payment, $index) {
            return array_merge(
                // 支払列
                [
                    $index + 1,
                    $payment->date->i18nFormat('yyyy/MM/dd') ?? '',
                    $payment->payment_method->name ?? '',
                    $payment->cost_category->name ?? '',
                    $payment->product_name,
                    $payment->store->name ?? '',
                    $payment->paid_user->code,
                    $payment->amount - $payment->private_amount,
                ],
                // 請求列
                collection($payment->bills)->reduce(function($accumulated, $bill) {
                    return array_merge($accumulated, [$bill->rate, $bill->amount]);
                }, [])
            );
        })->toArray(), null, 'A2');

        // Footer
        $sheet->fromArray([
            '最終行'
        ], null, 'A' . ($sheet->getHighestRow() + 1));

        try {
            $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

            $stream = new CallbackStream(function () use ($writer) {
                $writer->save('php://output');
            });

            // ファイルを出力
            return $this->response
                ->withHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->withHeader('Content-Disposition', "attachment;filename=\"$settlement->code.xlsx\"")
                ->withHeader('Cache-Control', 'max-age=0')
                ->withBody($stream);
        } catch (Exception $e) {
            throw new InternalErrorException($e);
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Settlement id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $settlement = $this->Settlements->get($id);
        if ($this->Settlements->delete($settlement)) {
            $this->Flash->success(__('The settlement has been deleted.'));
        } else {
            $this->Flash->error(__('The settlement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
