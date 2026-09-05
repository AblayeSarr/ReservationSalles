<?php

namespace App\Repository;

use App\Model\Reservation;

class ReservationRepository
{
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
}
