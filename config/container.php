<?php

use App\Application;
use App\Controller\ReservationController;
use App\Controller\SalleController;
use App\Repository\ReservationRepository;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepository;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;
use App\Validation\SalleValidator;
use FastRoute\Dispatcher;
use Illuminate\Database\Capsule\Manager as Capsule;
use function DI\autowire;
use function DI\factory;
use function FastRoute\simpleDispatcher;
use App\Factory\ReservationFactory;
use App\Factory\ReservationFactoryInterface;

return [
    SalleRepositoryInterface::class => autowire(SalleRepository::class),
    ReservationRepositoryInterface::class => autowire(ReservationRepository::class),
    SalleValidator::class => autowire(SalleValidator::class),
    ReservationFactoryInterface::class => autowire(ReservationFactory::class),
    CreerReservationService::class => autowire(CreerReservationService::class),
    AnnulerReservationService::class => autowire(AnnulerReservationService::class),
    SalleController::class => autowire(SalleController::class),
    ReservationController::class => autowire(ReservationController::class),
    Application::class => factory(
        function (
            Dispatcher $dispatcher,
            \Psr\Container\ContainerInterface $container,
            Capsule $capsule
        ): Application {
            return new Application(
                $dispatcher,
                function (string $controllerClass) use ($container): object {
                    return $container->get($controllerClass);
                },
                $capsule
            );
        }
    ),

    Dispatcher::class => factory(
        function (): Dispatcher {
            return simpleDispatcher(
                require dirname(__DIR__) . '/routes/web.php'
            );
        }
    ),

    Capsule::class => factory(
        function (): Capsule {
            return require dirname(__DIR__) . '/config/database.php';
        }
    ),
];