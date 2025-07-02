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

    public function export()
    {
        $filename = 'profesores_' . now()->format('Ymd_His') . '.csv';

        $rows = \App\Models\Profesor::all(['codigo','nombre','apellido',
                                        'titulo','ci','foto'])
            ->map(fn($p) => [
                $p->codigo,
                $p->nombre,
                $p->apellido,
                $p->titulo,
                $p->ci,
                $p->foto ? url("/storage/{$p->foto}") : '',   
            ]);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['ID','Nombre','Apellido','Título','CI','Foto URL']);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        $bom = chr(0xEF).chr(0xBB).chr(0xBF);
        $csv = $bom . $csv;

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

}
