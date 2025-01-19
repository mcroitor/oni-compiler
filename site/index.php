<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";
config::load_modules();

\mc\router::init();

$result = \mc\router::run();

$page = \mc\template::load(
    config::templates_dir . config::DS . "default.tpl.php",
    \mc\template::comment_modifiers
);

$requestUri = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_SANITIZE_URL) ?? "";

$primary_menu = (new \core\html\widget\nav(config::getMainMenu()))
    ->active($requestUri)
    ->build();

// ugly hack for adding logout button
if (\User\Manager::isLogged()) {
    $primary_menu = str_replace(
        "</ul>",
        "<li><a href='/?q=user/logout' class='u-pull-right inactive button'>" .
            "<img src='images/logout.png' alt='Logout' title='Logout' class='icon' />" .
            "</a></li></ul>",
        $primary_menu
    );
}

$page_data = [
    "page_header" => "<h2>Contest Manager</h2>",
    "page_primary_menu" => $primary_menu,
    "page_content" => $result,
];

echo $page->fill($page_data)->value();
