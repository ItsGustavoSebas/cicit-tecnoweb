<?php

namespace App\Http\Middleware;

use App\Models\Visita;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RegistrarVisistas
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(isset(Auth::user()->codigo)){
            $route = $request->getPathInfo();
            $visita = Visita::firstOrCreate([
                'ruta' => $route,
                "users_id" => Auth::user()->codigo
            ]);
            $visita->veces++;
            $visita->save();
        } else {

        }
        return $next($request);
    }
}
