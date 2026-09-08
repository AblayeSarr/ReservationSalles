<?php

namespace App\Repository;

use App\Model\Reservation;

class ReservationRepository implements ReservationRepositoryInterface
{
    public function findAll(): array
    {
        return Reservation::query()->get()->all();
    }

    public function findById(int $id): ?Reservation
    {
        return Reservation::find($id);
    }

    public function findOverlapping(
        int $salleId,
        \DateTimeInterface $dateDebut,
        \DateTimeInterface $dateFin
    ): ?Reservation {
        return Reservation::query()
            ->where('salle_id', $salleId)
            ->where('statut', 'confirmée')
            ->where('date_debut', '<', $dateFin)
            ->where('date_fin', '>', $dateDebut)
            ->first();
    }

    public function save(Reservation $reservation): Reservation
    {
        $reservation->save();

        return $reservation;
    }

    public function cancel(Reservation $reservation): Reservation
    {
        $reservation->statut = 'annulée';
        $reservation->save();

        return $reservation;
    }
}