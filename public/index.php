<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
require_once dirname(__DIR__) . '/config/database.php';

$container = require dirname(__DIR__) . '/config/container.php';

use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;

$dispatcher = simpleDispatcher(
    require dirname(__DIR__) . '/routes/web.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);

        $message = 'La page demandée est introuvable.';

        require dirname(__DIR__) . '/templates/error/404.php';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);

        $allowedMethods = $routeInfo[1];
        $allowed = implode(', ', $allowedMethods);

        $message = 'La méthode HTTP utilisée n\'est pas autorisée pour cette ressource.';

        require dirname(__DIR__) . '/templates/error/405.php';
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if ($handler === 'Accueil') {
            echo 'Bienvenue dans ReservationSalles';
            break;
        }

        [$controllerName, $method] = explode('::', $handler, 2);

        $controllerClass = 'App\\Controller\\' . $controllerName;
        $controller = $container->get($controllerClass);

        $controller->$method(...array_values($vars));
        break;
}