<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')
                     ->orderBy('codigo')
                     ->get()
                     ->map(fn($u) => [
                         'codigo'    => $u->codigo,
                         'nombre'    => $u->nombre,
                         'ci'        => $u->ci,
                         'email'     => $u->email,
                         'rol_nombre'=> $u->role?->nombre,
                     ]);

        return Inertia::render('Users/Index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('nombre')->get(['codigo','nombre']);
        return Inertia::render('Users/Form', [
            'mode' => 'create',
            'user' => null,
            'roles'=> $roles,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'ci'       => 'required|string|max:50',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'rol_id'   => 'required|integer|exists:rol,codigo',
        ]);

        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')
                         ->with('success','Usuario creado');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('nombre')->get(['codigo','nombre']);
        return Inertia::render('Users/Form', [
            'mode' => 'edit',
            'user' => $user,
            'roles'=> $roles,
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'nombre'   => 'required|string|max:255',
            'ci'       => 'required|string|max:50',
            'email'    => "required|email|unique:users,email,{$user->codigo},codigo",
            'password' => 'nullable|string|min:6|confirmed',
            'rol_id'   => 'required|integer|exists:rol,codigo',
        ]);

        if ($data['password'] ?? false) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
                         ->with('success','Usuario actualizado');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')
                         ->with('success','Usuario eliminado');
    }

    public function export()
    {
        $filename = 'usuarios_' . now()->format('Ymd_His') . '.csv';

        $rows = \App\Models\User::with('role')
            ->get(['codigo','nombre','ci','email','rol_id'])
            ->map(fn($u) => [
                $u->codigo,
                $u->nombre,
                $u->ci,
                $u->email,
                optional($u->rol)->nombre,
            ]);

        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['ID', 'Nombre', 'CI', 'Email', 'Rol']);
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
