<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use App\Models\TipoEstudiante;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EstudianteController extends Controller
{
    public function index()
    {
        $estudiantes = Estudiante::with('tipo')
            ->orderBy('codigo')
            ->get()
            ->map(fn($e) => [
                'codigo' => $e->codigo,
                'nombre' => $e->nombre,
                'apellido' => $e->apellido,
                'ci' => $e->ci,
                'tipo_nombre' => $e->tipo?->nombre,
            ]);

        return Inertia::render('Estudiantes/Index', compact('estudiantes'));
    }

    public function create()
    {
        $tipos = TipoEstudiante::orderBy('nombre')
            ->get(['codigo','nombre']);
        return Inertia::render('Estudiantes/Form', [
            'mode' => 'create',
            'estudiante' => null,
            'tipos' => $tipos,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:255',
            'apellido'           => 'required|string|max:255',
            'ci'                 => 'required|integer|unique:estudiante,ci',
            'tipo_estudiante_id' => 'required|integer|exists:tipo_estudiante,codigo',
        ]);

        Estudiante::create($data);

        return redirect()->route('estudiantes.index')
                         ->with('success','Estudiante creado');
    }

    public function edit(Estudiante $estudiante)
    {
        $tipos = TipoEstudiante::orderBy('nombre')
            ->get(['codigo','nombre']);
        return Inertia::render('Estudiantes/Form', [
            'mode' => 'edit',
            'estudiante' => $estudiante,
            'tipos' => $tipos,
        ]);
    }

    public function update(Request $request, Estudiante $estudiante)
    {
        $data = $request->validate([
            'nombre'             => 'required|string|max:255',
            'apellido'           => 'required|string|max:255',
            'ci'                 => "required|integer|unique:estudiante,ci,{$estudiante->codigo},codigo",
            'tipo_estudiante_id' => 'required|integer|exists:tipo_estudiante,codigo',
        ]);

        $estudiante->update($data);

        return redirect()->route('estudiantes.index')
                         ->with('success','Estudiante actualizado');
    }

    public function destroy(Estudiante $estudiante)
    {
        $estudiante->delete();

        return redirect()->route('estudiantes.index')
                         ->with('success','Estudiante eliminado');
    }
}
