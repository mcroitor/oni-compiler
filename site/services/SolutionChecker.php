<?php

namespace service;

use config;

include_once __DIR__ . "/../config.php";

class SolutionChecker
{
    public const STATE_NEW = "new";
    public const STATE_COMPILATION = "compilation";
    public const STATE_COMPILATION_ERROR = "compilation error";
    public const STATE_COMPILED = "compiled";
    public const STATE_RUNNING = "running";
    public const STATE_RUNTIME_ERROR = "runtime error";
    public const STATE_SUCCESS = "success";

    private $solution;
    private $solution_path;
    private $solution_name;
    private $solution_lang;
    private $config;

    public function __construct(\meta\solutions $solution) {
        $this->solution = $solution;
        $this->solution_path = \mc\filesystem::root($this->solution->path);
        $this->solution_name = \mc\filesystem::fileName($this->solution->path);
        $this->solution_lang = \mc\filesystem::extension($this->solution->path);
        // get config
        $data = json_decode(file_get_contents(\config::languages_config), true);
    }
    public function check() {
        $this->compile();
        $this->run();
    }

    private function compile() {
        // change the state to `compilation`
        $this->solution->state = self::STATE_COMPILATION;
        // $this->solution->save();
        // check if the language is supported
        if (!isset($this->config[$this->solution_lang])) {
            // set the state to `compilation error`
            $this->solution->state = self::STATE_COMPILATION_ERROR;
            throw new \Exception("Language not supported");
        }
        $command = $this->config[$this->solution_lang]['compile'];
        $command = str_replace("{solution_path}", $this->solution_path, $command);
        $command = str_replace("{solution_name}", $this->solution_name, $command);
        $command = str_replace("{solution_lang}", $this->solution_lang, $command);
        $output = shell_exec($command);
        if ($output) {
            // set the state to `compilation error`
            $this->solution->state = self::STATE_COMPILATION_ERROR;
            throw new \Exception("Compilation Error: " . $output);
        }
    }

    private function run() {
        $command = $this->config[$this->solution_lang]['run'];
        $command = str_replace("{solution_path}", $this->solution_path, $command);
        $command = str_replace("{solution_name}", $this->solution_name, $command);
        $command = str_replace("{solution_lang}", $this->solution_lang, $command);
        $output = shell_exec($command);
        if ($output) {
            throw new \Exception("Runtime Error: " . $output);
        }
    }


}
