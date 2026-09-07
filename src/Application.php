<?php

namespace App;

use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;

class Application
{
    public function __construct(
        private Dispatcher $dispatcher,
        private \Closure $controllerResolver,
        private Capsule $capsule
    ) {
    }

    public function run(): void
    {
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $uri = rawurldecode(
            parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
        );

        $routeInfo = $this->dispatcher->dispatch(
            $httpMethod,
            $uri
        );

        if ($routeInfo[0] === Dispatcher::NOT_FOUND) {
            http_response_code(404);

            $message = 'La page demandée est introuvable.';

            require dirname(__DIR__, 2)
                . '/templates/error/404.php';

            return;
        }

        if ($routeInfo[0] === Dispatcher::METHOD_NOT_ALLOWED) {
            http_response_code(405);

            $allowedMethods = $routeInfo[1];
            $allowed = implode(', ', $allowedMethods);

            header('Allow: ' . $allowed);

            $message = 'La méthode HTTP utilisée n\'est pas autorisée pour cette ressource.';

            require dirname(__DIR__, 2)
                . '/templates/error/405.php';

            return;
        }

        if ($routeInfo[0] === Dispatcher::FOUND) {
            try {
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];

                [$controllerName, $method] = explode(
                    '::',
                    $handler,
                    2
                );

                $controllerClass = 'App\\Controller\\' . $controllerName;

                $controller = ($this->controllerResolver)(
                    $controllerClass
                );

                $controller->$method(
                    ...array_values($vars)
                );
            } catch (\Throwable $exception) {
                http_response_code(500);

                $title = '500 — Erreur interne';

                $message = 'Une erreur interne est survenue. Veuillez réessayer plus tard.';

                require dirname(__DIR__)
                    . '/templates/error/500.php';
            }
        }
    }
}
