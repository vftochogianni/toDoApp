<?php

namespace ToDoApp\Domain\Exception;

class NotValidException extends \Exception
{
    public function __construct(string $className, string $reason = '')
    {
        $array = explode('\\', $className);

        parent::__construct(end($array).' is not valid.'.$reason);
    }
}
