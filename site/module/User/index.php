<?php

namespace User;

include_once __DIR__ . "/classes/Capability.php";
include_once __DIR__ . "/classes/Role.php";
include_once __DIR__ . "/classes/Manager.php";

session_start();

function init()
{
    \User\Manager::init();

    $query = $_GET["q"] ?? "/";

    if (!\User\Manager::isLogged() && $query != "user/login") {
        \mc\logger::stderr()->error("User is not logged in");
        header("Location: /?q=user/login");
        exit();
    }
}
