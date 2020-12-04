<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Shipping Model
 *
 * @property \App\Model\Table\ItemsTable&\Cake\ORM\Association\BelongsTo $Items
 *
 * @method \App\Model\Entity\Shipping get($primaryKey, $options = [])
 * @method \App\Model\Entity\Shipping newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Shipping[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Shipping|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shipping saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Shipping patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Shipping[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Shipping findOrCreate($search, callable $callback = null, $options = [])
 */
class ShippingTable extends Table
{
	/**
	 * Initialize method
	 *
	 * @param array $config The configuration for the Table.
	 * @return void
	 */
	public function initialize(array $config)
	{
		parent::initialize($config);

		$this->setTable('shipping');
		$this->setDisplayField('id');
		$this->setPrimaryKey('id');
		$this->belongsTo('Biditems', [
			'foreignKey' => 'item_id',
			'joinType' => 'INNER',
		]);
	}

	/**
	 * Default validation rules.
	 *
	 * @param \Cake\Validation\Validator $validator Validator instance.
	 * @return \Cake\Validation\Validator
	 */
	public function validationDefault(Validator $validator)
	{
		$validator
			->integer('id')
			->allowEmptyString('id', null, 'create');

		$validator
			->scalar('bidder_name')
			->maxLength('bidder_name', 255)
			->requirePresence('bidder_name', 'create')
			->notEmptyString('bidder_name');

		$validator
			->scalar('address')
			->maxLength('address', 255)
			->requirePresence('address', 'create')
			->notEmptyString('address');

		$validator
			->scalar('phone_number')
			->maxLength('phone_number', 255)
			->requirePresence('phone_number', 'create')
			->notEmptyString('phone_number');

		$validator
			->boolean('is_shipped')
			->requirePresence('is_shipped', 'create')
			->notEmptyString('is_shipped');

		$validator
			->boolean('is_received')
			->requirePresence('is_received', 'create')
			->notEmptyString('is_received');

		return $validator;
	}

	/**
	 * Returns a rules checker object that will be used for validating
	 * application integrity.
	 *
	 * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
	 * @return \Cake\ORM\RulesChecker
	 */
	public function buildRules(RulesChecker $rules)
	{
		$rules->add($rules->existsIn(['item_id'], 'Biditems'));
		return $rules;
	}
}