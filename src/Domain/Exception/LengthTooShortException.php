<?php

namespace ToDoApp\Domain\Exception;

class LengthTooShortException extends \Exception
{
    public function __construct(string $className, int $minimum)
    {
        $array = explode('\\', $className);

        parent::__construct(sprintf('%s is too short. Minimum length is %d', end($array), $minimum));
    }
}
