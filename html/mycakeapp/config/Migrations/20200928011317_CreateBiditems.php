<?php
use Migrations\AbstractMigration;

class CreateBiditems extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('biditems');
        $table->addColumn('user_id', 'integer', [
            'default' => null,
            'limit' => 11,
            'null' => false,
        ]);
        $table->addColumn('name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => false,
        ]);
        // ここにdetailsとimageを挿れる必要があるかも。so that those columns will be at Biditems table.
        // $table->addColumn('name', 'string', [
        //     'default' => null,
        //     'limit' => 1000,
        //     'null' => false,
        // ]);
        // $table->addColumn('file_name', 'string', [
        //     'default' => null,
        //     'limit' => 100,
        //     'null' => false,
        // ]);
        // 新たにもう一回migrationファイルを作る。そして、新しく出来たファイルにaddclumnを記載していく。履歴管理ができるように。
        $table->addColumn('finished', 'boolean', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('endtime', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->create();
    }
}
