<?php

include_once __DIR__ . "/classes/Manager.php";

use \User\Manager;

Manager::init();

\mc\router::register("user/list", function () { return \User\Manager::list(); });
\mc\router::register("user/import", "\User\Manager::import");
