<?php

namespace core\html;

class text implements \core\html\buildable {

    private $value;

    public function __construct(string $value) {
        $this->value = $value;
    }

    public function build(): string {
        return $this->value;
    }

}
