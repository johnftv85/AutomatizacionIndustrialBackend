<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DeviceBasicAuth
{
    /** Autentica a los equipos con HTTP Basic (usuario/clave definidos en .env). */
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) config('services.device.username');
        $password = (string) config('services.device.password');

        if ($user === '' || $password === ''
            || ! hash_equals($user, (string) $request->getUser())
            || ! hash_equals($password, (string) $request->getPassword())) {
            return response()->json(['message' => 'Unauthenticated.'], 401, [
                'WWW-Authenticate' => 'Basic realm="sensoriq"',
            ]);
        }

        return $next($request);
    }
}
