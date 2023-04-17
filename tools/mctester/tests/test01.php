<?php

include __DIR__ . "/../mc/logger.php";
include __DIR__ . "/../mc/assert.php";
include __DIR__ . "/../mc/test.php";
include __DIR__ . "/../mc/testsuite.php";

\mc\TestSuite::create("testsuite 1")->add(
    \mc\Test::create("test 1")->setCallable(function () {
        \mc\Assert::equal(1, 2);
        \mc\Assert::equal(2, 2);
    })
)->add(
    \mc\Test::create("test 2")->setCallable(
        function () {
            $a = [1, 2, 3];
            \mc\Assert::hasKey($a, 2);
        }
    )
)->run();
