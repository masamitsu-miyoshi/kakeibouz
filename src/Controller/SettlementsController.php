<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\InternalErrorException;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
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
        'order' => [
            'Settlements.code' => 'desc',
        ]
    ];

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
            'contain' => ['Payments'],
        ]);

        $this->set(compact('settlement'));
    }

    public function create()
    {
        $settlement = $this->Settlements->newEntity(['code' => FrozenTime::now('Asia/Tokyo')->subMonth(1)->i18nFormat('yyyyMM')]);
        if ($this->request->is('post')) {
            $settlement = $this->Settlements->patchEntity($settlement, $this->request->getData());

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
                'Payments',
                'Payments.PaymentMethods',
                'Payments.CostCategories',
                'Payments.Stores',
                'Payments.PaidUsers',
            ],
        ]);

        $spreadsheet = new Spreadsheet();
        // 選択されているシートを取得
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setTitle($settlement->code);

        // header
        $sheet->fromArray([
            [
                'No',
                'Date',
                'Method',
                'Category',
                '内容',
                '宛先',
                '立替人',
                '金額',
            ],
        ], null, 'A1');

        // Body
        $sheet->fromArray(collection($settlement->payments)->map(function ($payment, $index) {
            return [
                $index + 1,
                $payment->date->i18nFormat('yyyy/MM/dd') ?? '',
                $payment->payment_method->name ?? '',
                $payment->cost_category->name ?? '',
                $payment->product_name,
                $payment->store->name ?? '',
                $payment->paid_user->code,
                $payment->amount - $payment->private_amount,
            ];
        })->toArray(), null, 'A2');

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
