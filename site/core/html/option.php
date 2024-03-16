<?php

namespace core\html;

class option implements \core\html\buildable {

    private $text;
    private $value;
    private $selected;

    private function copy(){
        $result = new option($this->text, $this->value);
        $result->selected = $this->selected;
        return $result;
    }
    
    public function __construct(string $text, string $value = "") {
        $this->text = $text;
        $this->value = empty($value) ? $text : $value;
        $this->selected = false;
    }
    
    public function select() {
        $logger = new \core\logger();
        $result = $this->copy();
        $result->selected = true;
        $logger->info("selected option {$this->text}, result->selected = {$result->selected}");
        return $result;
    }

    public function build(): string {
        $select = $this->selected ? " selected='selected'" : "";
        return "<option value='{$this->value}'{$select}>{$this->text}</option>";
    }
}
