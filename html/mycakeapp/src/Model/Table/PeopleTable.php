<?php
namespace App\Model\Table;

use Cake\ORM\Table;

class PeopleTable extends Table {
	
	public function initialize(array $config) {
		parent::initialize($config);
		$this->setDisplayField('name');
		$this->hasMany('Messages');
	}

	public function validationDefault(Validator $validator) {
		$validator
			->integer('id')
			->allowEmpty('id', 'create');

		$validator
			->scalar('name')
			->requirePresence('name', 'create')
			->notEmpty('name');

		$validator
			->scalar('mail')
			->allowEmpty('mail');

		$validator
			->integer('age')
			->requirePresence('age', 'create')
			->notEmpty('age');

		return $validator;
	}
}
