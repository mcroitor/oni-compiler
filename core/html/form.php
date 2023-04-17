<?php

namespace core\html;

class form extends \core\html\html_element {
    
    public function __construct() {
        parent::__construct("form");
    }
    
    public function set_action(string $action): form {
        $result = new form();
        return $result->set_attribute("action", $action);;
    }
}