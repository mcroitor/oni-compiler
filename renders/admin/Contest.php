<?php

namespace mc\render\admin;

use config;

class Contest
{
    public static function createContest()
    {
        if (!empty($_POST)) {
            \mc\logger::stdout()->warn("redirect to registration");
            return Contest::registerContest();
        }
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/admin/contest/create.tpl.php")
        );

        return $result->value();
    }

    public static function viewContests()
    {
        $db = new \mc\sql\database(config::dsn);
        $contests = $db->select("contests");
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/admin/contest/all.tpl.php")
        );
        $contests_items = "";
        foreach ($contests as $contest) {
            $contests_items .= "<tr><td>{$contest['id']}</td>" .
                "<td><a href='/?q=contest/view/{$contest['id']}'>{$contest['name']}</a></td>" .
                "<td>{$contest['start']}</th>" .
                "<td>{$contest['end']}</td></tr>\n";
        }
        return $result->fill(["<!-- contests-items -->" => $contests_items])->value();
    }

    private static function registerContest()
    {

        $data = [
            "name" => filter_input(INPUT_POST, "contest-name", FILTER_DEFAULT),
            "start" => filter_input(INPUT_POST, "contest-start", FILTER_DEFAULT),
            "end" => filter_input(INPUT_POST, "contest-end", FILTER_DEFAULT),
        ];
        $db = new \mc\sql\database(config::dsn);
        $db->insert("contests", $data);
        header("location:/");
        exit();
    }

    public static function viewContest(array $params)
    {
        if (empty($params)) {
            header("location:/?q=contests");
            exit();
        }
        $contestId = (int)$params[0];
        if ($contestId === 0) {
            header("location:/?q=contests");
            exit();
        }
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, "contests");
        $contest = $crud->select($contestId);
        $result = new \mc\template(
            file_get_contents(
                \config::templates_dir . "/admin/contest/view.tpl.php"
            )
        );
        $data = [
            "<!-- name -->" => $contest["name"],
            "<!-- start -->" => $contest["start"],
            "<!-- end -->" => $contest["end"],
        ];

        return $result->fill($data)->value();
    }
}
