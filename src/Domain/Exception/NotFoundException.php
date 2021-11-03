<?php

namespace ToDoApp\Domain\Exception;

class NotFoundException extends \Exception
{
    public function __construct(string $className, $value, string $criteriaKey = 'id')
    {
        $array = explode('\\', $className);

        parent::__construct(end($array) . ' with ' . $criteriaKey . ' "' . $value . '" was not found.');
    }
}
