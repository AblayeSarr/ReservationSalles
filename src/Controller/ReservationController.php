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

        $this->render('reservation/index', [
            'reservations' => $reservations,
            'salles' => $salles,
            'salleFilter' => $salleFilter,
        ]);
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

        $this->render('reservation/form', [
            'salles' => $salles,
            'data' => $data,
            'errors' => $errors,
            'globalError' => $globalError,
        ]);
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

            $this->render('reservation/form', [
                'salles' => $salles,
                'data' => $data,
                'errors' => $errors,
                'globalError' => $globalError,
            ]);

            return;
        }

        $validatedData = $result->data();

        try {
            $dateDebut = new \DateTimeImmutable(
                $validatedData['date_debut']
            );

            $dateFin = new \DateTimeImmutable(
                $validatedData['date_fin']
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

            $this->render('reservation/form', [
                'salles' => $salles,
                'data' => $data,
                'errors' => $errors,
                'globalError' => $globalError,
            ]);

            return;
        }

        $dto = CreerReservationDTO::builder()
            ->salleId($validatedData['salle_id'])
            ->responsable($validatedData['responsable'])
            ->email($validatedData['email'])
            ->motif($validatedData['motif'])
            ->dateDebut($dateDebut)
            ->dateFin($dateFin)
            ->build();

        try {
            $reservation = $this->creerReservationService->creer($dto);

            $_SESSION['success'] = 'La réservation a été créée avec succès.';

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

            $this->render('reservation/form', [
                'salles' => $salles,
                'data' => $data,
                'errors' => $errors,
                'globalError' => $globalError,
            ]);

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

        $this->render('reservation/show', [
            'reservation' => $reservation,
        ]);
    }

    public function cancel(int $id): void
    {
        try {
            $this->annulerReservationService->annuler($id);

            $_SESSION['success'] = 'La réservation a été annulée avec succès.';

            header('Location: /reservations');

            exit;
        } catch (ReservationIntrouvableException $exception) {
            http_response_code(404);

            $title = 'Réservation introuvable';
            $message = $exception->getMessage();

            $this->render('error/404', [
                'title' => $title,
                'message' => $message,
            ]);
        }
    }

    private function render(string $view, array $data = []): void
    {
        extract($data);

        require dirname(__DIR__, 2)
            . '/templates/'
            . $view
            . '.php';
    }
}
