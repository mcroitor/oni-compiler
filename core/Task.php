<?php

namespace mc;

class Task
{
    private $name;
    private $memoryLimit;
    private $timeLimit;
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

    public function name()
    {
        return $this->name;
    }

    public function tests()
    {
        return $this->tests;
    }

    public function memoryLimit()
    {
        return $this->memoryLimit;
    }

    public function timeLimit()
    {
        return $this->timeLimit;
    }
}
