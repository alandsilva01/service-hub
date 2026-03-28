<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $projects = [
            ['company_id' => 1, 'name' => 'Sistema de Agendamento', 'description' => 'Agendamento de banho, tosa e consultas online'],
            ['company_id' => 1, 'name' => 'Loja Virtual', 'description' => 'E-commerce de produtos pet'],
            ['company_id' => 2, 'name' => 'Prontuário Digital', 'description' => 'Histórico médico dos animais'],
            ['company_id' => 3, 'name' => 'App Fidelidade', 'description' => 'Programa de pontos para clientes'],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }
    }
}
