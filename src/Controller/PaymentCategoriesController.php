<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * PaymentCategories Controller
 *
 * @property \App\Model\Table\PaymentCategoriesTable $PaymentCategories
 *
 * @method \App\Model\Entity\PaymentCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PaymentCategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $paymentCategories = $this->paginate($this->PaymentCategories);

        $this->set(compact('paymentCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id Payment Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $paymentCategory = $this->PaymentCategories->get($id, [
            'contain' => ['Payments'],
        ]);

        $this->set('paymentCategory', $paymentCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $paymentCategory = $this->PaymentCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $paymentCategory = $this->PaymentCategories->patchEntity($paymentCategory, $this->request->getData());
            if ($this->PaymentCategories->save($paymentCategory)) {
                $this->Flash->success(__('The payment category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payment category could not be saved. Please, try again.'));
        }
        $this->set(compact('paymentCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Payment Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $paymentCategory = $this->PaymentCategories->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $paymentCategory = $this->PaymentCategories->patchEntity($paymentCategory, $this->request->getData());
            if ($this->PaymentCategories->save($paymentCategory)) {
                $this->Flash->success(__('The payment category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The payment category could not be saved. Please, try again.'));
        }
        $this->set(compact('paymentCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Payment Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $paymentCategory = $this->PaymentCategories->get($id);
        if ($this->PaymentCategories->delete($paymentCategory)) {
            $this->Flash->success(__('The payment category has been deleted.'));
        } else {
            $this->Flash->error(__('The payment category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
