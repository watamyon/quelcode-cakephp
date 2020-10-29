<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Biditems Model
 *
 * @property \App\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Table\BidinfoTable&\Cake\ORM\Association\HasMany $Bidinfo
 * @property \App\Model\Table\BidrequestsTable&\Cake\ORM\Association\HasMany $Bidrequests
 *
 * @method \App\Model\Entity\Biditem get($primaryKey, $options = [])
 * @method \App\Model\Entity\Biditem newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Biditem[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Biditem|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Biditem saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Biditem patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Biditem[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Biditem findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BiditemsTable extends Table
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
        // 使用するデータベーステーブルの名前を設定している
        $this->setTable('biditems');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
        $this->hasOne('Bidinfo', [
            'foreignKey' => 'biditem_id',
        ]);
        $this->hasMany('Bidrequests', [
            'foreignKey' => 'biditem_id',
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
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('detail')
            ->maxLength('detail', 1000)
            ->requirePresence('detail', 'create')
            ->notEmptyString('detail');

        $validator
            ->scalar('file_name','文字列ではありません')
            ->maxLength('file_name', 100,'エラーメッセージ')
            ->requirePresence('file_name', 'create')
            ->notEmptyFile('file_name');

        $validator
            ->boolean('finished')
            ->requirePresence('finished', 'create')
            ->notEmptyString('finished');

        $validator
            ->dateTime('endtime')
            ->requirePresence('endtime', 'create')
            ->notEmptyDateTime('endtime');

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
        $rules->add(function ($entity, $options) {
            $file = $entity->file_name;
            $ext = substr($file, -4);
            $ext_lower = mb_strtolower($ext);
            if(!($ext_lower == '.gif' || $ext_lower == '.jpg' || $ext_lower == '.png' || $ext_lower == 'jpeg')){
                return false;
            } else {
                return true;
            }
        }, 'fileNameCheck', [
            // エラーメッセージが出せない。なぜ？（ファイルの識別はできる状態）
            'errorField' => 'nav',
            'message' => '画像ファイルを選択してください。'
        ]);        

        $rules->add($rules->existsIn(['user_id'], 'Users'));
        return $rules;
    }
}

// これが見本の処理、コレに当てはめるようにrule作った
            // if ($entity->title != 'テスト') {
            //     return true;
            // } else {
            //     return false;
            // }
            // 形式パクる
            // 既に$entity->file_nameでエンティティにsaveするときのfile_nameを取れているから、このfile_nameの末尾四桁が
            // 指定する拡張子に当てはまるかどうかをチェックすればいい。
