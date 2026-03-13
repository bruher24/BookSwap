<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailureResource;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

final class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = PersonalAccessToken::findToken($request->bearerToken() ?? '');
        $abilities = (array)($token->abilities ?? []);

        if (!in_array('admin', $abilities)) {
            $errors = ['Недостаточно прав'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
