<?php

namespace core\html;

class a implements \core\html\buildable {

    private $url;
    private $text;
    private $style = [];

    public function __construct(string $url, string $text = "", string $style = "") {
        if (empty($text)) {
            $text = $url;
        }
        $this->text = $text;
        $this->url = $url;
        $this->style = explode(" ", $style);
    }

    public function set_style(string $style): \core\html\a {
        return new \core\html\a($this->url, $this->text, $style);
    }

    public function build(): string {
        $style = implode(" ", $this->style);
        return "<a href='{$this->url}' class='{$style}'>{$this->text}</a>";
    }

}
