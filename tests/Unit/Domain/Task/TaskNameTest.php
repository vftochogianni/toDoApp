<?php

namespace ToDo\Tests\Unit\Domain\Task;

use PHPUnit\Framework\TestCase;
use ToDoApp\Domain\Exception\LengthTooLongException;
use ToDoApp\Domain\Exception\LengthTooShortException;
use ToDoApp\Domain\Exception\NotValidException;
use ToDoApp\Domain\Task\TaskName;
use ToDoApp\Tests\Utils;

class TaskNameTest extends TestCase
{
    public function testCreateWithOnlyLetters()
    {
        $name = Utils::generateRandomString(12, false, true, true, false);

        $result = TaskName::create($name);

        self::assertEquals($name, $result->value());
        self::assertEquals($name, (string) $result);
    }

    public function testCreateWithOnlyNumbers()
    {
        $name = Utils::generateRandomString(12, false, false, false);

        $result = TaskName::create($name);

        self::assertEquals($name, $result->value());
        self::assertEquals($name, (string) $result);
    }

    public function testCreateWithLettersAndNumbers()
    {
        $name = Utils::generateRandomString();

        $result = TaskName::create($name);

        self::assertEquals($name, $result->value());
        self::assertEquals($name, (string) $result);
    }

    public function testCreateWillThrowExceptionWhenNameDoesNotContainOnlyAlphanumeric()
    {
        $this->expectException(NotValidException::class);

        TaskName::create(Utils::generateRandomString(12, true));
    }

    public function testCreateWillThrowExceptionWhenLengthTooShort()
    {
        $this->expectException(LengthTooShortException::class);

        TaskName::create(Utils::generateRandomString(TaskName::MINIMUM_LENGTH - 1));
    }

    public function testCreateWillThrowExceptionWhenLengthTooLong()
    {
        $this->expectException(LengthTooLongException::class);

        TaskName::create(Utils::generateRandomString(TaskName::MAXIMUM_LENGTH + 1));
    }
}
