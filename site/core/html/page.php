<?php

namespace core\html;

class page implements \core\html\buildable {
    private $stylesheets = [];
    private $childs = [];

    public function add_child(\core\html\buildable $child): page {
        $result = new page();
        $result->stylesheets = $this->stylesheets;
        $result->childs = $this->childs;
        $result->childs[] = $child;
        return $result;
    }

    public function stylesheet(string $stylesheet): page {
        $result = new page();
        $result->stylesheets = $this->stylesheets;
        $result->childs = $this->childs;
        $result->stylesheets[] = $stylesheet;
        return $result;
    }

    public function build(): string {
        $result = "<html><head>";
        // stylesheets
        foreach ($this->stylesheets as $stylesheet) {
            $result .= "<link rel='stylesheet' href='./theme/" . $stylesheet . ".css' />";
        }
        $result .= "</head><body>";
        // elements
        foreach ($this->childs as $child) {
            $result .= $child->build();
        }
        $result .= "</body></html>";
        return $result;
    }

}
