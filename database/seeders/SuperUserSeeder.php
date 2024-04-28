<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuperUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'SuperUsuario',
            'email' => 'superUsuario@admin.com',
            'password' => bcrypt('SuperUserAdmin12345/**-'),
        ]);
    }
}
