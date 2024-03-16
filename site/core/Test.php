<?php

namespace mc;

class Test
{
    private $input;
    private $output;
    private $score;

    public function __construct($test)
    {
        $this->input = $test->input;
        $this->output = $test->output;
        $this->score = $test->score ?? 10;
    }

    public function input()
    {
        return $this->input;
    }

    public function output()
    {
        return $this->output;
    }

    public function score()
    {
        return $this->score;
    }
}
