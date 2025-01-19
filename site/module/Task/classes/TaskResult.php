<?php

namespace mc;

class TaskResult {
    public string $compilationStatus;
    public string $evaluationStatus;
    public int $score;
    public string $taskName;

    public function __construct(string $taskName)
    {
        $this->compilationStatus = "";
        $this->evaluationStatus = "";
        $this->score = 0;
        $this->taskName = $taskName;
    }
}