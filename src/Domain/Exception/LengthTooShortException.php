<?php

namespace ToDoApp\Domain\Exception;

class LengthTooShortException extends \Exception
{
    use ExceptionHelperTrait;

    public function __construct(string $className, int $minimum)
    {
        parent::__construct(sprintf(
            '%s is too short. Minimum length is %d.',
            $this->fromClassNameToWords($className),
            $minimum
        ));
    }
}
