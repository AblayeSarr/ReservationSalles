<?php

namespace Tests\Unit;

use App\DTO\CreerReservationDTO;
use App\Model\Reservation;
use App\Service\CreerReservationService;
use PHPUnit\Framework\TestCase;

class CreerReservationServiceTest extends TestCase
{
    public function test_creation_valide_d_une_reservation(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 14:00:00'),
            new \DateTimeImmutable('2030-09-10 16:00:00')
        );

        $reservation = $service->creer($dto);

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );
    }

    public function test_creation_echoue_si_la_salle_n_existe_pas(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            999,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 10:00:00'),
            new \DateTimeImmutable('2030-09-10 12:00:00')
        );

        $this->expectException(
            \App\Exception\SalleIndisponibleException::class
        );

        $service->creer($dto);
    }

    public function test_creation_echoue_si_la_salle_est_inactive(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            5,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 10:00:00'),
            new \DateTimeImmutable('2030-09-10 12:00:00')
        );

        $this->expectException(
            \App\Exception\SalleIndisponibleException::class
        );

        $service->creer($dto);
    }

    public function test_creation_echoue_si_la_date_de_fin_est_avant_la_date_de_debut(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 14:00:00'),
            new \DateTimeImmutable('2030-09-10 12:00:00')
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        $service->creer($dto);
    }

    public function test_creation_echoue_si_la_duree_depasse_4_heures(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 10:00:00'),
            new \DateTimeImmutable('2030-09-10 15:00:00')
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        $service->creer($dto);
    }

    public function test_creation_echoue_si_la_date_est_dans_le_passe(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2020-09-10 10:00:00'),
            new \DateTimeImmutable('2020-09-10 12:00:00')
        );

        $this->expectException(
            \InvalidArgumentException::class
        );

        $service->creer($dto);
    }

    public function test_creation_echoue_en_cas_de_conflit(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 11:00:00'),
            new \DateTimeImmutable('2030-09-10 13:00:00')
        );

        $this->expectException(
            \App\Exception\SalleIndisponibleException::class
        );

        $service->creer($dto);
    }

    public function test_creation_reussit_si_les_creneaux_sont_voisins(): void
    {
        $service = new CreerReservationService(
            new FakeSalleRepository(),
            new FakeReservationRepository(),
            new FakeReservationFactory()
        );

        $dto = new CreerReservationDTO(
            2,
            'Ablaye Sarr',
            'ablaye@example.com',
            'Cours de PHP',
            new \DateTimeImmutable('2030-09-10 12:00:00'),
            new \DateTimeImmutable('2030-09-10 14:00:00')
        );

        $reservation = $service->creer($dto);

        $this->assertInstanceOf(
            Reservation::class,
            $reservation
        );
    }
}