<?php

include_once __DIR__ . "/classes/Manager.php";

\mc\router::register("contest/list", "\Contest\Manager::list");
\mc\router::register("contest/create", "\Contest\Manager::create");
\mc\router::register("contest/update", "\Contest\Manager::update");
\mc\router::register("contest/view", "\Contest\Manager::view");
\mc\router::register("contest/remove", "\Contest\Manager::remove");
\mc\router::register("contest/tasks", "\Contest\Manager::tasks");
\mc\router::register("contest/addtasks", "\Contest\Manager::addTasks");
\mc\router::register("contest/participants", "\Contest\Manager::participants");
\mc\router::register("contest/addparticipants", "\Contest\Manager::addParticipants");
