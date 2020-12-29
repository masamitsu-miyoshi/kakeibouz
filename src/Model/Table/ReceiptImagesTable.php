<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ReceiptImages Model
 *
 * @property \App\Model\Table\PaymentsTable&\Cake\ORM\Association\BelongsTo $Payments
 *
 * @method \App\Model\Entity\ReceiptImage newEmptyEntity()
 * @method \App\Model\Entity\ReceiptImage newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptImage[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptImage get($primaryKey, $options = [])
 * @method \App\Model\Entity\ReceiptImage findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\ReceiptImage patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptImage[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\ReceiptImage|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptImage saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ReceiptImage[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ReceiptImage[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\ReceiptImage[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\ReceiptImage[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReceiptImagesTable extends Table
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

        $this->setTable('receipt_images');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Payments', [
            'foreignKey' => 'payment_id',
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

        $validator
            ->scalar('media_type')
            ->maxLength('media_type', 45)
            ->allowEmptyString('media_type');

        $validator
            ->allowEmptyString('data');

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
        $rules->add($rules->existsIn(['payment_id'], 'Payments'));

        return $rules;
    }
}
