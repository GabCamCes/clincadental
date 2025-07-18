<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class MenuSeeder extends Seeder
{
    public function run()
    {
        DB::table('menus')->insert([
            // Admin
            ['nombre' => 'Usuarios',      'ruta' => '/usuarios',      'rol' => 'A', 'orden' => 1],
            ['nombre' => 'Pacientes',     'ruta' => '/pacientes',     'rol' => 'A', 'orden' => 2],
            ['nombre' => 'Médicos',       'ruta' => '/medicos',       'rol' => 'A', 'orden' => 3],
            ['nombre' => 'Especialidades','ruta' => '/especialidades','rol' => 'A', 'orden' => 4],
            ['nombre' => 'Productos',     'ruta' => '/productos',     'rol' => 'A', 'orden' => 5],
            ['nombre' => 'Servicios',     'ruta' => '/servicios',     'rol' => 'A', 'orden' => 6],
            ['nombre' => 'Promociones',   'ruta' => '/promociones',   'rol' => 'A', 'orden' => 7],
            ['nombre' => 'Citas',         'ruta' => '/citas',         'rol' => 'A', 'orden' => 8],
            ['nombre' => 'Ventas',        'ruta' => '/ventas',        'rol' => 'A', 'orden' => 9],
            ['nombre' => 'Pagos',         'ruta' => '/pagos',         'rol' => 'A', 'orden' => 10],
            // Médico
            ['nombre' => 'Mis Citas',      'ruta' => '/citas',            'rol' => 'M', 'orden' => 1],
            ['nombre' => 'Historial Clínico','ruta' => '/historial-clinico', 'rol' => 'M', 'orden' => 2],
            ['nombre' => 'Horarios',       'ruta' => '/horarios-atencion', 'rol' => 'M', 'orden' => 3],
            // Paciente
            ['nombre' => 'Mis Citas',         'ruta' => '/citas',             'rol' => 'P', 'orden' => 1],
            ['nombre' => 'Mi Historial',      'ruta' => '/historial-clinico', 'rol' => 'P', 'orden' => 2],
            ['nombre' => 'Mis Pagos',         'ruta' => '/pagos',            'rol' => 'P', 'orden' => 3],
        ]);
    }
}

