<?php

namespace App\Http\Middleware;

use App\Http\Resources\FailureResource;
use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Symfony\Component\HttpFoundation\Response;

final class BearerAuth
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

        // TODO: реализовать авторизацию по токену
        // найти юзера по токену, а дальше?
        if (!in_array($request->email, $abilities) || !in_array('admin', $abilities)) {
            $errors = ['Недостаточно прав'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        // TODO: заглушка для тестов
        if (($request->email === 'admin@super.com' || $request->book == '2' || $request->chat == '2')
            && !in_array('super', $abilities)) {
            $errors = ['Недостаточно прав'];
            return (new FailureResource($errors))->response()->setStatusCode(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}
