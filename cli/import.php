<?php

// 1. import task
//  task is presented with a zip archive. this archive contains
//  _task.json_ - task structure description
//  _tests_ - set of files for problem solution testing

include __DIR__ . "/../config.php";
config::load_modules();

function help() {
    echo "usage: " . \mc\filesystem::fileName(__FILE__) . " -t <task.zip>";
}

function import(string $file) {
    \Task\Manager::importTask($file);
}

$short_opts = "t:";

$long_opts = [
    "task:"
];

$opts = getopt($short_opts, $long_opts);

if (empty($opts["t"]) && empty($opts["task"])) {
    help();
    exit();
}

$file = $opts["t"] ?? $opts["task"];

\mc\logger::stdout()->info("import task from `{$file}` archive");
import($file);
\mc\logger::stdout()->info("import done");

//  _pdf_, _docx_ or _md_ - task description
// 2. import contest
// 3. import participants