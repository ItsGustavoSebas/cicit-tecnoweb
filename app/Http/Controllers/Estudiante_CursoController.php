<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\EstudianteCurso;
use Inertia\Inertia;
use Illuminate\Http\Request;

class Estudiante_CursoController extends Controller
{
   
    public function crear($codigo)
    {
        $curso = Curso::with([
            'profesor',
            'cronogramas',
            'precios' => fn ($q) => $q->orderByDesc('precio')->take(1)
        ])->findOrFail($codigo);
    
        return Inertia::render('CursoInscripcion', [
            'curso' => $curso
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'estudiante_id' => 'required|exists:estudiante,codigo',
            'curso_id' => 'required|exists:curso,codigo',
            'monto' => 'required|numeric|min:0',
        ]);
        $existe = EstudianteCurso::where('estudiante_id', $validated['estudiante_id'])
                          ->where('curso_id', $validated['curso_id'])
                          ->exists();

        if ($existe) {
            return response()->json(['error' => 'Ya está inscrito en este curso.'], 409);
        }

        $inscripcion = EstudianteCurso::create([
            'estudiante_id' => $validated['estudiante_id'],
            'curso_id' => $validated['curso_id'],
            'monto' => $validated['monto'],
            'estado_id' => 1, 
            'factura_id' => null,
        ]);

        return response()->json([
            'message' => 'Inscripción registrada correctamente.',
            'data' => $inscripcion,
        ]);
    }
}
