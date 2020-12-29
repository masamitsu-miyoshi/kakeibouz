<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * Payers Controller
 *
 * @property \App\Model\Table\PayersTable $Payers
 *
 * @method \App\Model\Entity\Payer[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PayersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $payers = $this->paginate($this->Payers);

        $this->set(compact('payers'));
    }

    /**
     * View method
     *
     * @param string|null $id Payer id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $payer = $this->Payers->get($id, [
            'contain' => ['Payments'],
        ]);

        $this->set('payer', $payer);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $payer = $this->Payers->newEmptyEntity();
        if ($this->request->is('post')) {
            $payer = $this->Payers->patchEntity($payer, $this->request->getData());
            if ($this->Payers->save($payer)) {
                $this->Flash->success(__('The payer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payer could not be saved. Please, try again.'));
        }
        $this->set(compact('payer'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Payer id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $payer = $this->Payers->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $payer = $this->Payers->patchEntity($payer, $this->request->getData());
            if ($this->Payers->save($payer)) {
                $this->Flash->success(__('The payer has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payer could not be saved. Please, try again.'));
        }
        $this->set(compact('payer'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Payer id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $payer = $this->Payers->get($id);
        if ($this->Payers->delete($payer)) {
            $this->Flash->success(__('The payer has been deleted.'));
        } else {
            $this->Flash->error(__('The payer could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
