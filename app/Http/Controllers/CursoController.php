<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Profesor;        // ← si usas el modelo de profesores
use App\Models\TipoEstudiante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CursoController extends Controller
{
    public function indexPublic()
    {
        return Inertia::render('Cursos');
    }

    public function get()
    {
        return Curso::with([
                'profesor:codigo,nombre,apellido',
                'cronogramas:codigo,curso_id,dia,hora_inicio,hora_fin',
                'precios' => fn ($q) => $q->select('codigo', 'curso_id', 'precio')->orderByDesc('precio')->take(1)
            ])
            ->latest()
            ->get(['codigo','nombre','duracion','cupo','presencial','profesor_id']);
    }
    
    public function index()
    {
        $cursos = Curso::with(['profesor', 'usuario', 'cronogramas', 'precios.tipoEstudiante'])   
            ->orderByDesc('codigo')
            ->paginate(15)
            ->through(fn ($curso) => [                  
                'codigo'      => $curso->codigo,
                'nombre'      => $curso->nombre,
                'duracion'    => $curso->duracion,
                'cupo'        => $curso->cupo,
                'presencial'  => $curso->presencial,
                'profesor'   => optional($curso->profesor)->nombre ?? '—',
                'creadoPor'  => optional($curso->usuario)->nombre   ?? '—',
                'cronosTexto' => $curso->cronogramas
                ->map(fn ($c) => "{$c->dia} {$c->hora_inicio}-{$c->hora_fin}")
                ->implode(', '),

                'preciosTexto' => $curso->precios
                    ->map(fn ($p) => optional($p->tipoEstudiante)->nombre . ': ' . $p->precio)
                    ->implode(', '),
            ]);

        return Inertia::render('Cursos/index', compact('cursos'));
    }


    public function crear()
    {

        return Inertia::render('Cursos/crear', [
            'profesores'       => Profesor::select('codigo','nombre')->get(),
            'tiposEstudiante'  => TipoEstudiante::select('codigo','nombre')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|min:3|max:255',
            'duracion'       => 'required|string|min:2|max:255',
            'cupo'         => 'required|integer|min:1',
            'presencial'   => 'required|boolean',
            'profesor_id'  => 'required|exists:profesor,codigo',
    
            'cronogramas'               => 'required|array|min:1',
            'cronogramas.*.dia'         => 'required|string',
            'cronogramas.*.hora_inicio' => 'required|date_format:H:i',
            'cronogramas.*.hora_fin'    => 'required|date_format:H:i|after:cronogramas.*.hora_inicio',
    
            'precios'                       => 'required|array|min:1',
            'precios.*.precio'              => 'required|numeric|min:0',
            'precios.*.tipo_estudiante_id'  => 'required|exists:tipo_estudiante,codigo',
        ]);
    
        Curso::crearCurso($validated, Auth::id());
    
        return redirect()->route('cursos.index')
            ->with('success', 'Curso, cronograma y precios creados');
    }

    public function editar(Curso $curso)
    {
        return Inertia::render('Cursos/editar', [
            'curso'            => $curso->load('profesor'),
            'profesores'       => Profesor::select('codigo','nombre')->get(),
            'cronos' => $curso->cronogramas()
            ->selectRaw("dia,
                         to_char(hora_inicio,'HH24:MI')  as hora_inicio,
                         to_char(hora_fin,'HH24:MI')     as hora_fin")
            ->get(),
            'preciosPrevios'   => $curso->precios()
                                        ->select('precio','tipo_estudiante_id')
                                        ->get(),
            'tiposEstudiante'  => TipoEstudiante::select('codigo','nombre')->where('status',1)->get(),
        ]);
    }

    public function update(Request $request, Curso $curso)
    {
        $validated = $request->validate([
            'nombre'       => 'required|string|min:3|max:255',
            'duracion'       => 'required|string|min:2|max:255',
            'cupo'         => 'required|integer|min:1',
            'presencial'   => 'required|boolean',
            'profesor_id'  => 'required|exists:profesor,codigo',
    
            'cronogramas'               => 'required|array|min:1',
            'cronogramas.*.dia'         => 'required|string',
            'cronogramas.*.hora_inicio' => 'required|date_format:H:i',
            'cronogramas.*.hora_fin'    => 'required|date_format:H:i|after:cronogramas.*.hora_inicio',
    
            'precios'                      => 'required|array|min:1',
            'precios.*.precio'             => 'required|numeric|min:0',
            'precios.*.tipo_estudiante_id' => 'required|exists:tipo_estudiante,codigo',
        ]);
    
        $curso->actualizarCurso($validated, Auth::id());
    
        return redirect()
        ->route('cursos.index')
        ->with('success', 'Curso actualizado');
    }

    public function delete(Curso $curso)
    {
        $curso->delete();

        return Inertia::location(route('cursos.index'));
    }

    public function export()
    {
        $filename = 'cursos_' . now()->format('Ymd_His') . '.csv';

        $cursos = Curso::with([
                'profesor:codigo,nombre',
                'cronogramas:codigo,curso_id,dia,hora_inicio,hora_fin',
                'precios.tipoEstudiante:codigo,nombre',
            ])
            ->withCount('inscripciones as inscritos')
            ->get(['codigo','nombre','cupo','duracion','presencial','profesor_id']);

        // Construimos las filas
        $rows = $cursos->map(function ($c) {
            // Cronograma en una sola cadena
            $cronos = $c->cronogramas
                ->map(fn ($cr) => "{$cr->dia} {$cr->hora_inicio}-{$cr->hora_fin}")
                ->implode(', ');

            // Precios en una sola cadena
            $precios = $c->precios
                ->map(fn ($p) => optional($p->tipoEstudiante)->nombre . ': ' . $p->precio)
                ->implode(', ');

            return [
                $c->codigo,
                $c->nombre,
                $c->profesor?->nombre,
                $c->cupo,
                $c->inscritos,
                $c->duracion,
                $c->presencial ? 'Sí' : 'No',
                $cronos,
                $precios,
            ];
        });

        // --- Generar CSV ---
        $h = fopen('php://temp', 'r+');
        fputcsv($h, [
            'ID', 'Nombre', 'Profesor', 'Cupo',
            'Inscritos', 'Duración', 'Presencial',
            'Cronograma', 'Precios'
        ]);
        foreach ($rows as $row) {
            fputcsv($h, $row);
        }
        rewind($h);
        $csv = chr(0xEF).chr(0xBB).chr(0xBF).stream_get_contents($h); // BOM UTF-8
        fclose($h);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }

    public function exportInscripciones(Curso $curso)
    {
        $rows = $curso->inscripciones()
            ->with([
                'estudiante:codigo,nombre,apellido,ci',
                'estado:codigo,nombre'                    // ← traemos nombre del estado
            ])
            ->get(['codigo','estudiante_id','monto','estado_id','created_at'])
            ->map(fn($i) => [
                $i->estudiante->codigo,
                $i->estudiante->nombre,
                $i->estudiante->apellido,
                $i->estudiante->ci,
                $i->monto,
                optional($i->estado)->nombre,            // ← aquí
                $i->created_at->format('Y-m-d'),
            ]);

        $h = fopen('php://temp', 'r+');
        fputcsv($h, ['Cod Est','Nombre','Apellido','CI','Monto','Estado','Fecha']);
        foreach ($rows as $row) fputcsv($h, $row);
        rewind($h);
        $csv = chr(0xEF).chr(0xBB).chr(0xBF).stream_get_contents($h);
        fclose($h);

        $fname = 'inscripciones_curso_'.$curso->codigo.'_'.now()->format('Ymd_His').'.csv';

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$fname\"",
        ]);
    }




}
