<?php

namespace RaulFraile\ApiJokesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ContainsJavaValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {

        /** @var $constraint ContainsJava */

        if (stripos($value, 'java') !== false) {
            $this->context->addViolation($constraint->message, array('%string%' => $value));
        }
    }
}