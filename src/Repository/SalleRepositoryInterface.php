<?php

namespace App\Repository;

use App\Model\Salle;

interface SalleRepositoryInterface
{
    public function findAll(): array;

    public function findById(int $id): ?Salle;

    public function save(Salle $salle): Salle;
}
