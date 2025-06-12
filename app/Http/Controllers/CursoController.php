<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CursoController extends Controller
{

    public function index()
    {
        return Inertia::render('Cursos');
    }

    public function get()
    {
        return Curso::latest()->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'foto' => 'nullable|url',
            'nombre' => 'required|string',
            'duracion' => 'required|string',
            'horarios' => 'required|array',
            'horarios.*.dia' => 'required|string',
            'horarios.*.hora' => 'required|string',
            'precio' => 'required|numeric',
            'instructor' => 'required|string',
        ]);

        return Curso::create($request->all());
    }

    public function destroy(Curso $curso)
    {
        $curso->delete();
        return response()->noContent();
    }
}
