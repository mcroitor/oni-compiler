<?php

include_once __DIR__ . "/classes/Manager.php";

\mc\router::register("task/list", "\Task\Manager::list");
\mc\router::register("task/create", "\Task\Manager::create");
\mc\router::register("task/view", "\Task\Manager::view");
\mc\router::register("task/remove", "\Task\Manager::remove");
    