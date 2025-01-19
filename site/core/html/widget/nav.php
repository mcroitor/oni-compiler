<?php

namespace core\html\widget;

class nav implements \core\html\buildable
{

    private $links;
    private $class = [];
    private $active = "";

    /**
     * create a navigation panel
     * @param array links presented as {text => url}
     * @param string additional classes
     */
    public function __construct(array $links, string $class = "")
    {
        $this->links = $links;
        $this->class = \array_unique(\array_merge(["menu"], explode(" ", $class)));
        if(!empty($this->links)){
            $this->active = \array_keys($this->links)[0];
        }
    }

    /**
     * create new menu with specified active menu link
     * @param string url of active menu link
     * @return nav panel
     */
    public function active(string $active): nav
    {
        $result = new nav($this->links, \implode(" ", $this->class));
        $result->active = $active;
        return $result;
    }

    /**
     * build html representation of navigation panel
     * @return string html view of nav
     */
    public function build(): string
    {
        $class = implode(" ", $this->class);
        $result = "<nav class='{$class}'>";
        $links = [];
        foreach ($this->links as $key => $value) {
            $links[] = \core\html\builder::a($value, $key)
                ->set_style($value === $this->active ? "button active" : "button inactive")
                ->build();
        }

        $ul = \core\html\builder::unordered_list($links);
        $result .= $ul->build();
        $result .= "</nav>";
        return $result;
    }
}
