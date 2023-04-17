<?php

namespace core\html;

class label implements \core\html\buildable {
    private $for;
    private $text;

    public function __construct(string $text, string $for) {
        $this->for = $for;
        $this->text = $text;
    }
    
    public function build(): string {
        return "<label for='{$this->for}'>{$this->text}</label>";
    }
}