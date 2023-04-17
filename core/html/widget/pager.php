<?php

namespace core\html\widget;

class pager implements \core\html\buildable {

    private $index;
    private $max_index;
    private $page;

    public function __construct(int $index, int $max_index) {
        $this->index = $index;
        $this->max_index = $max_index;
        $this->page = \filter_input(INPUT_SERVER, "PHP_SELF");
    }

    private function min(): \core\html\a {
        return new \core\html\a("{$this->page}?page=0", "<<=", "button");
    }

    private function left(): \core\html\a {
        $left_value = ($this->index <= 0) ? 0 : $this->index - 1;
        return new \core\html\a("{$this->page}?page={$left_value}", "<==", "button");
    }

    private function right(): \core\html\a {
        $right_value = ($this->index === $this->max_index) ? $this->max_index : $this->index + 1;
        return new \core\html\a("{$this->page}?page={$right_value}", "==>", "button");
    }

    private function max(): \core\html\a {
        return new \core\html\a("{$this->page}?page={$this->max_index}", "=>>", "button");
    }

    private function spacer(): \core\html\text {
        return \core\html\builder::text(" ");
    }

    public function build(): string {
        return \core\html\builder::div()->set_style("six columns")
                        ->add_child($this->min())
                        ->add_child($this->spacer())
                        ->add_child($this->left())
                        ->add_child($this->spacer())
                        ->add_child($this->right())
                        ->add_child($this->spacer())
                        ->add_child($this->max())
                        ->build();
    }

}
