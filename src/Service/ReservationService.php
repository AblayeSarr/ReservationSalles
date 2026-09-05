<?php

namespace App\Service;

use App\Model\Reservation;
use App\Model\Salle;
use App\Repository\ReservationRepository;
use App\Validation\ReservationValidator;

class ReservationService
{
    public function __construct(
        private ReservationValidator $validator,
        private ReservationRepository $reservationRepository
    ) {
    }

    public function creerReservation(array $data): Reservation
    {
        // 1. Validation des données
        $resultat = $this->validator->validate($data);

        if (!$resultat->isValid()) {
            throw new \InvalidArgumentException(
                'Les données de réservation sont invalides.'
            );
        }

        // 2. Vérifier que la salle existe
        $salle = Salle::find($data['salle_id']);

        if ($salle === null) {
            throw new \RuntimeException(
                'La salle demandée n’existe pas.'
            );
        }

        // 3. Vérifier que la salle est active
        if (!$salle->active) {
            throw new \RuntimeException(
                'La salle demandée est inactive.'
            );
        }

        // 4. Convertir les dates
        $dateDebut = new \DateTime($data['date_debut']);
        $dateFin = new \DateTime($data['date_fin']);

        // 5. Vérifier que le début est avant la fin
        if ($dateDebut >= $dateFin) {
            throw new \RuntimeException(
                'La date de début doit être avant la date de fin.'
            );
        }

        // 6. Vérifier la durée maximale de 4 heures
        $duree = $dateDebut->diff($dateFin);

        $dureeEnMinutes = ($duree->days * 24 * 60)
            + ($duree->h * 60)
            + $duree->i;

        if ($dureeEnMinutes > 240) {
            throw new \RuntimeException(
                'La durée de réservation ne peut pas dépasser 4 heures.'
            );
        }

        // 7. Vérifier que la réservation commence dans le futur
        $maintenant = new \DateTime();

        if ($dateDebut <= $maintenant) {
            throw new \RuntimeException(
                'La réservation doit commencer dans le futur.'
            );
        }

        // 8. Vérifier qu'il n'y a pas de chevauchement
        $reservationExistante = $this->reservationRepository->findOverlapping(
            $data['salle_id'],
            $dateDebut,
            $dateFin
        );

        if ($reservationExistante !== null) {
            throw new \RuntimeException(
                'La salle est déjà réservée sur ce créneau.'
            );
        }

        // 9. Préparer la nouvelle réservation
        $reservation = new Reservation();

        $reservation->salle_id = $data['salle_id'];
        $reservation->responsable = $data['responsable'];
        $reservation->email = $data['email'];
        $reservation->motif = $data['motif'];
        $reservation->date_debut = $dateDebut;
        $reservation->date_fin = $dateFin;
        $reservation->statut = 'confirmée';

        // 10. Enregistrer la réservation
        return $this->reservationRepository->save($reservation);
    }
}