<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = Auth::user();

        if ($user) {
            // Carga permisos y funcionalidad en una sola consulta
            $user->loadMissing('role.permisos.funcionalidad');
        }

        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $user,
                'permisos' => $user?->role?->permisos ?? [], // 👈 aquí los mandas directo
            ],
            'visitas' => \App\Models\Visita::whereRaw(
                'ruta = ? AND users_id = ?',
                [$request->getPathInfo(), optional($user)->codigo]
            )->value('veces'),
        ]);
    }

}
