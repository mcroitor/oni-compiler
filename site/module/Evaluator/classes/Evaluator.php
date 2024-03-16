<?php
/**
 * Evaluator class, compiles and runs the code
 * 
 */

namespace Evaluator;

class Evaluator
{
    private $config = [];

    /**
     * Loads configuration for each language
     * @param string $configFile
     */
    public function __construct(string $configFile)
    {
        $config = json_decode(file_get_contents($configFile), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            \mc\logger::stdout()->error('Invalid JSON in config file');
            throw new \Exception('Invalid JSON in config file');
        }
        foreach($config as $value) {
            $this->config[$value['extension']] = $value;
        }
    }

    /**
     * Compile code
     * @param string $filePath
     */
    public function compile(string $filePath, string $outputPath)
    {
        $logger = \mc\logger::stdout();
        $logger->info('Compiling ' . $filePath);

        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        if (!array_key_exists($extension, $this->config)) {
            $logger->error('Unsupported programming language');
            return false;
        }
        $config = $this->config[$extension];
        $command = $this->buildCompileLine($config, $filePath, $outputPath);
        $logger->info('Command: ' . $command);

        //finally compile the code
        exec($command, $output, $return);
        if ($return !== 0) {
            $logger->error('Compilation failed');
            return false;
        }
        $logger->info('Compilation successful');
    }

    private function buildCompileLine(array $config, string $filePath, string $outputPath)
    {
        $replaces = [
            '{$compiler}' => $config['compiler'],
            '{$source}' => $filePath,
            '{$app}' => $outputPath,
        ];
        $command = str_replace(array_keys($replaces), array_values($replaces), $config['compile']);
        return $command;
    }
}