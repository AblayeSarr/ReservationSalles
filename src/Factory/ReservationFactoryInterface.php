<?php

namespace App\Factory;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;

interface ReservationFactoryInterface
{
    public function create(CreerReservationDTO $dto): Reservation;
}
