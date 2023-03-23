<?php

namespace mc;

use mc\TaskResult;

class ParticipantResult
{
    private $taskResults = [];

    public function __construct(array $tasks)
    {
        foreach ($tasks as $task) {
            $this->taskResults[$task->name] = new TaskResult($task->name);
        }
    }

    public function taskResult($taskName) {
        return $this->taskResults[$taskName];
    } 
}
