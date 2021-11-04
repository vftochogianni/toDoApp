<?php

namespace ToDoApp\Domain\Exception;

class LengthTooLongException extends \Exception
{
    use ExceptionHelperTrait;

    public function __construct(string $className, int $maximum)
    {
        parent::__construct(sprintf(
            '%s is too long. Maximum length is %d.',
            $this->fromClassNameToWords($className),
            $maximum
        ));
    }
}
