<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

class BearerAuthorization
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = PersonalAccessToken::findToken($request->bearerToken());
        $abilities = $token->abilities;

        if (!in_array($request->email, $abilities) || !in_array('admin', $abilities)) {
            return ResponseHelper::errorResponse(['Недостаточно прав']);
        }
        return $next($request);
    }
}
