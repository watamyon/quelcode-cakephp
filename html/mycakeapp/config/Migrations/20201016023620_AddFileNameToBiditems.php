<?php
use Migrations\AbstractMigration;

class AddFileNameToBiditems extends AbstractMigration
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
        $table->addColumn('file_name', 'string', [
            'default' => null,
            'limit' => 100,
            'null' => true,
        ]);
        $table->update();
    }
}
