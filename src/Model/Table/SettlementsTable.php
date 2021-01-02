<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Settlement;
use ArrayObject;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Settlements Model
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
 *
 * @property PaymentsTable $Payments
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

        $this->setTable('settlements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Payments', [
            'foreignKey' => 'settlement_id',
            'sort' => ['Payments.date' => 'DESC', 'Payments.id' => 'DESC']
        ]);
    }

    public function afterSave(Event $event, Settlement $entity, ArrayObject $options) {
        $target = Time::parse(substr($entity->code, 0, 4) . '-' . substr($entity->code, 4, 2) . '-01');
        $target->addMonth(1);

        // IDを一括Update
        $query = $this->Payments->query();
        $query->update()
            ->set(['settlement_id' => $entity->id])
            ->where(['settlement_id IS NULL', 'date <' => $target->i18nFormat('yyyy-MM-dd')])
            ->execute();
    }

    public function beforeDelete(Event $event, Settlement $entity, ArrayObject $options) {
        // IDを一括Update
        $query = $this->Payments->query();
        $query->update()
            ->set(['settlement_id' => null])
            ->where(['settlement_id' =>  $entity->id])
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
