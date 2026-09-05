<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';

use function FastRoute\simpleDispatcher;
use FastRoute\RouteCollector;

$dispatcher = simpleDispatcher(
    require dirname(__DIR__) . '/routes/web.php'
);

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

$uri = rawurldecode(
    parse_url($uri, PHP_URL_PATH)
);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        http_response_code(404);
        echo 'Page non trouvée';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        http_response_code(405);

        $allowedMethods = $routeInfo[1];

        header(
            'Allow: ' . implode(', ', $allowedMethods)
        );

        echo 'Méthode HTTP non autorisée';
        break;

    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        if ($handler === 'Accueil') {
            echo 'Bienvenue dans ReservationSalles';
            break;
        }

        echo 'Route trouvée : ' . $handler;

        if ($vars !== []) {
            echo PHP_EOL;

            foreach ($vars as $name => $value) {
                echo $name . ' = ' . $value;
            }
        }

        break;
}