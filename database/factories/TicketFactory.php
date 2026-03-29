<?php
namespace Database\Factories;
use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => 'open',
            'project_id' => Project::factory(),
            'user_id' => User::factory(),
        ];
    }
}
