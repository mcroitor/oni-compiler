<?php

include_once __DIR__ . "/classes/Manager.php";
include_once __DIR__ . "/classes/TestUploader.php";

\mc\router::register("task/list", "\Task\Manager::list");
\mc\router::register("task/create", "\Task\Manager::create");
\mc\router::register("task/update", "\Task\Manager::update");
\mc\router::register("task/view", "\Task\Manager::view");
\mc\router::register("task/export", "\Task\Manager::export");
\mc\router::register("task/import", "\Task\Manager::import");
\mc\router::register("task/remove", "\Task\Manager::remove");
\mc\router::register("task/tests", "\Task\Manager::tests");
\mc\router::register("tests/upload", "\Task\TestUploader::upload");
