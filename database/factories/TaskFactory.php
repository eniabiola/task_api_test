<?php

namespace Database\Factories;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Artisan;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Artisan::call('db:seed', ['--class' => 'TaskStatusSeeder']);

        return [
            'task_status_id' => TaskStatus::inRandomOrder()->first()->id, // Assuming TaskStatus is a related model
            'user_id' => User::factory(), // Assuming User is a related model
            'title' => $this->faker->sentence, // Random title for the task
            'description' => $this->faker->sentence, // Random description for the task
            'due_date' => $this->faker->dateTimeBetween('now', '+1 month'), // Random due date within the next month
        ];
    }
}
