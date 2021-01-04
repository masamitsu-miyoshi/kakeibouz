<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payments Model
 *
 * @property \App\Model\Table\PaymentMethodsTable&\Cake\ORM\Association\BelongsTo $PaymentMethods
 * @property \App\Model\Table\CostCategoriesTable&\Cake\ORM\Association\BelongsTo $CostCategories
 * @property \App\Model\Table\StoresTable&\Cake\ORM\Association\BelongsTo $Stores
 * @property \App\Model\Table\PayersTable&\Cake\ORM\Association\BelongsTo $Payers
 *
 * @method \App\Model\Entity\Payment newEmptyEntity()
 * @method \App\Model\Entity\Payment newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Payment[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payment get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payment findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payment[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentsTable extends Table
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

        $this->setTable('payments');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('PaymentMethods', [
            'foreignKey' => 'payment_method_id',
        ]);
        $this->belongsTo('CostCategories', [
            'foreignKey' => 'cost_category_id',
        ]);
        $this->belongsTo('Stores', [
            'foreignKey' => 'store_id',
        ]);
        $this->belongsTo('PaidUsers', [
            'className' => 'Users',
            'foreignKey' => 'paid_user_id',
            'propertyName' => 'paid_user',
        ]);
        $this->belongsTo('BilledUsers', [
            'className' => 'Users',
            'foreignKey' => 'billed_user_id',
            'propertyName' => 'billed_user',
        ]);

        $this->belongsTo('Families');
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
            ->requirePresence('paid_user_id');

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
        $rules->add($rules->existsIn('paid_user_id', 'PaidUsers'));

        $rules->addUpdate(function ($entity, $options) {
            if (empty($entity->getOriginal('settlement_id'))) {
                return true;
            } else {
                return false;
            }
        }, 'settlement_id', [
            'errorField' => 'settlement_id',
            'message' => '締めたレコードの更新禁止'
        ]);

        $rules->addDelete(function ($entity, $options) {
            if (empty($entity->settlement_id)) {
                return true;
            } else {
                return false;
            }
        }, 'settlement_id', [
            'errorField' => 'settlement_id',
            'message' => '締めたレコードの更新禁止'
        ]);

        return $rules;
    }
}
