<?php

namespace App\Controller;

use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

class HomeController
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleRepository->findAll();
        $reservations = $this->reservationRepository->findAll();

        $sallesActives = array_filter(
            $salles,
            static function ($salle): bool {
                return $salle->active;
            }
        );

        $totalSalles = count($sallesActives);

        $capaciteTotale = array_sum(
            array_map(
                static function ($salle): int {
                    return (int) $salle->capacite;
                },
                $sallesActives
            )
        );

        $reservationsConfirmees = array_filter(
            $reservations,
            static function ($reservation): bool {
                return $reservation->statut === 'confirmée';
            }
        );

        $reservationsAujourdhui = array_filter(
            $reservationsConfirmees,
            static function ($reservation): bool {
                return $reservation->date_debut->isToday();
            }
        );

        $stats = [
            'totalSalles' => $totalSalles,
            'capaciteTotale' => $capaciteTotale,
            'reservationsConfirmees' => count($reservationsConfirmees),
            'reservationsAujourdhui' => count($reservationsAujourdhui),
        ];

        $prochainesReservations = array_filter(
            $reservationsConfirmees,
            static function ($reservation): bool {
                return $reservation->date_debut->isFuture();
            }
        );

        usort(
            $prochainesReservations,
            static function ($a, $b): int {
                return $a->date_debut->timestamp
                    <=> $b->date_debut->timestamp;
            }
        );

        $prochainesReservations = array_slice(
            $prochainesReservations,
            0,
            5
        );

        $escape = static function (mixed $value): string {
            return htmlspecialchars(
                (string) $value,
                ENT_QUOTES,
                'UTF-8'
            );
        };

        require dirname(__DIR__, 2) . '/templates/home.php';
    }
}