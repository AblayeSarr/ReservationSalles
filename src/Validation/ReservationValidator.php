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
        $this->salleIdValidator = v::stringType()
            ->notEmpty()
            ->digit()
            ->positive();

        $this->responsableValidator = v::stringType()
            ->notEmpty()
            ->length(2, 100);

        $this->emailValidator = v::email();

        $this->motifValidator = v::stringType()
            ->length(5, 255);

        $this->dateDebutValidator = v::stringType()
            ->notEmpty();

        $this->dateFinValidator = v::stringType()
            ->notEmpty();
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

        if (!isset($errors['salle_id'])) {
            $acceptedData['salle_id'] = (int) $acceptedData['salle_id'];
        }

        return new ValidationResult(
            $errors === [],
            $errors,
            $acceptedData
        );
    }
}