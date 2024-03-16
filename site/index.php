<?php

// use meta\modules;

include_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";
config::load_modules();


$routes = [
    "/" => function () {
        return "";
    },
];

\mc\router::init($routes);

$result = \mc\router::run();

$page = new \mc\template(
    file_get_contents(
        config::templates_dir . config::DS . "default.tpl.php"
    )
);

$requestUri = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_SANITIZE_URL) ?? "";

// test
config::addAsideMenu(["test" => "Test"]);

$page_data = [
    "<!-- page_header -->" => "<h2>Contest Manager</h2>",
    "<!-- page_primary_menu -->" => (new \core\html\widget\nav(config::getMainMenu()))
        ->active($requestUri)
        ->build(),
    "<!-- page_aside_content -->" => (new \core\html\widget\nav(config::getAsideMenu()))
        ->active("")
        ->build(),
    "<!-- page_content -->" => $result,
];

echo $page->fill($page_data)->value();
