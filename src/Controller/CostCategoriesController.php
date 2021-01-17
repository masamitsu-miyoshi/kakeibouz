<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * CostCategories Controller
 *
 * @property \App\Model\Table\CostCategoriesTable $CostCategories
 *
 * @method \App\Model\Entity\CostCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CostCategoriesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->CostCategories->find()->where(['family_id'=> $this->request->getSession()->read('Auth.family_id')]);

        $costCategories = $this->paginate($query);

        $this->set(compact('costCategories'));
    }

    /**
     * View method
     *
     * @param string|null $id Cost Category id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $costCategory = $this->CostCategories->find()
            ->where([
                'id' => $id,
                'family_id' => $this->request->getSession()->read('Auth.family_id')
            ])
            ->firstOrFail();

        $this->set('costCategory', $costCategory);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $costCategory = $this->CostCategories->newEmptyEntity();
        if ($this->request->is('post')) {
            $costCategory = $this->CostCategories->patchEntity($costCategory, $this->request->getData());

            $costCategory->family_id = $this->request->getSession()->read('Auth.family_id');

            if ($this->CostCategories->save($costCategory)) {
                $this->Flash->success(__('The cost category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cost category could not be saved. Please, try again.'));
        }
        $this->set(compact('costCategory'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Cost Category id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $costCategory = $this->CostCategories->find()
            ->where([
                'id' => $id,
                'family_id' => $this->request->getSession()->read('Auth.family_id')
            ])
            ->firstOrFail();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $costCategory = $this->CostCategories->patchEntity($costCategory, $this->request->getData());

            $costCategory->family_id = $this->request->getSession()->read('Auth.family_id');

            if ($this->CostCategories->save($costCategory)) {
                $this->Flash->success(__('The cost category has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The cost category could not be saved. Please, try again.'));
        }
        $this->set(compact('costCategory'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Cost Category id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $costCategory = $this->CostCategories->find()
            ->where([
                'id' => $id,
                'family_id' => $this->request->getSession()->read('Auth.family_id')
            ])
            ->firstOrFail();
        if ($this->CostCategories->delete($costCategory)) {
            $this->Flash->success(__('The cost category has been deleted.'));
        } else {
            $this->Flash->error(__('The cost category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
