<?php
declare(strict_types=1);

use App\Enum\StatusEnum;
use Phinx\Seed\AbstractSeed;

class StatusesSeeder extends AbstractSeed
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
            [
                'name' => StatusEnum::TODO->label(),
                'slug' => StatusEnum::TODO->value,
            ],
            [
                'name' => StatusEnum::IN_PROGRESS->label(),
                'slug' => StatusEnum::IN_PROGRESS->value,
            ],
            [
                'name' => StatusEnum::READY_FOR_REVIEW->label(),
                'slug' => StatusEnum::READY_FOR_REVIEW->value,
            ],
            [
                'name' => StatusEnum::DONE->label(),
                'slug' => StatusEnum::DONE->value,
            ]
        ];

        $this->table('statuses')->insert($data)->save();
    }
}
