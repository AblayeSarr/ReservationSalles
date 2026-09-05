<?php

use FastRoute\RouteCollector;

return function (RouteCollector $r): void {
    $r->addRoute('GET', '/', 'Accueil');

    $r->addRoute('GET', '/salles', 'SalleController::index');
    $r->addRoute('GET', '/salles/create', 'SalleController::create');
    $r->addRoute('POST', '/salles', 'SalleController::store');
    $r->addRoute('GET', '/salles/{id:\d+}', 'SalleController::show');
    $r->addRoute('GET', '/salles/{id:\d+}/edit', 'SalleController::edit');
    $r->addRoute('POST', '/salles/{id:\d+}/edit', 'SalleController::update');

    $r->addRoute('GET', '/reservations', 'ReservationController::index');
    $r->addRoute('GET', '/reservations/create', 'ReservationController::create');
    $r->addRoute('POST', '/reservations', 'ReservationController::store');
    $r->addRoute('GET', '/reservations/{id:\d+}', 'ReservationController::show');
    $r->addRoute('POST', '/reservations/{id:\d+}/cancel', 'ReservationController::cancel');
};
