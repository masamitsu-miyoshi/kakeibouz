<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ReceiptImages Controller
 *
 * @property \App\Model\Table\ReceiptImagesTable $ReceiptImages
 *
 * @method \App\Model\Entity\File[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ReceiptImagesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $receiptImages = $this->paginate($this->ReceiptImages);

        $this->set(compact('receiptImages'));
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
        $receiptImage = $this->ReceiptImages->get($id, [
            'contain' => [],
        ]);

        $this->set('receiptImage', $receiptImage);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $receiptImage = $this->ReceiptImages->newEmptyEntity();
        if ($this->request->is('post')) {
            $receiptImage = $this->ReceiptImages->patchEntity($receiptImage, $this->request->getData());

            $data = $this->request->getData('data');
            $receiptImage->name = $data->getClientFilename();
            $receiptImage->media_type = $data->getClientMediaType();
            $receiptImage->data = $data->getStream()->getContents();

            if ($this->ReceiptImages->save($receiptImage)) {
                $this->Flash->success(__('The file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The file could not be saved. Please, try again.'));
        }
        $this->set(compact('receiptImage'));
    }

    /**
     * Edit method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $receiptImage = $this->ReceiptImages->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {

            $receiptImage = $this->ReceiptImages->patchEntity($receiptImage, $this->request->getData());

            $data = $this->request->getData('data');
            $receiptImage->name = $data->getClientFilename();
            $receiptImage->media_type = $data->getClientMediaType();
            $receiptImage->data = $data->getStream()->getContents();

            if ($this->ReceiptImages->save($receiptImage)) {
                $this->Flash->success(__('The file has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The file could not be saved. Please, try again.'));
        }
        $this->set(compact('receiptImage'));
    }

    /**
     * Delete method
     *
     * @param string|null $id File id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $receiptImage = $this->ReceiptImages->get($id);
        if ($this->ReceiptImages->delete($receiptImage)) {
            $this->Flash->success(__('The file has been deleted.'));
        } else {
            $this->Flash->error(__('The file could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
