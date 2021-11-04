<?php

namespace ToDoApp\Domain\Exception;

class NotValidException extends \Exception
{
    use ExceptionHelperTrait;

    public function __construct(string $className, string $reason = '')
    {
        parent::__construct($this->fromClassNameToWords($className).' is not valid. '.$reason);
    }
}
