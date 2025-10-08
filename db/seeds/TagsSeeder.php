<?php
declare(strict_types=1);

use App\Enum\TagEnum;
use Phinx\Seed\AbstractSeed;

class TagsSeeder extends AbstractSeed
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
                'name' => TagEnum::TECH_ISSUE->label(),
                'slug' => TagEnum::TECH_ISSUE->value,
            ],
            [
                'name' => TagEnum::TECH_QUESTION->label(),
                'slug' => TagEnum::TECH_QUESTION->value,
            ],
            [
                'name' => TagEnum::FATAL_ERROR->label(),
                'slug' => TagEnum::FATAL_ERROR->value,
            ],
            [
                'name' => TagEnum::SALES_QUESTION->label(),
                'slug' => TagEnum::SALES_QUESTION->value,
            ],
            [
                'name' => TagEnum::FEATURE_REQUEST->label(),
                'slug' => TagEnum::FEATURE_REQUEST->value,
            ]
        ];

        $this->table('tags')->insert($data)->save();
    }
}
