<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'ci' => '46841533',
                'telefono' => '71239710',
                'foto' => null,
                'password' => Hash::make('123'),
                'tipo_usuario' => 'administrador',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
