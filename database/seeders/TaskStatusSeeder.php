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
            ['name' => 'pending', 'final_step' => false, 'badge_colour' => 'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold'],
            ['name' => 'in progress', 'final_step' => false, 'badge_colour' => 'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold'],
            ['name' => 'completed', 'final_step' => false, 'badge_colour' => 'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold'],
            ['name' => 'cancelled', 'final_step' => true, 'badge_colour' => 'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold'],
        ]);
    }
}
