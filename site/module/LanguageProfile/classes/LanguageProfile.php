<?php

namespace LanguageProfile;

use config;

class LanguageProfile
{
    public const SOURCE = "{source}";
    public const COMPILER_PATH = "{compiler-path}";
    public const COMPILER_OUTPUT = "{compiler-output}";
    public const INTERPRETER_PATH = "{interpreter-path}";
    // common information
    private string $name;
    private array $extensions;
    // compile information
    private string $compiler_path;
    private string $compiler_output;
    private string $compile_command;
    // execute information
    private string $interpreter_path;
    private string $execute_command;

    public function __construct(array|object $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function Create(string $data): LanguageProfile
    {
        return new LanguageProfile(json_decode($data, true));
    }

    public function GetName(): string
    {
        return $this->name;
    }

    public function Support(string $extension): bool
    {
        return in_array($extension, $this->extensions);
    }

    public function CompileLine(): string
    {
        $source = "source." . $this->extensions[0];
        $data = [
            self::SOURCE => $source,
            self::COMPILER_PATH => $this->compiler_path,
            self::COMPILER_OUTPUT => $this->compiler_output,
        ];

        config::$logger->info("Compile Data: " . json_encode($data));
        config::$logger->info("Compile command: " . $this->compile_command);

        return (new \mc\template($this->compile_command))->fill($data)->value();
    }

    public function ExecuteLine(): string
    {
        if($this->compiler_output == "") {
            $source = "source." . $this->extensions[0];
        } else {
            $source = $this->compiler_output;
        }
        $data = [
            self::SOURCE => $source,
            self::INTERPRETER_PATH => $this->interpreter_path,
        ];
        return (new \mc\template($this->execute_command))->fill($data)->value();
    }

    public function GetExtensions(): array
    {
        return $this->extensions;
    }

    public function GetCompilerPath(): string
    {
        return $this->compiler_path;
    }

    public function GetCompilerOutput(): string
    {
        return $this->compiler_output;
    }

    public function GetCompileCommand(): string
    {
        return $this->compile_command;
    }

    public function GetInterpreterPath(): string
    {
        return $this->interpreter_path;
    }

    public function GetExecuteCommand(): string
    {
        return $this->execute_command;
    }
}
