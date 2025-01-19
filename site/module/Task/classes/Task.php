<?php

namespace Task;

use \Task\Test;

class Task
{
    private string $name;
    private int $memoryLimit;
    private int $timeLimit;
    private $tests = [];

    public function __construct($task)
    {
        $this->name = $task->name;
        $this->memoryLimit = $task->memoryLimit;
        $this->timeLimit = $task->timeLimit;
        foreach ($task->tests as $test) {
            $this->tests[] = new Test($test);
        }
    }

    public function name(): string
    {
        return $this->name;
    }

    public function tests(): array
    {
        return $this->tests;
    }

    public function memoryLimit(): int
    {
        return $this->memoryLimit;
    }

    public function timeLimit(): int
    {
        return $this->timeLimit;
    }
}
