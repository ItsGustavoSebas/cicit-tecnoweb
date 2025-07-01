<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Estudiante;
use App\Models\EstudianteCurso;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FacturaController extends Controller
{
    // Lista todas las facturas
    public function index()
    {
        $facturas = Factura::with('usuario')
            ->orderByDesc('created_at')
            ->get()
            ->map(fn($f) => [
                'codigo'    => $f->codigo,
                'usuario'   => $f->usuario->nombre,
                'monto'     => $f->monto,
                'created_at'=> $f->created_at->format('Y-m-d'),
            ]);

        return Inertia::render('Facturas/Index', compact('facturas'));
    }

    // Paso 1 o 2 de creación: sin estudiante, muestra lista; con ?estudiante=ID, muestra cursos pendientes
    public function create(Request $request)
    {
        if ($request->has('estudiante')) {
            $estudiante = Estudiante::findOrFail($request->query('estudiante'));
            $items = EstudianteCurso::with('curso')
                ->where('estudiante_id', $estudiante->codigo)
                ->where('estado_id', 1)
                ->get()
                ->map(fn($ic) => [
                    'codigo' => $ic->codigo,
                    'curso'  => $ic->curso->nombre,
                    'monto'  => $ic->monto,
                ]);
            return Inertia::render('Facturas/Create', [
                'step'       => 2,
                'estudiante' => [
                    'codigo' => $estudiante->codigo,
                    'nombre' => $estudiante->nombre.' '.$estudiante->apellido,
                ],
                'items'      => $items,
            ]);
        }

        $students = Estudiante::orderBy('nombre')
            ->get(['codigo','nombre','apellido'])
            ->map(fn($e) => [
                'codigo' => $e->codigo,
                'nombre' => $e->nombre.' '.$e->apellido,
            ]);

        return Inertia::render('Facturas/Create', [
            'step'     => 1,
            'students' => $students,
        ]);
    }

    // Guarda la factura y marca los ítems como pagados
    public function store(Request $request)
    {
        $data = $request->validate([
            'estudiante_id'   => 'required|integer|exists:estudiante,codigo',
            'items'           => 'required|array',
            'items.*'         => 'integer|exists:estudiante_curso,codigo',
        ]);

        $items = EstudianteCurso::whereIn('codigo', $data['items'])
            ->where('estado_id', 1)
            ->get();

        $total = $items->sum('monto');

        $factura = Factura::create([
            'users_id' => Auth::user()->codigo,
            'monto'    => $total,
        ]);

        // Marcar cada item como pagado
        foreach ($items as $ic) {
            $ic->update([
                'estado_id'  => 2,
                'factura_id' => $factura->codigo,
            ]);
        }

        return redirect()
            ->route('facturas.show', $factura->codigo)
            ->with('success','Factura generada');
    }

    // Muestra detalles de la factura (sin editar)
    public function show(Factura $factura)
    {
        $factura->load('usuario','items.curso','items.estudiante');

        $detalle = [
            'codigo'     => $factura->codigo,
            'usuario'    => $factura->usuario->nombre,
            'monto'      => $factura->monto,
            'created_at' => $factura->created_at->format('Y-m-d H:i'),
            'items'      => $factura->items->map(fn($ic) => [
                'curso'      => $ic->curso->nombre,
                'estudiante' => $ic->estudiante->nombre.' '.$ic->estudiante->apellido,
                'monto'      => $ic->monto,
            ]),
        ];

        return Inertia::render('Facturas/Show', ['factura' => $detalle]);
    }

    public function pdf(Factura $factura)
    {
        // Carga relaciones: usuario, items.curso, items.estudiante
        $factura->load('usuario','items.curso','items.estudiante');

        // Para imprimir "pagado por" tomamos el primer item (siempre es el mismo estudiante)
        $estudiante = $factura->items->first()->estudiante;

        // Le pasamos factura completa y estudiante al view
        $pdf = Pdf::loadView('facturas.pdf', [
            'factura'    => $factura,
            'estudiante' => $estudiante,
        ]);

        // Stream inline al navegador
        return response($pdf->output(), 200)
    ->header('Content-Type', 'application/pdf')
    ->header('Content-Disposition', 'attachment; filename="factura_'.$factura->codigo.'.pdf"');


    }

}
