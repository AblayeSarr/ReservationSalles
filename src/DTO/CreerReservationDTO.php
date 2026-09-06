<?php

namespace App\DTO;

class CreerReservationDTO
{
    private int $salleId;
    private string $responsable;
    private string $email;
    private string $motif;
    private \DateTimeImmutable $dateDebut;
    private \DateTimeImmutable $dateFin;

    public function __construct(
    int $salleId,
    string $responsable,
    string $email,
    string $motif,
    \DateTimeImmutable $dateDebut,
    \DateTimeImmutable $dateFin
    ) {
    $this->salleId = $salleId;
    $this->responsable = $responsable;
    $this->email = $email;
    $this->motif = $motif;
    $this->dateDebut = $dateDebut;
    $this->dateFin = $dateFin;
    }

    public function getSalleId(): int
    {
        return $this->salleId;
    }

    public function getResponsable(): string
    {
        return $this->responsable;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getMotif(): string
    {
        return $this->motif;
    }

    public function getDateDebut(): \DateTimeImmutable
    {
        return $this->dateDebut;
    }

    public function getDateFin(): \DateTimeImmutable
    {
        return $this->dateFin;
    }
}