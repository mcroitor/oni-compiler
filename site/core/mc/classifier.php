<?php

namespace mc;

/**
 * Class classifier, used to load and provide access to classifier data
 * Classifier data is stored as a JSON file in the classifier directory
 * @package mc
 */
class classifier
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
     * @return classifier
     */
    public static function load(string $classifierPath)
    {
        $data = json_decode(file_get_contents($classifierPath), true);
        return new classifier($data["name"], $data["values"]);
    }

    /**
     * returne classifier element by its key
     * @param string $id
     * @return mixed
     */
    public function get(string $id)
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
    public function all()
    {
        return $this->values;
    }

    /**
     * count elements in classifier
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * classifier name
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * returns classifier keys
     * @return array
     */
    public function keys()
    {
        return array_keys($this->values);
    }

    /**
     * Check if key exists.
     * @param string $key
     * @return bool
     */
    public function has_key(string $key): bool
    {
        return isset($this->values[$key]);
    }

    /**
     * Get key by value. If key does not exist, returns false
     * @param string $value
     * @return int|string|false
     */
    public function get_key_by_value(string $value): int|string|false
    {
        return array_search($value, $this->values);
    }
}
