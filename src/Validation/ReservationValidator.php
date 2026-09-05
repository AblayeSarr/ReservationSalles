<?php

namespace App\Validation;

use Respect\Validation\Exceptions\ValidationException;
use Respect\Validation\Validator as v;

class ReservationValidator implements ValidatorInterface
{
    private $salleIdValidator;
    private $responsableValidator;
    private $emailValidator;
    private $motifValidator;
    private $dateDebutValidator;
    private $dateFinValidator;

    public function __construct()
    {
        $this->salleIdValidator = v::intType()
            ->positive();

        $this->responsableValidator = v::stringType()
            ->notEmpty()
            ->length(2, 120);

        $this->emailValidator = v::email();

        $this->motifValidator = v::stringType()
            ->notEmpty()
            ->length(5, 255);

        $this->dateDebutValidator = v::dateTime();

        $this->dateFinValidator = v::dateTime();
    }

    public function validate(array $data): ValidationResult
    {
        $errors = [];
        $acceptedData = [];

        $validators = [
            'salle_id' => $this->salleIdValidator,
            'responsable' => $this->responsableValidator,
            'email' => $this->emailValidator,
            'motif' => $this->motifValidator,
            'date_debut' => $this->dateDebutValidator,
            'date_fin' => $this->dateFinValidator,
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
