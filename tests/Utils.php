<?php

namespace ToDoApp\Tests;

use ToDoApp\Domain\Task\Task;
use ToDoApp\Domain\Task\TaskId;
use ToDoApp\Domain\Task\TaskName;

class Utils
{
    public static function generateRandomString(
        int $length = 12,
        bool $containSpecialCharacters = false,
        bool $containsLowercase = true,
        bool $containsUppercase = true,
        bool $containNumeric = true
    ) {
        $divider = 0;
        if ($containNumeric) {
            ++$divider;
        }
        if ($containSpecialCharacters) {
            ++$divider;
        }
        if ($containsLowercase) {
            ++$divider;
        }
        if ($containsUppercase) {
            ++$divider;
        }
        if (0 == $divider) {
            return '';
        }

        $sublength = ceil($length / $divider);

        $random = '';
        if ($containNumeric) {
            $random .= substr(str_shuffle(str_repeat($x = '0123456789', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containsLowercase) {
            $random .= substr(str_shuffle(str_repeat($x = 'abcdefghijklmnopqrstuvwxyz', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containsUppercase) {
            $random .= substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        if ($containSpecialCharacters) {
            $random .= substr(str_shuffle(str_repeat($x = '!@#$%^&*()?/\\[]{}|;:,.<>`~|\'"-_+=', ceil($sublength / strlen($x)))), 1, $sublength);
        }

        return $random;
    }

    public static function createTask(bool $completed = false): Task
    {
        $taskId = TaskId::create(1);
        $taskName = TaskName::create('a new task');
        $task = Task::create($taskId, $taskName);

        if ($completed) {
            $task->complete();
        }

        $task->resetEvents();

        return $task;
    }
}
