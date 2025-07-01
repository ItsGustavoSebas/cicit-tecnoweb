<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\Estudiante_CursoController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\ProfileController;
use App\Models\Curso;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
require __DIR__.'/auth.php';

Route::get('/inicio', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// Rutas web que devuelven vistas Inertia
Route::get('/cursos', [CursoController::class, 'indexPublic'])->name('cursos');

// Rutas API para AJAX
Route::prefix('api')->group(function () {
    Route::get('/get-cursos', [CursoController::class, 'get']);
    Route::post('/create-curso', [CursoController::class, 'store']);
    Route::delete('/delete-curso/{curso}', [CursoController::class, 'destroy']);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/cursos/inicio',               [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/crear',         [CursoController::class, 'crear'])->name('cursos.crear');
    Route::post('/cursos/guardar',              [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/editar/{curso}',[CursoController::class, 'editar'])->name('cursos.editar');
    Route::put('/cursos/actualizar/{curso}',       [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/eliminar/{curso}',    [CursoController::class, 'delete'])->name('cursos.delete');
});

Route::get('/funcionalidades/search', function (Illuminate\Http\Request $request) {
    $search = $request->query('q');

    return \App\Models\Funcionalidad::where('nombre', 'like', "%$search%")->get();
})->name('funcionalidades.search');

Route::get('/cursos/{codigo}/inscripcion', [Estudiante_CursoController::class, 'crear'])->name('estudiante_curso.crear');
Route::get('/api/estudiante/{ci}', [EstudianteController::class, 'buscarPorCI']);
Route::post('/api/inscripcion', [Estudiante_CursoController::class, 'store']);

