<?php

namespace ToDoApp\Domain\Task;

use ToDoApp\Domain\Exception\NotValidException;
use ToDoApp\Domain\ValueObject;

class TaskId extends ValueObject
{
    public const ID_CANNOT_BE_A_NEGATIVE_NUMBER = 'Id cannot be a negative number.';

    private int $id;

    private function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @throws NotValidException
     */
    public static function create(int $id): self
    {
        self::validate($id);

        return new self($id);
    }

    public function value(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    /**
     * @throws NotValidException
     */
    private static function validate(int $id)
    {
        if ($id < 1) {
            throw new NotValidException(self::class, self::ID_CANNOT_BE_A_NEGATIVE_NUMBER);
        }
    }
}
