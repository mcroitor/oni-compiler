<?php

class Compiler
{
    // common information
    private string $name;
    private array $extensions;
    // compile information
    private string $compiler;
    private array $compiler_options;
    private string $compiler_output;
    private string $compile_command;
    // execute information
    private string $interpreter;
    private array $interpreter_options;
    private string $execute_command;

    public function __construct(array|object $data)
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        }
    }

    public static function Create(string $data): Compiler
    {
        return new Compiler(json_decode($data, true));
    }

    public function GetName(): string
    {
        return $this->name;
    }

    public function CanCompile(string $extension): bool
    {
        return in_array($extension, $this->extensions);
    }

    public function CompileLine(string $source): string
    {
        $search = [
            "{source}",
            "{compiler}",
            "{compiler_options}",
            "{compiler_output}"
        ];
        $replace = [
            $source,
            $this->compiler,
            implode(" ", $this->compiler_options),
            $this->compiler_output
        ];
        return str_replace(
            $search,
            $replace,
            $this->compile_command
        );
    }

    public function ExecuteLine(string $source): string
    {
        $search = [
            "{source}",
            "{interpreter}",
            "{interpreter_options}"
        ];
        $replace = [
            $source,
            $this->interpreter,
            implode(" ", $this->interpreter_options)
        ];
        return str_replace(
            $search,
            $replace,
            $this->execute_command
        );
    }
}
