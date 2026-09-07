<?php

namespace Tests\Integration;

use App\Model\Salle;
use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;
use App\Model\Reservation;
use App\Repository\ReservationRepository;

class SalleIntegrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        require dirname(__DIR__, 2) . '/config/database.php';
    }

    public function test_creation_d_une_salle_avec_eloquent(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Integration',
            'batiment' => 'Bâtiment Test',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true,
        ]);

        $this->assertNotNull($salle->id);
        $this->assertSame('Salle Integration', $salle->nom);

        Capsule::table('salles')
            ->where('id', $salle->id)
            ->delete();
    }
    public function test_une_salle_recupere_ses_reservations(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Relation',
            'batiment' => 'Bâtiment Test',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Ablaye Sarr',
            'email' => 'ablaye@example.com',
            'motif' => 'Test relation',
            'date_debut' => '2030-09-10 14:00:00',
            'date_fin' => '2030-09-10 16:00:00',
            'statut' => 'confirmée',
        ]);

        $salle->load('reservations');

        $this->assertCount(1, $salle->reservations);
        $this->assertSame($reservation->id, $salle->reservations->first()->id);

        $reservation->delete();
        $salle->delete();
    }
    public function test_recherche_une_reservation_en_chevauchement(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Chevauchement',
            'batiment' => 'Bâtiment Test',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Ablaye Sarr',
            'email' => 'ablaye@example.com',
            'motif' => 'Test chevauchement',
            'date_debut' => '2030-09-10 10:00:00',
            'date_fin' => '2030-09-10 12:00:00',
            'statut' => 'confirmée',
        ]);

        $repository = new ReservationRepository();

        $result = $repository->findOverlapping(
            $salle->id,
            new \DateTimeImmutable('2030-09-10 11:00:00'),
            new \DateTimeImmutable('2030-09-10 13:00:00')
        );

        $this->assertNotNull($result);
        $this->assertSame($reservation->id, $result->id);

        $reservation->delete();
        $salle->delete();
    }
    public function test_annulation_d_une_reservation(): void
    {
        $salle = Salle::create([
            'nom' => 'Salle Annulation',
            'batiment' => 'Bâtiment Test',
            'capacite' => 30,
            'type' => 'cours',
            'active' => true,
        ]);

        $reservation = Reservation::create([
            'salle_id' => $salle->id,
            'responsable' => 'Ablaye Sarr',
            'email' => 'ablaye@example.com',
            'motif' => 'Test annulation',
            'date_debut' => '2030-09-10 14:00:00',
            'date_fin' => '2030-09-10 16:00:00',
            'statut' => 'confirmée',
        ]);

        $repository = new ReservationRepository();

        $repository->cancel($reservation);

        $reservation->refresh();

        $this->assertSame('annulée', $reservation->statut);

        $reservation->delete();
        $salle->delete();
    }
}
