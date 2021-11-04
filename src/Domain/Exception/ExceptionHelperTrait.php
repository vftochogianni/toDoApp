<?php

namespace ToDoApp\Domain\Exception;

trait ExceptionHelperTrait
{
    public function fromClassNameToWords(string $className)
    {
        $array = explode('\\', $className);
        $camelCaseString = end($array);

        $pattern = '/(?<=[a-z])(?=[A-Z])/x';
        $words = preg_split($pattern, $camelCaseString);

        return ucfirst(strtolower(join(' ', $words)));
    }
}
