<?php

namespace core\html;

class html_element implements \core\html\buildable
{
    /**
     * 
     * @var array
     */
    private $childs = [];

    /**
     * 
     * @var string
     */
    private $tag_name;
    private $attributes = [];

    private function copy(): html_element
    {
        $result = new html_element($this->tag_name);
        $result->childs = $this->childs;
        $result->attributes = $this->attributes;
        return $result;
    }

    public function __construct(string $tag_name)
    {
        $this->tag_name = $tag_name;
    }

    /**
     * set style based on CSS class
     * @param string $class
     */
    public function set_style(string|array $class): html_element
    {
        $result = $this->copy();
        if (is_array($class)) {
            $result->attributes["class"] = implode(" ", $class);
        } else {
            $result->attributes["class"] = $class;
        }
        return $result;
    }

    public function set_attribute(string $name, string $value): html_element
    {
        $result = $this->copy();
        $result->attributes[$name] = $value;
        return $result;
    }

    public function add_child(\core\html\buildable|string $child): html_element
    {
        if (is_string($child)) {
            $ch = \core\html\builder::text($child);
        } elseif (is_a($child, "\core\html\buildable")) {
            $ch = $child;
        } else {
            throw new \Exception("invalid parameter");
        }
        $result = $this->copy();
        $result->childs[] = $ch;
        return $result;
    }

    public function set_name(string $name): html_element
    {
        return $this->set_attribute("name", $name);
    }

    public function get_tag_name(): string
    {
        return $this->tag_name;
    }

    public function build(): string
    {
        $attributes_view = "";
        foreach ($this->attributes as $key => $value) {
            $attributes_view .= " {$key}='{$value}'";
        }

        $result = "<{$this->tag_name}{$attributes_view}>";
        foreach ($this->childs as $child) {
            $result .= $child->build();
        }
        $result .= "</{$this->tag_name}>";
        return $result;
    }
}
