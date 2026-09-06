<?php

namespace App\Controller;

use App\DTO\CreerSalleDTO;
use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;
use App\Validation\SalleValidator;

class SalleController
{
    public function __construct(
        private SalleRepositoryInterface $salleRepository,
        private SalleValidator $salleValidator
    ) {
    }

    public function index(): void
    {
        $salles = $this->salleRepository->findAll();

        require dirname(__DIR__, 2) . '/templates/salle/index.php';
    }

    public function show(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            require dirname(__DIR__, 2) . '/templates/error/404.php';

            return;
        }

        require dirname(__DIR__, 2) . '/templates/salle/show.php';
    }

    public function create(): void
    {
        $data = [];
        $errors = [];
        $mode = 'create';

        require dirname(__DIR__, 2) . '/templates/salle/form.php';
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

            require dirname(__DIR__, 2) . '/templates/salle/form.php';

            return;
        }

        $validatedData = $result->data();

        $dto = new CreerSalleDTO(
            $validatedData['nom'],
            $validatedData['batiment'],
            $validatedData['capacite'],
            $validatedData['type'],
            $validatedData['active']
        );

        $salle = new Salle();

        $salle->nom = $dto->getNom();
        $salle->batiment = $dto->getBatiment();
        $salle->capacite = $dto->getCapacite();
        $salle->type = $dto->getType();
        $salle->active = $dto->isActive();

        $salle = $this->salleRepository->save($salle);

        header('Location: /salles/' . $salle->id);

        exit;
    }

    public function edit(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            require dirname(__DIR__, 2) . '/templates/error/404.php';

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

        require dirname(__DIR__, 2) . '/templates/salle/form.php';
    }

    public function update(int $id): void
    {
        $salle = $this->salleRepository->findById($id);

        if ($salle === null) {
            http_response_code(404);

            require dirname(__DIR__, 2) . '/templates/error/404.php';

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

            require dirname(__DIR__, 2) . '/templates/salle/form.php';

            return;
        }

        $validatedData = $result->data();

        $dto = new CreerSalleDTO(
            $validatedData['nom'],
            $validatedData['batiment'],
            $validatedData['capacite'],
            $validatedData['type'],
            $validatedData['active']
        );

        $salle->nom = $dto->getNom();
        $salle->batiment = $dto->getBatiment();
        $salle->capacite = $dto->getCapacite();
        $salle->type = $dto->getType();
        $salle->active = $dto->isActive();

        $this->salleRepository->save($salle);

        header('Location: /salles/' . $salle->id);

        exit;
    }
}