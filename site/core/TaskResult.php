<?php

namespace mc;

class TaskResult {
    public $compilationStatus;
    public $evaluationStatus;
    public $score;
    public $taskName;

    public function __construct(string $taskName)
    {
        $this->compilationStatus = "";
        $this->evaluationStatus = "";
        $this->score = 0;
        $this->taskName = $taskName;
    }
}