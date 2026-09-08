<?php

namespace Tests\Unit;

use App\Validation\SalleValidator;
use PHPUnit\Framework\TestCase;

class SalleValidatorTest extends TestCase
{
    public function test_capacite_negative(): void
    {
        $validator = new SalleValidator();

        $data = [
            'nom' => 'Salle Test',
            'batiment' => 'Bâtiment A',
            'capacite' => -5,
            'type' => 'cours',
            'active' => true,
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('capacite', $result->errors());
    }
    public function test_type_de_salle_inconnu(): void
    {
        $validator = new SalleValidator();

        $data = [
            'nom' => 'Salle Test',
            'batiment' => 'Bâtiment A',
            'capacite' => 30,
            'type' => 'inconnu',
            'active' => true,
        ];

        $result = $validator->validate($data);

        $this->assertFalse($result->isValid());
        $this->assertArrayHasKey('type', $result->errors());
    }
}
