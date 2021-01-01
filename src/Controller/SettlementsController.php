<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\I18n\FrozenTime;
use Cake\I18n\Time;

/**
 * Settlements Controller
 *
 * @property \App\Model\Table\SettlementsTable $Settlements
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SettlementsController extends AppController
{
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
