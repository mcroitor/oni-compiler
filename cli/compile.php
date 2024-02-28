<?php

// compile a file
// script gets a path to a file and compiles it, path to output and compiles it

include __DIR__ . "/../config.php";
config::load_modules();

function help() {
    echo "usage: " . \mc\filesystem::fileName(__FILE__) . " -i <file> -o <output>";
}

$short_opts = "i:o:";

$long_opts = [
    "input:",
    "output:"
];

$opts = getopt($short_opts, $long_opts);

if ((empty($opts["i"]) && empty($opts["input"])) || (empty($opts["o"]) && empty($opts["output"]))){
    help();
    exit();
}

$input = $opts["i"] ?? $opts["input"];
$output = $opts["o"] ?? $opts["output"];

$evaluator = new \Evaluator\Evaluator(config::languages_config);

$evaluator->compile($input, $output);
