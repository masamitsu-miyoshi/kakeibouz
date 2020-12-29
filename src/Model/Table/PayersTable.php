<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Payers Model
 *
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\HasMany $Payments
 *
 * @method \App\Model\Entity\Payer newEmptyEntity()
 * @method \App\Model\Entity\Payer newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Payer[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Payer get($primaryKey, $options = [])
 * @method \App\Model\Entity\Payer findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Payer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Payer[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Payer|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payer saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Payer[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payer[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payer[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Payer[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class PayersTable extends Table
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

        $this->setTable('payers');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->hasMany('Payments', [
            'foreignKey' => 'payer_id',
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
