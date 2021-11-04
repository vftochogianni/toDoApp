<?php

namespace ToDoApp\Domain\Exception;

class NotFoundException extends \Exception
{
    use ExceptionHelperTrait;

    public function __construct(string $className, $value, string $criteriaKey = 'id')
    {
        parent::__construct(
            $this->fromClassNameToWords($className).' with '.$criteriaKey.' "'.$value.'" was not found.'
        );
    }
}
