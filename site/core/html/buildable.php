<?php

namespace core\html;

interface buildable {
    public function build(): string;
}