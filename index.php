<?php

include_once (__DIR__ . "/config.php");
config::load_modules();

$routes = [
    "/" => function () { return ""; },
    "user/list" => function () { return \User\Manager::list(); },
    "task/list" => "\Task\Manager::list",
    "task/create" => "\Task\Manager::create",
];

\mc\router::init($routes);
$result = \mc\router::run();

$page = new \mc\template(file_get_contents(config::templates_dir . "/default.tpl.php"));

$menu = new \core\html\widget\nav([
    "Contests" => "/?q=contest/view",
    "Tasks" => "/?q=task/list",
    "Users" => "/?q=user/list",
]);

$page_data = [
    "<!-- page_header -->" => "<h2>Contest Manager</h2>",
    "<!-- page_primary_menu -->" => $menu->build(),
    "<!-- page_aside_content -->" => "",
    "<!-- page_content -->" => $result,
];

echo $page->fill($page_data)->value();