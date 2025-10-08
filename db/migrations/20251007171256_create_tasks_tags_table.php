<?php
declare(strict_types=1);
use Phinx\Migration\AbstractMigration;

class CreateTasksTagsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('task_tags', [
            'id' => false,
            'primary_key' => ['task_id', 'tag_id']
        ]);

        $table->addColumn('task_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('tag_id', 'integer', ['signed' => false, 'null' => false])
            ->addForeignKey('task_id', 'tasks', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->addForeignKey('tag_id', 'tags', 'id', [
                'delete' => 'CASCADE',
                'update' => 'CASCADE'
            ])
            ->create();
    }
}
