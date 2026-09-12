<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;
use App\Response\ResponseStrategyInterface;

class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private SalleValidator $salleValidator,
        private ResponseStrategyInterface $responseStrategy
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleRepository->findAll();

        $this->render('salle/index', [
            'salles' => $salles,
        ]);
    }

    public function show(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            $this->render('error/404');

            return;
        }

        $this->render('salle/show', [
            'salle' => $salle,
        ]);
    }

    public function create(): void
    {
        $data = [];

        $errors = [];

        $mode = 'create';

        $this->render('salle/form', [
            'data' => $data,
            'errors' => $errors,
            'mode' => $mode,
        ]);
    }

    public function store(): void
    {
        $data = $_POST;

        if (isset($data['capacite'])) {
            $data['capacite'] = (int) $data['capacite'];
        }

        $data['active'] = isset($data['active'])
            ? (bool) $data['active']
            : false;

        $result = $this->salleValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();

            $mode = 'create';

            $this->render('salle/form', [
                'data' => $data,
                'errors' => $errors,
                'mode' => $mode,
            ]);

            return;
        }

        $validatedData = $result->data();

        $dto = CreerSalleDTO::builder()
            ->nom($validatedData['nom'])
            ->batiment($validatedData['batiment'])
            ->capacite($validatedData['capacite'])
            ->type($validatedData['type'])
            ->active($validatedData['active'])
            ->build();

        $salle = new Salle();

        $salle->nom = $dto->getNom();

        $salle->batiment = $dto->getBatiment();

        $salle->capacite = $dto->getCapacite();

        $salle->type = $dto->getType();

        $salle->active = $dto->isActive();

        $salle = $this->salleRepository->save($salle);

        $_SESSION['success'] = 'La salle a été créée avec succès.';

        header('Location: /salles/' . $salle->id);

        exit;
    }

    public function edit(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            $this->render('error/404');

            return;
        }

        $data = [
            'nom' => $salle->nom,
            'batiment' => $salle->batiment,
            'capacite' => $salle->capacite,
            'type' => $salle->type,
            'active' => $salle->active,
        ];

        $errors = [];

        $mode = 'edit';

        $this->render('salle/form', [
            'data' => $data,
            'errors' => $errors,
            'mode' => $mode,
        ]);
    }

    public function update(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            $this->render('error/404');

            return;
        }

        $data = $_POST;

        if (isset($data['capacite'])) {
            $data['capacite'] = (int) $data['capacite'];
        }

        $data['active'] = isset($data['active'])
            ? (bool) $data['active']
            : false;

        $result = $this->salleValidator->validate($data);

        if (!$result->isValid()) {
            $errors = $result->errors();

            $mode = 'edit';

            $this->render('salle/form', [
                'data' => $data,
                'errors' => $errors,
                'mode' => $mode,
            ]);

            return;
        }

        $validatedData = $result->data();

        $dto = CreerSalleDTO::builder()
            ->nom($validatedData['nom'])
            ->batiment($validatedData['batiment'])
            ->capacite($validatedData['capacite'])
            ->type($validatedData['type'])
            ->active($validatedData['active'])
            ->build();

        $salle->nom = $dto->getNom();

        $salle->batiment = $dto->getBatiment();

        $salle->capacite = $dto->getCapacite();

        $salle->type = $dto->getType();

        $salle->active = $dto->isActive();

        $this->salleRepository->save($salle);

        $_SESSION['success'] = 'La salle a été modifiée avec succès.';

        header('Location: /salles/' . $salle->id);

        exit;
    }

    private function render(string $view, array $data = []): void
    {
        echo $this->responseStrategy->render($view, $data);
    }
}