<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UbicacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('ubicacion')->insert([
            [
                'nombre' => 'Plaza 24 de Septiembre',
                'referencia' => 'Plaza Principal',
                'link' => 'https://es.wikipedia.org/wiki/Plaza_24_de_Septiembre',
                'latitud' => -17.78366801337319,
                'longitud' => -63.18159046939128,
                'cliente_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
