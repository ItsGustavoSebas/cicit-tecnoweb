<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\EstudianteCurso;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $curso = Curso::findOrFail($validated['curso_id']);
        if ($curso->cupo < 1) {
            return response()->json(['error' => 'No hay cupos disponibles para este curso.'], 409);
        }
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
        $curso->cupo = $curso->cupo - 1;
        $curso->save();    
        return response()->json([
            'message' => 'Inscripción registrada correctamente.',
            'data' => $inscripcion,
        ]);
    }

    public function verEstudiantes($cursoId)
    {
        $curso = Curso::with(['profesor', 'cronogramas', 'precios'])
                      ->findOrFail($cursoId);

        $inscripciones = EstudianteCurso::with('estudiante', 'estado')
                                        ->where('curso_id', $cursoId)
                                        ->get();

        return Inertia::render('Cursos/listaEstudiantes', [
            'curso'         => $curso,
            'inscripciones' => $inscripciones,
        ]);
    }

    public function certificado(EstudianteCurso $inscripcion)
    {
        // solo permitimos si el estado es “aprobado / concluido”
        abort_if($inscripcion->estado_id !== 3 /*Aprobado*/,
                 403, 'El estudiante aún no concluyó el curso');

        $inscripcion->load('estudiante','curso');

        $pdf = Pdf::loadView('pdf.certificado', [
            'estudiante' => $inscripcion->estudiante,
            'curso'      => $inscripcion->curso,
            'fecha'      => now()->format('d-m-Y'),
        ])->setPaper('a4', 'landscape');

        return $pdf->stream("certificado_{$inscripcion->codigo}.pdf");
    }

    /* 2️⃣  Formulario de inscripción/pago */
    public function formulario(EstudianteCurso $inscripcion)
    {
        $inscripcion->load('estudiante','curso','estado');

        $pdf = Pdf::loadView('pdf.formulario', ['i' => $inscripcion]);

        return $pdf->download("formulario_{$inscripcion->codigo}.pdf");
    }

    public function buscarPorCI(Request $request)
    {
        $ci = $request->query('ci');

        $est = Estudiante::where('ci', $ci)->first();

        if (!$est) {
            return response()->json(['ok' => false, 'msg' => 'No se encontró ningún estudiante.'], 404);
        }

        $inscripciones = $est->inscripciones()       // relación hasMany
            ->with(['curso:codigo,nombre', 'estado:codigo,nombre'])
            ->get(['codigo','curso_id','monto','estado_id','created_at']);

        return [
            'ok'   => true,
            'est'  => [
                'nombre'   => $est->nombre,
                'apellido' => $est->apellido,
            ],
            'inscripciones' => $inscripciones->map(fn($i) => [
                'id'        => $i->codigo,
                'curso'     => $i->curso->nombre,
                'monto'     => $i->monto,
                'estado_id' => $i->estado_id,
                'estado'    => $i->estado->nombre,
                'fecha'     => $i->created_at->format('d-m-Y'),
            ]),
        ];
    }
} 
