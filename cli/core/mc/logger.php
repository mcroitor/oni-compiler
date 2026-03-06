<?php

namespace Mc;

/**
 * Description of log
 *
 * @author Croitor Mihail <mcroitor@gmail.com>
 */
class Logger
{

    public const INFO = 1;  // standard color
    public const PASS = 2;  // green color
    public const WARN = 4;  // yellow color
    public const ERROR = 8; // red color
    public const FAIL = 16; // red color
    public const DEBUG = self::INFO | self::PASS;

    private const LOG_TYPE = [
        self::INFO => "INFO",
        self::DEBUG => "DEBUG",
        self::PASS => "PASS",
        self::WARN => "WARN",
        self::ERROR => "ERROR",
        self::FAIL => "FAIL"
    ];

    private $logFile;
    private $pretifier = null;
    private $debug = false;

    /**
     * @param string $logFile
     */
    public function __construct(string $logFile = "php://stdout")
    {
        $this->logFile = $logFile;
    }

    /**
     * set a output pretifier function
     * @param callable $pretifier
     */
    public function setPretifier(callable $pretifier): void
    {
        $this->pretifier = $pretifier;
    }

    /**
     * enable / disable debug logging
     * @param bool $enable
     */
    public function enableDebug(bool $enable = true): void
    {
        $this->debug = $enable;
    }

    /**
     * write a message with specific log type marker
     * @param string $data
     * @param string $logType
     */
    private function write(string $data, string  $logType): void
    {
        if (isset($_SESSION["timezone"])) {
            date_default_timezone_set($_SESSION["timezone"]);
        }
        $type = self::LOG_TYPE[$logType];
        $text = date("Y-m-d H:i:s") . "\t{$type}: {$data}" . PHP_EOL;
        if ($this->pretifier) {
            $text = call_user_func($this->pretifier, $text);
        }
        file_put_contents($this->logFile, $text, FILE_APPEND);
    }

    /**
     * info message
     * @param string $data
     */
    public function info(string $data): void
    {
        $this->write($data, self::INFO);
    }

    /**
     * warn message
     * @param string $data
     */
    public function warn(string $data): void
    {
        $this->write($data, self::WARN);
    }

    /**
     * pass message
     * @param string $data
     */
    public function pass(string $data): void
    {
        $this->write($data, self::PASS);
    }

    /**
     * error message
     * @param string $data
     */
    public function error(string $data): void
    {
        $this->write($data, self::ERROR);
    }

    /**
     * fail message
     * @param string $data
     */
    public function fail(string $data): void
    {
        $this->write($data, self::FAIL);
    }

    /**
     * debug message
     * @param string $data
     * @param bool $debug
     */
    public function debug(string $data, bool $debug = false): void
    {
        if ($this->debug || $debug) {
            $this->write($data, self::DEBUG);
        }
    }

    /**
     * stdout logger builder
     * @return \Mc\Logger
     */
    public static function stdout(): Logger
    {
        return new Logger();
    }

    /**
     * stderr logger builder
     * @return \Mc\Logger
     */
    public static function stderr(): Logger
    {
        return new Logger("php://stderr");
    }
}
