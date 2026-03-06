<?php

namespace mc\filesystem;

class sausage {
    public static function path(string $path): string {
        $path = str_replace(".", DIRECTORY_SEPARATOR, $path);

        return \mc\filesystem\manager::normalize($path);
    }

    public static function from(string $path): string {
        $path = \mc\filesystem\manager::normalize($path);
        return str_replace(["/", "\\"], ".", $path);
    }
}