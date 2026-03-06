<?php

namespace Core;

use \Mc\Template;

class Helper {
    public static function Template(string $template, string $context = \config::templates_dir): Template {
        $template = \Mc\Filesystem\Manager::Implode([$context, "{$template}.tpl.php"]);
        if (!file_exists($template)) {
            throw new \Exception("Template not found: {$template}");
        }
        return Template::Load($template, Template::CM);
    }
}