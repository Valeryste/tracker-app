<?php


use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            'username' => 'admin',
            'password' => password_hash('admintest', PASSWORD_DEFAULT),
            'is_admin' => true
        ];

        $this->table('users')->insert($data)->save();
    }
}
