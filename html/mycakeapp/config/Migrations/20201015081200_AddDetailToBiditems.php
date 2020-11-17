<?php
use Migrations\AbstractMigration;

class AddDetailToBiditems extends AbstractMigration
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
        $table->addColumn('detail', 'text', [
            'default' => null,
            'limit' => 1000,
            'null' => true,
        ]);
        $table->update();
    }
}
