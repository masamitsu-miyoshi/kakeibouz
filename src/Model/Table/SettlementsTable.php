<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Settlements Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\Settlement newEmptyEntity()
 * @method \App\Model\Entity\Settlement newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Settlement[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Settlement get($primaryKey, $options = [])
 * @method \App\Model\Entity\Settlement findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Settlement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Settlement[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Settlement|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Settlement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Settlement[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SettlementsTable extends Table
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

        $this->addBehavior('Timestamp');

        $this->belongsTo('Books');
        $this->belongsTo('Users');

        $this->hasMany('Bills')
            ->setForeignKey(['book_id', 'user_id'])
            ->setBindingKey(['book_id', 'user_id'])
            ->setSort(['Bills.amount' => 'DESC', 'Bills.id' => 'ASC']);

        $this->hasMany('Payments')
            ->setForeignKey(['book_id', 'user_id'])
            ->setBindingKey(['book_id', 'user_id']);
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
            ->decimal('bill_amount')
            ->notEmptyString('bill_amount');

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
        $rules->add($rules->existsIn(['book_id'], 'Books'), ['errorField' => 'book_id']);
        $rules->add($rules->existsIn(['user_id'], 'Users'), ['errorField' => 'user_id']);

        return $rules;
    }
}
