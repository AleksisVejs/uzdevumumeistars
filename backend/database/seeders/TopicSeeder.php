<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $algebra = Topic::updateOrCreate(
            ['slug' => 'algebra-ievads'],
            ['name' => 'Algebra Ievads', 'prerequisite_level' => 1, 'next_topic_ids' => []]
        );

        $linear = Topic::updateOrCreate(
            ['slug' => 'linearas-vienadojumu-sistemas'],
            ['name' => 'Lineārās vienādojumu sistēmas', 'prerequisite_level' => 2, 'next_topic_ids' => []]
        );

        $algebra->update(['next_topic_ids' => [$linear->id]]);
    }
}
