<?php

namespace App\Repository;

use App\Model\Reservation;

interface ReservationRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Reservation;

    public function findOverlapping(
        int $salleId,
        \DateTimeInterface $dateDebut,
        \DateTimeInterface $dateFin
    ): ?Reservation;

    public function save(Reservation $reservation): Reservation;

    public function cancel(Reservation $reservation): Reservation;
}
