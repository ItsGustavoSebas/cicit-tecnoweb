<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/inicio', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
// Rutas web que devuelven vistas Inertia
Route::get('/cursos', [CursoController::class, 'index'])->name('cursos');

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
});

Route::get('/funcionalidades/search', function (Illuminate\Http\Request $request) {
    $search = $request->query('q');

    return \App\Models\Funcionalidad::where('nombre', 'like', "%$search%")->get();
})->name('funcionalidades.search');


require __DIR__.'/auth.php';
