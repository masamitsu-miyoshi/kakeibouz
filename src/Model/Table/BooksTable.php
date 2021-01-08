<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Book;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Books Model
 *
 * @method \App\Model\Entity\Book newEmptyEntity()
 * @method \App\Model\Entity\Book newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Book[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Book get($primaryKey, $options = [])
 * @method \App\Model\Entity\Book findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Book patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Book[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Book|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Book saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Book[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Book[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Book[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Book[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 *
 * @property PaymentsTable $Payments
 * @property BillsTable $Bills
 * @property FamiliesTable $Families
 * @property SettlementsTable $Settlements
 */
class BooksTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Families');

        $this->hasMany('Payments')
            ->setSort(['Payments.date' => 'DESC', 'Payments.id' => 'DESC']);

        $this->hasMany('Settlements')
            ->setSort(['Settlements.user_id' => 'ASC'])
            ->setDependent(true);

        $this->hasMany('Bills')
            ->setDependent(true);
    }

    public function afterSave(Event $event, Book $entity, ArrayObject $options) {
        $target = Time::parse(substr($entity->code, 0, 4) . '-' . substr($entity->code, 4, 2) . '-01');
        $target->addMonth(1);

        $family = $this->Families->get($entity->family_id, [
                'contain' => ['Users']
            ]);

        // 締め対象の支払いを一括Update
        $this->Payments->query()
            ->update()
            ->set(['book_id' => $entity->id])
            ->where(['book_id IS NULL', 'date <' => $target->i18nFormat('yyyy-MM-dd')])
            ->execute();

        // 支払から請求を作成
        $select = $this->Payments->find()
            ->leftJoinWith('Families.Users')
            ->select([
                'Families.id',
                $entity->id,
                'Payments.id',
                'Users.id',
                'Users.bill_rate',
                'paid_amount' => 'Payments.amount - Payments.private_amount'
            ])
            ->where(['Payments.book_id' => $entity->id]);
        $this->Bills->query()
            ->insert([
                'family_id',
                'book_id',
                'payment_id',
                'user_id',
                'rate',
                'paid_amount'
            ])
            ->values($select)
            ->execute();

        // 請求金額を一括Update
        $this->Bills->query()
            ->update()
            ->set(['amount = rate * paid_amount'])
            ->where(['book_id' => $entity->id])
            ->execute();

        // 請求済みを集計
        $bills = $this->Bills->query()
            ->select([
                'user_id',
                'bill_amount' => 'SUM(Bills.amount)',
            ])
            ->group(['user_id'])
            ->where(['book_id' => $entity->id])
            ->all()
            ->combine('user_id', 'bill_amount')
            ->toArray();


        // 支払済みを集計
        $payments = $this->Payments->find()
            ->select([
                'paid_user_id',
                'paid_amount' => 'SUM(Payments.amount - Payments.private_amount)'
            ])
            ->group(['paid_user_id'])
            ->where(['book_id' => $entity->id])
            ->all()
            ->combine('paid_user_id', 'paid_amount')
            ->toArray();

        foreach ($family->users as $user) {
            // 債務を作成
            $this->Settlements->query()
                ->insert([
                    'book_id',
                    'user_id',
                    'billed_amount',
                    'paid_amount',
                    'amount',
                ])
                ->values([
                    'book_id' => $entity->id,
                    'user_id' => $user->id,
                    'billed_amount' => $bills[$user->id] ?? 0,
                    'paid_amount' => $payments[$user->id] ?? 0,
                    'amount' => $bills[$user->id] - $payments[$user->id],
                ])
                ->execute();
        }


        // 支払いに請求先ユーザが設定されている場合、按分率を調整する。
//        $this->Payments->find()
//            ->where(['Payments.book_id' => $entity->id, 'Payments.billed_user_id IS NOT NULL'])
//            ->all();// eachで、payments.idから、処理する。

    }

    public function beforeDelete(Event $event, Book $entity, ArrayObject $options) {
        // IDを一括Update
        $query = $this->Payments->query();
        $query->update()
            ->set(['book_id' => null])
            ->where(['book_id' =>  $entity->id])
            ->execute();
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('code')
            ->minLength('code', 6)
            ->maxLength('code', 6)
            ->requirePresence('code', 'create')
            ->notEmptyString('code')
            ->add('code', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['code']), ['errorField' => 'code']);

        return $rules;
    }
}
