<?php

namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Factory\ReservationFactoryInterface;
use App\Model\Reservation;

class FakeReservationFactory implements ReservationFactoryInterface
{
    public function create(CreerReservationDTO $dto): Reservation
    {
        $reservation = new class extends Reservation {
            public $salle_id;
            public $responsable;
            public $email;
            public $motif;
            public $date_debut;
            public $date_fin;
            public $statut;
        };

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
