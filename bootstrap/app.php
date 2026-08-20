<?php

use App\Http\Middleware\EnsureUserIsAdminMiddleware;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        channels: env('APP_ENV') === 'testing' ? null : __DIR__ . '/../routes/channels.php',
        health: '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->statefulApi();
        $middleware->alias([
            'abilities' => CheckAbilities::class,
            'ability' => CheckForAnyAbility::class,
            'admin' => EnsureUserIsAdminMiddleware::class,
        ]);
        // TODO: заменить на реальный адрес фронта
        $middleware->redirectGuestsTo('http://localhost:5173/login');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->wantsJson()) {
                return response()->json([
                    'errors' => [
                        [
                            'status' => (string) Response::HTTP_NOT_FOUND,
                            'title' => Response::$statusTexts[Response::HTTP_NOT_FOUND] ?? 'Error',
                            'detail' => 'Not found',
                        ],
                    ],
                ], Response::HTTP_NOT_FOUND)->header('Content-Type', 'application/vnd.api+json');
            }
        });
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('sanctum:prune-expired')->daily();
    })->create();
