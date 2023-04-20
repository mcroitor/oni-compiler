<?php

namespace Task;

use config;
use ZipArchive;

class TestUploader {

    public static function upload(array $params) {
        $task_id = (int)filter_input(INPUT_POST, "task-id");
        $input_pattern = filter_input(INPUT_POST, "input-pattern");
        $output_pattern = filter_input(INPUT_POST, "output-pattern");

        $file_name = basename($_FILES['tests']['name']);

        $tmp_file = $_FILES['tests']['tmp_name'];
        $zip_file = config::tmp_dir . "/{$task_id}-{$file_name}";
        $out_dir = config::tasks_dir . "/{$task_id}/tests/";
        self::copy($tmp_file, $zip_file);
        self::unpack($zip_file, $out_dir);
        $tests = self::clean($out_dir, $input_pattern, $output_pattern);
        self::register($tests);
        header("location:/?q=task/view/{$task_id}");
        return "";
    }
    
    private static function copy(string $from, string $to) {
        // TODO #: validate possibility of copy / move
        move_uploaded_file($from, $to);
    }

    private static function unpack(string $zip, string $out) {
        $za = new ZipArchive;
        $za->open($zip, ZipArchive::RDONLY);
        $za->extractTo($out);
        $za->close();
    }

    private static function clean(string $dir, string $input_pattern, string $output_pattern) {
        $input_pattern = "/" . str_replace("*", "(.+)", $input_pattern) . "/";
        $output_pattern = "/" . str_replace("*", "(.+)", $output_pattern) . "/";
        $tests = [];
        $files = scandir($dir);

        foreach ($files as $file) {
            // check filename by input pattern
            $label = self::get_label($file, $input_pattern);
            
        }
        return $tests;
    }

    private static function register(array $tests) {
        foreach ($tests as $test) {
            // register test
        }
    }

    private static function get_label(string $input, string $pattern) {
        $matches = [];
        preg_match($pattern, $input, $matches);
        return empty($matches) ? false : $matches[1];
      }
}
