<?php

include_once __DIR__ . "/classes/Manager.php";

use \User\Manager;

Manager::init();

$query = $_GET["q"] ?? "/";

\mc\logger::stderr()->info("User module loaded");
\mc\logger::stderr()->info("User is logged in: " . (Manager::isLogged() ? "yes" : "no"));
\mc\logger::stderr()->info("Query: " . $query);

if(!User\Manager::isLogged() && $query != "user/login") {
    \mc\logger::stderr()->error("User is not logged in");
    header("Location: /?q=user/login");
    exit();
}
