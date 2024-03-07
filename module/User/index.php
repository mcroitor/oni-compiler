<?php

include_once __DIR__ . "/classes/Manager.php";

use \User\Manager;

Manager::init();

if(!User\Manager::isLogged() && $_GET["q"] != "user/login") {
    header("Location: /?q=user/login");
    exit();
}

\mc\router::register("user/list", function () { return \User\Manager::list(); });
\mc\router::register("user/import", "\User\Manager::import");
\mc\router::register("user/login", "\User\Manager::login");
\mc\router::register("user/add", "\User\Manager::add");
