<?php

use App\Http\Controllers\CursoController;
use App\Http\Controllers\Estudiante_CursoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\ProfesorController;
use App\Models\Visita;
use App\Models\EstudianteCurso;
use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\Profesor;
use App\Models\Factura;
use Illuminate\Support\Facades\DB;
require __DIR__.'/auth.php';

Route::get('/', function () {
    return Inertia::render('Cursos', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('welcome');
// Rutas web que devuelven vistas Inertia
Route::get('/cursos', [CursoController::class, 'indexPublic'])->name('cursos');
Route::get('/roles', [RoleController::class, 'index'])->name('roles');

// Rutas API para AJAX
Route::prefix('api')->group(function () {
    Route::get('/get-cursos', [CursoController::class, 'get']);
    Route::post('/create-curso', [CursoController::class, 'store']);
    Route::delete('/delete-curso/{curso}', [CursoController::class, 'destroy']);
});


Route::get('/dashboard', function () {
    // 1. Rutas más accedidas (top 5)
    $visitasTotal = Visita::select('ruta', DB::raw('SUM(veces) as total'))
        ->groupBy('ruta')
        ->orderByDesc('total')
        ->limit(5)
        ->get();

    // 2. Cursos más solicitados (top 5)
    $cursosSolicitados = EstudianteCurso::select('curso_id', DB::raw('COUNT(*) as total'))
        ->groupBy('curso_id')
        ->with('curso:codigo,nombre')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->map(fn($row) => [
            'curso_id' => $row->curso_id,
            'nombre'   => $row->curso->nombre,
            'total'    => $row->total,
        ]);

    // 3. Estudiantes con más inscripciones (top 5)
    $estudiantesTop = EstudianteCurso::select('estudiante_id', DB::raw('COUNT(*) as total'))
        ->groupBy('estudiante_id')
        ->with('estudiante:codigo,nombre,apellido')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->map(fn($row) => [
            'estudiante_id' => $row->estudiante_id,
            'nombre'        => $row->estudiante->nombre.' '.$row->estudiante->apellido,
            'total'         => $row->total,
        ]);

    // 4. Profesores que dictan más cursos (top 5)
    //    asumimos que Curso tiene 'profesor_id' y relación profesor()
    $profesoresTop = Curso::select('profesor_id', DB::raw('COUNT(*) as total'))
        ->groupBy('profesor_id')
        ->with('profesor:codigo,nombre,apellido')
        ->orderByDesc('total')
        ->limit(5)
        ->get()
        ->map(fn($row) => [
            'profesor_id' => $row->profesor_id,
            'nombre'      => $row->profesor->nombre.' '.$row->profesor->apellido,
            'total'       => $row->total,
        ]);

    // 5. Total de dinero generado
    $totalIngresos = Factura::sum('monto');

    return Inertia::render('Dashboard', [
        'visitasTotal'           => $visitasTotal,
        'cursosSolicitados' => $cursosSolicitados,
        'estudiantesTop'    => $estudiantesTop,
        'profesoresTop'     => $profesoresTop,
        'totalIngresos'     => $totalIngresos,
    ]);
})->middleware(['auth','verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');



    Route::get('/cursos/inicio',               [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/cursos/crear',         [CursoController::class, 'crear'])->name('cursos.crear');
    Route::post('/cursos/guardar',              [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/cursos/editar/{curso}',[CursoController::class, 'editar'])->name('cursos.editar');
    Route::put('/cursos/actualizar/{curso}',       [CursoController::class, 'update'])->name('cursos.update');
    Route::delete('/cursos/eliminar/{curso}',    [CursoController::class, 'delete'])->name('cursos.delete');
    Route::get('/cursos/{curso}/estudiantes', [Estudiante_CursoController::class, 'verEstudiantes'])->name('cursos.verEstudiantes');
    Route::get('cursos/export', [CursoController::class,'export'])
     ->name('cursos.export');
    Route::get('cursos/{curso}/inscripciones/export',
           [CursoController::class,'exportInscripciones'])
     ->name('cursos.inscripciones.export');
    Route::get('inscripciones/{inscripcion}/certificado',
           [Estudiante_CursoController::class, 'certificado'])
     ->name('inscripciones.certificado');

Route::get('inscripciones/{inscripcion}/formulario',
           [Estudiante_CursoController::class, 'formulario'])
     ->name('inscripciones.formulario');
});

Route::get('/funcionalidades/search', function (Illuminate\Http\Request $request) {
    $search = $request->query('q');

    return \App\Models\Funcionalidad::where('nombre', 'like', "%$search%")->get();
})->name('funcionalidades.search');

Route::get('/cursos/{codigo}/inscripcion', [Estudiante_CursoController::class, 'crear'])->name('estudiante_curso.crear');
Route::get('/api/estudiante/{ci}', [EstudianteController::class, 'buscarPorCI']);
Route::post('/api/inscripcion', [Estudiante_CursoController::class, 'store']);
Route::get('cursos/inscripciones/buscar',      // AJAX, devuelve JSON
           [Estudiante_CursoController::class,'buscarPorCI'])
     ->name('public.inscripciones.buscar');

Route::get('cursos/inscripciones/{inscripcion}/formulario',
           [Estudiante_CursoController::class,'formulario'])
     ->name('public.inscripciones.formulario');

Route::get('cursos/inscripciones/{inscripcion}/certificado',
           [Estudiante_CursoController::class,'certificado'])
     ->name('public.inscripciones.certificado');

Route::middleware(['auth', 'verified'])
    ->group(function () {
        Route::get('roles/{role}/functionalities', [RoleController::class, 'editFunctionalities'])->name('roles.functionalities.edit');
        Route::put('roles/{role}/functionalities', [RoleController::class, 'updateFunctionalities'])->name('roles.functionalities.update');
        Route::resource('roles', RoleController::class);
    });

    Route::middleware(['auth','verified'])->group(function(){
        Route::get('users/export', [UserController::class, 'export'])
            ->name('users.export');        
        Route::resource('users', UserController::class);
        Route::get('facturas/export', [FacturaController::class, 'export'])
            ->name('facturas.export');
        Route::get('facturas/{factura}/pdf', [FacturaController::class, 'pdf'])
            ->name('facturas.pdf');
        Route::get('estudiantes/export', [EstudianteController::class, 'export'])
            ->name('estudiantes.export');
        Route::resource('estudiantes', EstudianteController::class);
        Route::get('profesores/export', [ProfesorController::class, 'export'])
            ->name('profesores.export');
        Route::resource('profesores', ProfesorController::class)
            ->parameters(['profesores' => 'profesor']);
        Route::resource('facturas', FacturaController::class)
         ->only(['index','show','create','store']);

    });

