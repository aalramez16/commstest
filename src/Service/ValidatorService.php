<?php
declare(strict_types=1);

namespace CommsTest\Service;

use CommsTest\DTO\DTOInterface;
use Symfony\Component\Validator\Validation;

class ValidatorService {
    public function validate(DTOInterface $dto): array {
        $validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
        
        $errors = $validator->validate($dto);

        $errorPayload = [];
        foreach ($errors as $violation) {
            $errorPayload[$violation->getPropertyPath()][] = $violation->getMessage;
        }
        return $errorPayload;
    }
}