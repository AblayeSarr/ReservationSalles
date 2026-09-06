<?php

namespace App\Controller;

use App\DTO\CreerReservationDTO;
use App\Exception\ReservationIntrouvableException;
use App\Exception\SalleIndisponibleException;
use App\Repository\ReservationRepositoryInterface;
use App\Repository\SalleRepositoryInterface;
use App\Service\AnnulerReservationService;
use App\Service\CreerReservationService;
use App\Validation\ReservationValidator;

class ReservationController
{
    public function __construct(
        private ReservationRepositoryInterface $reservationRepository,
        private SalleRepositoryInterface $salleRepository,
        private ReservationValidator $reservationValidator,
        private CreerReservationService $creerReservationService,
        private AnnulerReservationService $annulerReservationService
    ) {
    }

    public function index(): void
    {
        $reservations = $this->reservationRepository->findAll();
        $salles = $this->salleRepository->findAll();

        $salleFilter = null;

        if (isset($_GET['salle_id']) && $_GET['salle_id'] !== '') {
            $salleId = (int) $_GET['salle_id'];

            $salleFilter = $this->salleRepository->findById($salleId);

            if ($salleFilter !== null) {
                $reservations = array_filter(
                    $reservations,
                    static function ($reservation) use ($salleId): bool {
                        return (int) $reservation->salle_id === $salleId;
                    }
                );
            }
        }

        require dirname(__DIR__, 2)
            . '/templates/reservation/index.php';
    }

    public function create(): void
    {
        $salles = array_filter(
            $this->salleRepository->findAll(),
            static function ($salle): bool {
                return $salle->active;
            }
        );

        $data = [];
        $errors = [];
        $globalError = null;

        require dirname(__DIR__, 2)
            . '/templates/reservation/form.php';
    }

    public function store(): void
    {
        $data = $_POST;

        $result = $this->reservationValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();

            $salles = array_filter(
                $this->salleRepository->findAll(),
                static function ($salle): bool {
                    return $salle->active;
                }
            );

            $globalError = null;

            require dirname(__DIR__, 2)
                . '/templates/reservation/form.php';

            return;
        }

        $data = $result->data();

        try {
            $dateDebut = new \DateTimeImmutable(
                $data['date_debut']
            );

            $dateFin = new \DateTimeImmutable(
                $data['date_fin']
            );
        } catch (\Exception $exception) {
            $errors = [
                'date_debut' => [
                    'Le format de la date est invalide.'
                ],
                'date_fin' => [
                    'Le format de la date est invalide.'
                ],
            ];

            $salles = array_filter(
                $this->salleRepository->findAll(),
                static function ($salle): bool {
                    return $salle->active;
                }
            );

            $globalError = null;

            require dirname(__DIR__, 2)
                . '/templates/reservation/form.php';

            return;
        }

        $dto = new CreerReservationDTO(
            $data['salle_id'],
            $data['responsable'],
            $data['email'],
            $data['motif'],
            $dateDebut,
            $dateFin
        );

        try {
            $reservation = $this->creerReservationService->creer($dto);

            header(
                'Location: /reservations/'
                . $reservation->id
            );

            exit;
        } catch (
            SalleIndisponibleException
            | \InvalidArgumentException $exception
        ) {
            $errors = [];
            $globalError = $exception->getMessage();

            $salles = array_filter(
                $this->salleRepository->findAll(),
                static function ($salle): bool {
                    return $salle->active;
                }
            );

            require dirname(__DIR__, 2)
                . '/templates/reservation/form.php';

            return;
        }
    }

    public function show(int $id): void
    {
        $reservation = $this->reservationRepository->findById($id);

        if ($reservation === null) {
            throw new ReservationIntrouvableException(
                'La réservation demandée est introuvable.'
            );
        }

        require dirname(__DIR__, 2)
            . '/templates/reservation/show.php';
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->annuler($id);

            header('Location: /reservations');

            exit;
        } catch (ReservationIntrouvableException $exception) {
            http_response_code(404);

            $title = 'Réservation introuvable';
            $message = $exception->getMessage();

            require dirname(__DIR__, 2)
                . '/templates/error/404.php';
        }
    }
}