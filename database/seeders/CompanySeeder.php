<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'PetShop Patinhas Felizes', 'email' => 'contato@patinhasfelizes.com', 'phone' => '11 91234-5678'],
            ['name' => 'Clínica Veterinária BemEstar', 'email' => 'clinica@bemestarpet.com', 'phone' => '11 92345-6789'],
            ['name' => 'Pet Center Amigos', 'email' => 'atendimento@petamigos.com', 'phone' => '11 93456-7890'],
        ];

        foreach ($companies as $company) {
            Company::create($company);
        }
    }
}
