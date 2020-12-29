<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PaymentCategories Model
 *
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 *
 * @method \App\Model\Entity\PaymentCategory newEmptyEntity()
 * @method \App\Model\Entity\PaymentCategory newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\PaymentCategory[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PaymentCategory get($primaryKey, $options = [])
 * @method \App\Model\Entity\PaymentCategory findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\PaymentCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentCategory[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PaymentCategory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PaymentCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PaymentCategory[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PaymentCategory[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\PaymentCategory[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\PaymentCategory[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PaymentCategoriesTable extends Table
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

        $this->setTable('payment_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Payments', [
            'foreignKey' => 'payment_category_id',
        ]);
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
            ->scalar('name')
            ->maxLength('name', 45)
            ->allowEmptyString('name');

        return $validator;
    }
}
