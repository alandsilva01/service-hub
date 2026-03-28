<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserProfileSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::create([
            'name' => 'Alan Dev',
            'email' => 'alan@servicehub.com',
            'password' => Hash::make('password'),
        ]);

        UserProfile::create([
            'user_id' => $user->id,
            'phone' => '11 99999-0000',
            'position' => 'Desenvolvedor',
        ]);
    }
}
