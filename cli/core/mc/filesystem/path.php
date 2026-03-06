<?php

namespace mc\filesystem;

class path {

    private $path;

    public function __construct(string | array $path)
    {
        $p = $path;
        if (is_array($path)) {
            $p = \mc\filesystem\manager::implode($path);
        }
        $this->path = \mc\filesystem\manager::normalize($p);
    }

    public function filename(): string {
        return \mc\filesystem\manager::children($this->path);
    }

    public function extension(): string {
        $filename = $this->filename();
        $chunks = explode(".", $filename);
        return end($chunks);
    }

    public function parent(): string {
        return \mc\filesystem\manager::root($this->path);
    }

    public function __toString()
    {
        return $this->path;
    }

    public function exists() : bool {
        return file_exists($this->path);
    }

    public function is_file() : bool {
        return is_file($this->path);
    }

    public function is_dir() : bool {
        return is_dir($this->path);
    }

    public function is_link() : bool {
        return is_link($this->path);
    }

    public function is_readable() : bool {
        return is_readable($this->path);
    }

    public function is_writable() : bool {
        return is_writable($this->path);
    }

    public function is_executable() : bool {
        return is_executable($this->path);
    }

    public function size() : int {
        return filesize($this->path);
    }
}