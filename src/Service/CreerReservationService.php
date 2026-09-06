<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private ReservationRepositoryInterface $reservationRepository
    ) {
    }

    public function creer(CreerReservationDTO $dto): Reservation
    {
        // 1. Vérifier que la salle existe
        $salle = $this->salleRepository->findById(
            $dto->getSalleId()
        );

        if ($salle === null) {
            throw new SalleIndisponibleException(
                'La salle demandée n’existe pas.'
            );
        }

        // 2. Vérifier que la salle est active
        if (!$salle->active) {
            throw new SalleIndisponibleException(
                'La salle demandée est inactive.'
            );
        }

        // 3. Vérifier que la date de début est avant la date de fin
        if ($dto->getDateDebut() >= $dto->getDateFin()) {
            throw new \InvalidArgumentException(
                'La date de début doit être avant la date de fin.'
            );
        }

        // 4. Vérifier que la durée ne dépasse pas 4 heures
        $duree = $dto->getDateDebut()->diff(
            $dto->getDateFin()
        );

        $dureeEnMinutes = ($duree->days * 24 * 60)
            + ($duree->h * 60)
            + $duree->i;

        if ($dureeEnMinutes > 240) {
            throw new \InvalidArgumentException(
                'La durée de réservation ne peut pas dépasser 4 heures.'
            );
        }

        // 5. Vérifier que la réservation commence dans le futur
        $maintenant = new \DateTimeImmutable();

        if ($dto->getDateDebut() <= $maintenant) {
            throw new \InvalidArgumentException(
                'La réservation doit commencer dans le futur.'
            );
        }

        // 6. Vérifier qu’il n’y a pas de chevauchement
        $reservationExistante = $this->reservationRepository->findOverlapping(
            $dto->getSalleId(),
            $dto->getDateDebut(),
            $dto->getDateFin()
        );

        if ($reservationExistante !== null) {
            throw new SalleIndisponibleException(
                'La salle est déjà réservée sur ce créneau.'
            );
        }

        // 7. Créer la réservation
        $reservation = new Reservation();

        $reservation->salle_id = $dto->getSalleId();
        $reservation->responsable = $dto->getResponsable();
        $reservation->email = $dto->getEmail();
        $reservation->motif = $dto->getMotif();
        $reservation->date_debut = $dto->getDateDebut();
        $reservation->date_fin = $dto->getDateFin();
        $reservation->statut = 'confirmée';

        // 8. Enregistrer la réservation
        return $this->reservationRepository->save($reservation);
    }
}