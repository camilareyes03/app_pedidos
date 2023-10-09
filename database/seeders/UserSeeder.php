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
                'password' => Hash::make('123'),
                'tipo_usuario' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kevin',
                'email' => 'kevin@gmail.com',
                'ci' => '46151651',
                'telefono' => '63202017',
                'password' => null,
                'tipo_usuario' => 'cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rodrigo',
                'email' => 'rodrigo@gmail.com',
                'ci' => '44613255',
                'telefono' => '71270000',
                'password' => null,
                'tipo_usuario' => 'repartidor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Camila',
                'email' => 'camila@gmail.com',
                'ci' => '16518464',
                'telefono' => '61185315',
                'password' => null,
                'tipo_usuario' => 'cliente',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jose',
                'email' => 'jose@gmail.com',
                'ci' => '84651616',
                'telefono' => '79330023',
                'password' => null,
                'tipo_usuario' => 'repartidor',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
