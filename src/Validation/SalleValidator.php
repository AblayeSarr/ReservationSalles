<?php

namespace App\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class SalleValidator implements ValidatorInterface
{
    private $nomValidator;
    private $batimentValidator;
    private $capaciteValidator;
    private $typeValidator;
    private $activeValidator;

    public function __construct()
    {
        $this->nomValidator = v::stringType()
            ->notEmpty()
            ->length(2, 100);

        $this->batimentValidator = v::stringType()
            ->notEmpty()
            ->length(2, 100);

        $this->capaciteValidator = v::intType()
            ->between(1, 1000);

        $this->typeValidator = v::in([
            'cours',
            'informatique',
            'laboratoire',
            'amphitheatre',
            'reunion',
        ]);

        $this->activeValidator = v::boolType();
    }

    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $acceptedData = [];

        $validators = [
            'nom' => $this->nomValidator,
            'batiment' => $this->batimentValidator,
            'capacite' => $this->capaciteValidator,
            'type' => $this->typeValidator,
            'active' => $this->activeValidator,
        ];

        foreach ($validators as $field => $validator) {
            if (!array_key_exists($field, $data)) {
                $errors[$field] = [
                    'Ce champ est obligatoire.'
                ];

                continue;
            }

            try {
                $validator->check($data[$field]);

                $acceptedData[$field] = $data[$field];
            } catch (ValidationException $exception) {
                $errors[$field] = [
                    $exception->getMessage()
                ];
            }
        }

        return new ValidationResult(
            $errors === [],
            $errors,
            $acceptedData
        );
    }
}
