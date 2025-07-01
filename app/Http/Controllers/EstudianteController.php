<?php

namespace App\Http\Controllers;

use App\Models\Estudiante;
use Inertia\Inertia;

class EstudianteController extends Controller
{
   
    public function buscarPorCI($ci)
    {
        try {
            $estudiante = Estudiante::with('tipo_estudiante')->where('ci', $ci)->first();
    
            if (!$estudiante) {
                return response()->json(['error' => 'No encontrado'], 404);
            }
    
            return response()->json([
                'codigo' => $estudiante->codigo,
                'nombre' => $estudiante->nombre,
                'apellido' => $estudiante->apellido,
                'ci' => $estudiante->ci,
                'tipo_estudiante_id' => $estudiante->tipo_estudiante_id,
                'tipo_estudiante_nombre' => $estudiante->tipo_estudiante->nombre ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    
}
