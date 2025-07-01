<?php

namespace App\Http\Controllers;

use App\Models\Profesor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfesorController extends Controller
{
    public function index()
    {
        $profesores = Profesor::orderBy('codigo')
            ->get()
            ->map(fn($p) => [
                'codigo'   => $p->codigo,
                'nombre'   => $p->nombre,
                'apellido' => $p->apellido,
                'titulo'   => $p->titulo,
                'foto'     => $p->foto,
                'ci'       => $p->ci,
            ]);

        return Inertia::render('Profesores/Index', compact('profesores'));
    }

    public function create()
    {
        return Inertia::render('Profesores/Form', [
            'mode'     => 'create',
            'profesor' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'titulo'   => 'required|string|max:255',
            'ci'       => 'required|integer|unique:profesor,ci',
            'foto'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('profesores', 'public');
            $data['foto'] = $path;
        }

        Profesor::create($data);

        return redirect()->route('profesores.index')
                         ->with('success','Profesor creado');
    }

    public function edit(Profesor $profesor)
    {
        return Inertia::render('Profesores/Form', [
        'mode'     => 'edit',
        'profesor' => $profesor,
        ]);
    }


    public function update(Request $request, Profesor $profesor)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'apellido' => 'required|string|max:255',
            'titulo'   => 'required|string|max:255',
            'ci'       => "required|integer|unique:profesor,ci,{$profesor->codigo},codigo",
            'foto'     => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // borra la anterior si existe
            if ($profesor->foto) {
                Storage::disk('public')->delete($profesor->foto);
            }
            $data['foto'] = $request->file('foto')->store('profesores','public');
        }

        $profesor->update($data);

        return redirect()->route('profesores.index')
                         ->with('success','Profesor actualizado');
    }

    public function destroy(Profesor $profesor)
    {
        if ($profesor->foto) {
            Storage::disk('public')->delete($profesor->foto);
        }
        $profesor->delete();

        return redirect()->route('profesores.index')
                         ->with('success','Profesor eliminado');
    }
}
