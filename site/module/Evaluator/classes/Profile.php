<?php

namespace Task;

class Profile
{
    public string $name;
    public string $compiler;
    public string $extension;
    public string $compile;
    public string $interpreter;
    public string $execute;
    public string $source;
    public string $app;

    public function __construct($profile)
    {
        $this->name = $profile->name;
        $this->compiler = $profile->compiler ?? "";
        $this->extension = $profile->extension;
        $this->compile = $profile->compile ?? "";
        $this->interpreter = $profile->interpreter ?? "";
        $this->execute = $profile->execute;
        $this->source = $profile->source ?? "default.{$this->extension}";
        $this->app = $profile->app ?? "";
    }
}
