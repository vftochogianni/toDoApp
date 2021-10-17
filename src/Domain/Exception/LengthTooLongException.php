<?php

namespace ToDoApp\Domain\Exception;

class LengthTooLongException extends \Exception
{
    public function __construct(string $className, int $maximum)
    {
        $array = explode('\\', $className);

        parent::__construct(sprintf('%s is too long. Maximum length is %d', end($array), $maximum));
    }
}
