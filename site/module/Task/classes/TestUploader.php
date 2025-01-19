<?php

namespace Task;

use config;
use ZipArchive;
use mc\sql\database;

class TestUploader
{
    #[\mc\route("tests/upload")]
    public static function upload(array $params)
    {
        $taskId = (int)filter_input(INPUT_POST, "task-id");
        $inputPattern = filter_input(INPUT_POST, "input-pattern");
        $outputPattern = filter_input(INPUT_POST, "output-pattern");

        $tmpFile = $_FILES['tests']['tmp_name'];
        $outDir = config::tasks_dir . "/{$taskId}/tests/";

        if (!file_exists($outDir)) {
            mkdir($outDir, 0777, true);
        }

        $db = new database(config::dsn);

        $tests = [];
        $zip = new ZipArchive;
        $zip->open($tmpFile, ZipArchive::RDONLY);

        for ($i = 0; $i < $zip->count(); ++$i) {
            $in = $zip->getNameIndex($i);
            $label = self::getLabel($in, $inputPattern);

            if ($label === false) {
                continue;
            }
            $out = str_replace('*', $label, $outputPattern);
            if ($zip->locateName($out) === false) {
                continue;
            }
            $tests[$label] = ["in" => $in, "out" => $out, "label" => $label];
        }

        $points = round(100 / count($tests), 2);

        $db->delete(\meta\task_tests::__name__, [\meta\task_tests::TASK_ID => $taskId]);

        foreach ($tests as $test) {
            $testDescription = [
                \meta\task_tests::INPUT => $test["in"],
                \meta\task_tests::OUTPUT => $test["out"],
                \meta\task_tests::LABEL => $test["label"],
                \meta\task_tests::TASK_ID => $taskId,
                \meta\task_tests::POINTS => $points,
            ];
            file_put_contents($outDir . $test["in"], $zip->getFromName($test["in"]));
            file_put_contents($outDir . $test["out"], $zip->getFromName($test["out"]));
            $db->insert(\meta\task_tests::__name__, $testDescription);
        }

        header("location:/?q=task/update/{$taskId}");
        return "";
    }

    private static function getLabel(string $input, string $pattern): string | false
    {
        $matches = [];
        $pattern = "/" . str_replace("*", "(.+)", $pattern) . "/";
        preg_match($pattern, $input, $matches);
        return empty($matches) ? false : $matches[1];
    }
}
