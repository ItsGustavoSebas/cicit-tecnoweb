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
                'tipo_nombre' => $e->tipoEstudiante?->nombre,
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

    
    public function buscarPorCI($ci)
    {
        try {
            $estudiante = Estudiante::with('tipoEstudiante')->where('ci', $ci)->first();
    
            if (!$estudiante) {
                return response()->json(['error' => 'No encontrado'], 404);
            }
    
            return response()->json([
                'codigo' => $estudiante->codigo,
                'nombre' => $estudiante->nombre,
                'apellido' => $estudiante->apellido,
                'ci' => $estudiante->ci,
                'tipo_estudiante_id' => $estudiante->tipo_estudiante_id,
                'tipo_estudiante_nombre' => $estudiante->tipoEstudiante->nombre ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function export()
    {
        $filename = 'estudiantes_' . now()->format('Ymd_His') . '.csv';

        $rows = \App\Models\Estudiante::with('tipo')
            ->get(['codigo','nombre','apellido','ci','tipo_estudiante_id'])
            ->map(fn($e) => [
                $e->codigo,
                $e->nombre,
                $e->apellido,
                $e->ci,
                optional($e->tipo)->nombre,
            ]);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['ID', 'Nombre', 'Apellido', 'CI', 'Tipo']);
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
