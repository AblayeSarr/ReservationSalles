<?php

namespace Tests\Unit;

use App\Validation\ReservationValidator;
use PHPUnit\Framework\TestCase;

class ReservationValidatorTest extends TestCase
{
    public function test_email_invalide(): void
    {
        $validator = new ReservationValidator();

        $data = [
            'salle_id' => '2',
            'responsable' => 'Ablaye Sarr',
            'email' => 'email-invalide',
            'motif' => 'Cours de PHP',
            'date_debut' => '2030-09-10 14:00',
            'date_fin' => '2030-09-10 16:00',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('email', $result->errors());
    }
    public function test_responsable_vide(): void
    {
        $validator = new ReservationValidator();

        $data = [
            'salle_id' => '2',
            'responsable' => '',
            'email' => 'ablaye@example.com',
            'motif' => 'Cours de PHP',
            'date_debut' => '2030-09-10 14:00',
            'date_fin' => '2030-09-10 16:00',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('responsable', $result->errors());
    }
    public function test_date_invalide(): void
    {
        $validator = new ReservationValidator();

        $data = [
            'salle_id' => '2',
            'responsable' => 'Ablaye Sarr',
            'email' => 'ablaye@example.com',
            'motif' => 'Cours de PHP',
            'date_debut' => '',
            'date_fin' => '2030-09-10 16:00',
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('date_debut', $result->errors());
    }
}
