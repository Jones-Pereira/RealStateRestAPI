<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->statefulApi();

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Handler para exceções de validação
        $exceptions->render(function (ValidationException $e, $request) {
            return response()->json([
                'errors' => $e->errors(),
            ], 422);
        });

        // Handler para exceções de autenticação
        $exceptions->render(function (AuthenticationException $e, $request) {
            return response()->json([
                'message' => 'Unauthenticated',
            ], 401);
        });

        // Handler para exceções de permissão não autorizada do Spatie
        $exceptions->render(function (Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        });

        // Handler para exceções de autorização
        $exceptions->render(function (AuthorizationException $e, $request) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        });

        // Handler para exceções de recurso não encontrado
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'message' => 'Not Found',
            ], 404);
        });

        // Handler para exceções de acesso negado
        $exceptions->render(function (AccessDeniedHttpException $e, $request) {
            return response()->json([
                'message' => 'Access Denied',
            ], 403);
        });

        // Handler genérico para outras exceções
        $exceptions->render(function (Throwable $e, $request) {

            if (app()->environment('local', 'development', 'testing')) {
                return response()->json([
                    'message' => 'An error occurred',
                    'exception' => get_class($e),
                ], 500);
            }

            return response()->json([
                'message' => 'Silence is golden',
            ], 500);
        });
    })->create();
