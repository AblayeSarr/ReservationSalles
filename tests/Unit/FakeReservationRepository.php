<?php

namespace Tests\Unit;

use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;

class FakeReservationRepository implements ReservationRepositoryInterface
{
    public function findAll(): array
    {
        return [];
    }

    public function findById(int $id): ?Reservation
    {
        return null;
    }

    public function findOverlapping(
        int $salleId,
        \DateTimeInterface $dateDebut,
        \DateTimeInterface $dateFin
    ): ?Reservation {
        if (
            $salleId === 2
            && $dateDebut < new \DateTimeImmutable('2030-09-10 12:00:00')
            && $dateFin > new \DateTimeImmutable('2030-09-10 10:00:00')
        ) {
            return new class extends Reservation {
                public $salle_id = 2;
                public $statut = 'confirmée';
            };
        }

        return null;
    }

    public function save(Reservation $reservation): Reservation
    {
        return $reservation;
    }

    public function cancel(Reservation $reservation): Reservation
    {
        return $reservation;
    }
}
