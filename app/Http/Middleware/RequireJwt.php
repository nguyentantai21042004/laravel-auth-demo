<?php

namespace App\Http\Middleware;

use App\Support\Jwt;
use Closure;
use Illuminate\Http\Request;

class RequireJwt
{
    public function handle(Request $request, Closure $next)
    {
        $auth = $request->header('Authorization');
        if (!$auth || stripos($auth, 'Bearer ') !== 0) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        $token = trim(substr($auth, 7));
        $payload = Jwt::decode($token);
        if (!$payload) {
            return response()->json(['message' => 'Invalid token'], 401);
        }
        // Attach payload for downstream usage
        $request->attributes->set('jwt', $payload);
        return $next($request);
    }
}


