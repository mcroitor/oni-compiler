<?php

namespace Task;

class Test
{
    private string $input;
    private string $output;
    private int $score;

    public function __construct($test)
    {
        $this->input = $test->input;
        $this->output = $test->output;
        $this->score = $test->score ?? 10;
    }

    public function input(): string
    {
        return $this->input;
    }

    public function output(): string
    {
        return $this->output;
    }

    public function score(): int
    {
        return $this->score;
    }
}
