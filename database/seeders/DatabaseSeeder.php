<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'adm@gmail.com',
            'password' => '12345678',
        ]);
        DB::table('cursos')->insert([
            [
                'foto' => 'https://i.ytimg.com/vi/955AjducFw4/maxresdefault.jpg',
                'nombre' => 'Curso de Laravel con Inertia',
                'duracion' => '40 horas',
                'horarios' => json_encode([
                    ['dia' => 'Lunes', 'hora' => '18:00'],
                    ['dia' => 'Miércoles', 'hora' => '18:00']
                ]),
                'precio' => 250.00,
                'instructor' => 'Juan Pérez',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'foto' => 'https://i.ytimg.com/vi/0MdVOLKMYEo/maxresdefault.jpg',
                'nombre' => 'Vue.js desde cero',
                'duracion' => '30 horas',
                'horarios' => json_encode([
                    ['dia' => 'Martes', 'hora' => '19:00'],
                    ['dia' => 'Jueves', 'hora' => '19:00']
                ]),
                'precio' => 200.00,
                'instructor' => 'Ana Torres',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
