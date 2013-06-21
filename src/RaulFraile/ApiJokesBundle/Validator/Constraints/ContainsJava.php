<?php

namespace RaulFraile\ApiJokesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsJava extends Constraint
{
    public $message = 'Java jokes are not allowed';
}