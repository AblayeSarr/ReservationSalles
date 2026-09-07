<?php

namespace Tests\Unit;

use App\Model\Salle;
use App\Repository\SalleRepositoryInterface;

class FakeSalleRepository implements SalleRepositoryInterface
{
    public function findAll(): array
    {
        return [];
    }

    public function findById(int $id): ?Salle
    {
        if ($id !== 2) {
            return null;
        }

        $salle = new Salle();
        $salle->id = 2;
        $salle->active = true;

        return $salle;
    }

    public function save(Salle $salle): Salle
    {
        return $salle;
    }
}
