<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('task_statuses')->insert([
            ['name' => 'pending', 'is_active' => true],
            ['name' => 'in progress', 'is_active' => true],
            ['name' => 'completed', 'is_active' => true],
        ]);
    }
}
