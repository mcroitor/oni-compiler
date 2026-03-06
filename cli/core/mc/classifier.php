<?php

namespace Mc;

/**
 * Class classifier, used to load and provide access to classifier data
 * Classifier data is stored as a JSON file in the classifier directory
 * @package Mc
 */
class Classifier
{
    private $values = [];
    private $name = "empty";

    public function __construct(string $name, array $values = [])
    {
        $this->name = $name;
        $this->values = $values;
    }

    /**
     * Static method, fabric method, creates classifier from a JSON file.
     * JSON structure: { name: "", values: [] }
     * @param string $classifier_path
     * @return Classifier
     * @throws \Exception
     * @throws \JsonException
     */
    public static function load(string $classifierPath): Classifier
    {
        if (!file_exists($classifierPath)) {
            throw new \Exception("Classifier file not found: " . $classifierPath);
        }
        if (!is_readable($classifierPath)) {
            throw new \Exception("Classifier file not readable: " . $classifierPath);
        }
        $data = json_decode(file_get_contents($classifierPath), true);
        if(json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception("Error decoding JSON: " . json_last_error_msg());
        }
        return new Classifier($data["name"], $data["values"]);
    }

    /**
     * return classifier element by its key
     * @param string $id
     * @return mixed
     */
    public function get(string $id): mixed
    {
        if (empty($this->values[$id])) {
            return null;
        }
        return $this->values[$id];
    }

    /**
     * return all classifier elements
     * @return array
     */
    public function all(): array
    {
        return $this->values;
    }

    /**
     * count elements in classifier
     * @return int
     */
    public function count(): int
    {
        return count($this->values);
    }

    /**
     * classifier name
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * returns classifier keys
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->values);
    }

    /**
     * Check if key exists.
     * @param string $key
     * @return bool
     */
    public function hasKey(string $key): bool
    {
        return isset($this->values[$key]);
    }

    /**
     * Get key by value. If key does not exist, returns false
     * @param string $value
     * @return int if key is int
     * @return string if key is string
     * @return false if key does not exist
     */
    public function getKeyByValue(string $value): int|string|false
    {
        return array_search($value, $this->values);
    }
}
