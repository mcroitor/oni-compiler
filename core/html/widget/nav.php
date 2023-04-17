<?php

namespace core\html\widget;

class nav implements \core\html\buildable {

    private $links;
    private $class = [];
    private $active;

    public function __construct(array $links, string $class = "") {
        $this->links = $links;
        $this->class = \array_unique(\array_merge(["menu"], explode(" ", $class)));
        $this->active = \array_keys($this->links)[0];
    }

    public function active(string $active): nav {
        $result = new nav($this->links, \implode(" ", $this->class));
        $result->active = $active;
        return $result;
    }

    public function build(): string {
        if(empty($this->active)){
            $this->active = \array_keys($this->links)[0];
        }
        $class = implode(" ", $this->class);
        $result = "<nav class='{$class}'>";
        $links = [];
        foreach ($this->links as $key => $value) {
            $links[] = \core\html\builder::a($value, $key)
                    ->set_style($key === $this->active ? "button active" : "button inactive")
                    ->build();
        }

        $ul = \core\html\builder::unordered_list($links);
        $result .= $ul->build();
//        $result .= \core\html\builder::hr()->build();
        $result .= "</nav>";
        return $result;
    }

}
