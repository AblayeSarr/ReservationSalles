<?php

namespace App\Factory;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;

class ReservationFactory implements ReservationFactoryInterface
{
    public function create(CreerReservationDTO $dto): Reservation
    {
        $reservation = new Reservation();

        $reservation->salle_id = $dto->getSalleId();
        $reservation->responsable = $dto->getResponsable();
        $reservation->email = $dto->getEmail();
        $reservation->motif = $dto->getMotif();
        $reservation->date_debut = $dto->getDateDebut();
        $reservation->date_fin = $dto->getDateFin();
        $reservation->statut = 'confirmée';

        return $reservation;
    }
}
