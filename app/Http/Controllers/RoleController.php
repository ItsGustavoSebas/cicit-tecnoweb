<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Funcionalidad;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('codigo')->get();
        return Inertia::render('Roles/Index', compact('roles'));
    }

    public function create()
    {
        return Inertia::render('Roles/Form', [
            'mode' => 'create',
            'role' => null,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        Role::create($data);
        return redirect()->route('roles.index')
                         ->with('success', 'Rol creado exitosamente');
    }

    public function edit(Role $role)
    {
        return Inertia::render('Roles/Form', [
            'mode' => 'edit',
            'role' => $role,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $data = $request->validate([
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'nullable|string',
        ]);
        $role->update($data);
        return redirect()->route('roles.index')
                         ->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')
                         ->with('success', 'Rol eliminado');
    }

    // Mostrar página para asignar funcionalidades
    public function editFunctionalities(Role $role)
    {
        $role->load('permisos.recursos', 'permisos.funcionalidad');

        $permisos = $role->permisos->keyBy('funcionalidad_id');

        $funcionalidades = \App\Models\Funcionalidad::with('permisos')->get()->map(function ($f) use ($permisos) {
            $permiso = $permisos[$f->codigo] ?? null;
            return [
                'codigo' => $f->codigo,
                'nombre' => $f->nombre,
                'ruta' => $f->ruta,
                'asignado' => $permiso ? true : false,
                'permiso_id' => $permiso?->codigo,
                'recursos_asignados' => $permiso
                    ? $permiso->recursos->pluck('codigo')->toArray()
                    : [],
            ];
        });

        $recursos = \App\Models\Recurso::all(['codigo', 'descripcion']);

        return Inertia::render('Roles/Functionalities', [
            'role' => $role,
            'funcionalidades' => $funcionalidades,
            'recursos' => $recursos,
        ]);
    }


    // Guardar asignaciones
    public function updateFunctionalities(Request $request, Role $role)
{
    // 1. Valida entrada
    $data = $request->validate([
        'funcionalidad_id' => 'required|integer|exists:funcionalidad,codigo',
        'recursos'         => 'array',
        'recursos.*'       => 'integer|exists:recurso,codigo',
    ]);

    // 2. Encuentra o crea el Permiso para esa funcionalidad
    $permiso = $role->permisos()
        ->firstWhere('funcionalidad_id', $data['funcionalidad_id'])
        ?? $role->permisos()->create([
               'funcionalidad_id' => $data['funcionalidad_id'],
               'activo'           => true,
           ]);

    // 3. Prepara el array para sync(): [ recurso_id => ['activo'=>true], ... ]
    $sync = [];
    foreach ($data['recursos'] ?? [] as $recId) {
        $sync[$recId] = ['activo' => true];
    }

    // 4. Hace sync (esto crea nuevas relaciones, elimina las quitadas)
    $permiso->recursos()->sync($sync);

    // 5. Redirige de vuelta, con mensaje
    return redirect()->route('roles.index')
                     ->with('success', 'Permisos actualizados');
}

}
