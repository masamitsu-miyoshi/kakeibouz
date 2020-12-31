<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\NotFoundException;
use Cake\I18n\FrozenTime;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'setup']);
    }

    public function setup($username = null)
    {
        $user = $this->Users
            ->find('all', [
                'conditions' => ['Users.username' => $username]
            ])
            ->first();

        if (!$user) {
            throw new NotFoundException();
        }

        if ($user->initialized) {
            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());

            $user->initialized = FrozenTime::now('Asia/Tokyo');
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'login']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }

        $this->set(compact('user'));
    }

    public function login()
    {
        $result = $this->Authentication->getResult();
        // ユーザーがログインしている場合は、そのユーザーを送り出してください。
        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/payments';
            return $this->redirect($target);
        }
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('ユーザー名とパスワードが無効です');
        }
        $user = $this->Users->newEmptyEntity();
        $this->set(compact('user'));
    }

    public function logout()
    {
        $this->Authentication->logout();
        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
