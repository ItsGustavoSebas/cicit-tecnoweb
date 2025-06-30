<div class="app__body__footer">
    <span class="me-4">
        &copy; {{ date("Y") .' '. config('app.name', 'Laravel') }} Todos los derechos reservados.
    </span>
    <span class="app__body__footer__visitas">
        {{ App\Models\Visita::whereRaw('ruta = ? AND users_id = ?',
                                        [ request()->getPathInfo(), Auth::user()->codigo ]
                                    )->value('veces') }}
    </span>
</div>