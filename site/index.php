<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . "config.php";
config::load_modules();

\Mc\Router::init();

$result = \Mc\Router::run();

$page = \Core\Helper::Template("default");

$requestUri = filter_input(INPUT_SERVER, "REQUEST_URI", FILTER_SANITIZE_URL) ?? "";

$primary_menu = (new \core\html\widget\nav(config::getMainMenu()))
    ->active($requestUri)
    ->build();
$aside_menu = (new \core\html\widget\nav(config::getAsideMenu(), "vertical-menu"))
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
    "page_aside" => $aside_menu,
    "page_content" => $result,
    "main" => empty(config::$asideMenu) ? "twelve columns" : "nine columns",
    "aside" => empty(config::$asideMenu) ? "" : "three columns",
];

// config::$logger->Info("Active Route: " . \Mc\Router::getSelectedRoute());
// config::$logger->Info("All Routes: " . json_encode(\Mc\Router::getRoutes()));

echo $page->Fill($page_data)->Value();
