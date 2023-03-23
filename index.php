<?php
include_once (__DIR__ . "/config.php");

$contest_id = 0;

$contest = new mc\Contest($contest_id);
$contest->loadData();

$routes = [
    /*
    "/" => function () { global $contest; return $contest->name(); },
    "participants" => function () {
        global $contest;
        return \mc\render\Common::participants($contest->participants());
    },
    "participant" => function (array $params) {
        global $contest;
        $participant_name = $params[0] ?? "";
        return \mc\render\Common::participant($contest->participants()[$participant_name]);
    },
    "tasks" => function () {
        global $contest;
        return \mc\render\Common::tasks($contest->tasks());
    }
    */
    "/" => function () { header("location:/?q=contests"); exit(); },
    "contests" => function () { return \mc\render\admin\Contest::viewContests(); },
    "contest/view" => function ($params) { return \mc\render\admin\Contest::viewContest($params); },
    "contest/create" => function () { return \mc\render\admin\Contest::createContest(); },
    "tasks" => function () { return \mc\render\admin\Task::viewAll(); },
    "task/view" => function ($params) { return \mc\render\admin\Task::view($params); },
    "task/create" => function () { return \mc\render\admin\Task::create(); },
    "task/update" => function ($params) { return \mc\render\admin\Task::update($params); },
];

\mc\router::init($routes);
$result = \mc\router::run();

$page = new \mc\template(file_get_contents(config::templates_dir . "/default.tpl.php"));

echo $page->fill(["<!-- page_content -->" => $result])->value();