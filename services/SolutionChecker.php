<?php

namespace service;

use config;

include_once __DIR__ . "/../config.php";

class SolutionChecker
{

    protected static $db;
    protected static $sleep = 500; // ms

    public static function run()
    {

        self::$db = new \mc\sql\database(config::dsn);
        while (true) {
            usleep(self::$sleep);
            self::registerSolution();
            self::compileSolution();
            self::evaluateSolution();
        }
    }

    private static function registerSolution()
    {
    }

    private static function compileSolution()
    {
    }

    private static function evaluateSolution()
    {
    }
}
