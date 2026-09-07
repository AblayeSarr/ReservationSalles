<?php

namespace App\DTO;

class CreerSalleDTO
{
    private string $nom;
    private string $batiment;
    private int $capacite;
    private string $type;
    private bool $active;

    public static function builder(): CreerSalleDTOBuilder
    {
        return new CreerSalleDTOBuilder();
    }

    public function __construct(
        string $nom,
        string $batiment,
        int $capacite,
        string $type,
        bool $active
    ) {
        $this->nom = $nom;
        $this->batiment = $batiment;
        $this->capacite = $capacite;
        $this->type = $type;
        $this->active = $active;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getBatiment(): string
    {
        return $this->batiment;
    }

    public function getCapacite(): int
    {
        return $this->capacite;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}