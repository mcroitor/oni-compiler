<?php

namespace mc;

class Profile
{
    public $name;
    public $compiler;
    public $extension;
    public $compile;
    public $interpreter;
    public $execute;
    public $source;
    public $app;

    public function __construct($profile)
    {
        $this->name = $profile->name;
        $this->compiler = $profile->compiler ?? "";
        $this->extension = $profile->extension;
        $this->compile = $profile->compile ?? "";
        $this->interpreter = $profile->interpreter ?? "";
        $this->execute = $profile->execute;
        $this->source = $profile->source ?? "default." . $this->extension;
        $this->app = $profile->app ?? "";
    }
}
