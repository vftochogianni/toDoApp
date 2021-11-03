<?php

namespace ToDoApp\Domain\Task;

use ToDoApp\Domain\Exception\LengthTooLongException;
use ToDoApp\Domain\Exception\LengthTooShortException;
use ToDoApp\Domain\Exception\NotValidException;
use ToDoApp\Domain\ValueObject;

class TaskName extends ValueObject
{
    public const MINIMUM_LENGTH = 4;
    public const MAXIMUM_LENGTH = 50;

    public const ONLY_ALPHANUMERIC = 'Should only contain alphanumeric characters.';

    private string $name;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @throws LengthTooLongException
     * @throws LengthTooShortException
     * @throws NotValidException
     */
    public static function create(string $name): self
    {
        self::validate($name);

        return new self($name);
    }

    public function value(): string
    {
        return $this->name;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @throws LengthTooLongException
     * @throws LengthTooShortException
     * @throws NotValidException
     */
    private static function validate(string $name)
    {
        if (!ctype_alnum(str_replace(' ', '', $name))) {
            throw new NotValidException(self::class, self::ONLY_ALPHANUMERIC);
        }

        if (strlen($name) < self::MINIMUM_LENGTH) {
            throw new LengthTooShortException(self::class, self::MINIMUM_LENGTH);
        }

        if (strlen($name) > self::MAXIMUM_LENGTH) {
            throw new LengthTooLongException(self::class, self::MAXIMUM_LENGTH);
        }
    }
}
