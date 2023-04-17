<?php

namespace core\html;

class input implements \core\html\buildable {

    private $attributes;

    private function copy(): input {
        $result = new input();
        $result->attributes = $this->attributes;
        return $result;
    }

    public function __construct(string $type = "text") {
        $this->attributes = ["type" => $type];
    }

    public function set_attribute(string $name, string $value): input {
        $result = $this->copy();
        $result->attributes[$name] = $value;
        return $result;
    }

    public function set_id(string $id): input {
        return $this->set_attribute("id", $id);
    }

    public function set_name(string $name): input {
        return $this->set_attribute("name", $name);
    }

    public function set_style(string $style): input {
        return $this->set_attribute("class", $style);
    }

    public function set_value(string $value): input {
        return $this->set_attribute("value", $value);
    }

    public function build(): string {
        $attributes_view = "";
        foreach ($this->attributes as $key => $value) {
            $attributes_view .= " {$key}='{$value}'";
        }

        return "<input{$attributes_view} />";
    }

}
