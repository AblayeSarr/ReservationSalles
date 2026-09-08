<?php

namespace App\Service;

use App\DTO\CreerReservationDTO;
use App\Exception\SalleIndisponibleException;
use App\Factory\ReservationFactoryInterface;
use App\Model\Reservation;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;

class CreerReservationService
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private ReservationRepositoryInterface $reservationRepository,
        private ReservationFactoryInterface $reservationFactory
    ) {
    }

    public function creer(CreerReservationDTO $dto): Reservation
    {
        $salle = $this->salleRepository->findById($dto->getSalleId());

        if ($salle === null) {
            throw new SalleIndisponibleException(
                'La salle demandée n’existe pas.'
            );
        }

        if (!$salle->active) {
            throw new SalleIndisponibleException(
                'La salle demandée est inactive.'
            );
        }

        if ($dto->getDateDebut() >= $dto->getDateFin()) {
            throw new \InvalidArgumentException(
                'La date de début doit être avant la date de fin.'
            );
        }

        $duree = $dto->getDateDebut()->diff($dto->getDateFin());

        $dureeEnMinutes = ($duree->days * 24 * 60)
            + ($duree->h * 60)
            + $duree->i;

        if ($dureeEnMinutes > 240) {
            throw new \InvalidArgumentException(
                'La durée de réservation ne peut pas dépasser 4 heures.'
            );
        }

        $maintenant = new \DateTimeImmutable();

        if ($dto->getDateDebut() <= $maintenant) {
            throw new \InvalidArgumentException(
                'La réservation doit commencer dans le futur.'
            );
        }

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

        $reservation = $this->reservationFactory->create($dto);

      return $this->reservationRepository->save($reservation);
    }
}
