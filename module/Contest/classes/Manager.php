<?php

namespace Contest;

class Manager
{
    public const dir = \config::module_dir . "/Contest/";
    public const templates_dir = self::dir . "/templates/";

    public static function list(array $params)
    {
        $from = empty($params[0]) ? 0 : (int)$params[0];
        $offset = empty($params[1]) ? 20 : (int)$params[1];

        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contests = $crud->all($from, $offset);

        $list = "";

        foreach ($contests as $contest) {
            $list .= (new \mc\template(
                file_get_contents(self::templates_dir . "contestlist.element.tpl.php")
            ))->fill([
                "<!-- name -->" => $contest[\meta\contests::NAME],
                "<!-- start -->" => $contest[\meta\contests::START],
                "<!-- end -->" => $contest[\meta\contests::END],
                "<!-- id -->" => $contest[\meta\contests::ID],
            ])->value();
        }
        return (new \mc\template(
            file_get_contents(self::templates_dir . "contestlist.tpl.php")
        ))->fill([
            "<!-- contestlist element -->" => $list
        ])->value();
    }

    public static function create(array $params)
    {
        if (isset($_POST["create-contest"])) {
            $contestId = self::insertData();
            self::createStructure($contestId);
            header("location:/?q=contest/update/{$contestId}");
            return "";
        }

        return file_get_contents(self::templates_dir . "contest.create.tpl.php");
    }

    public static function update(array $params)
    {
        if (isset($_POST["update-contest"])) {
            $contestId = filter_input(INPUT_POST, "contest-id");
            self::updateData();
            header("location:/?q=contest/update/{$contestId}");
            return "";
        }
        $contestId = empty($params[0]) ? 0 : (int)$params[0];
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contest = $crud->select($contestId);
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.update.tpl.php")
        );

        return $tpl->fill([
            "<!-- contest-id -->" => $contest[\meta\contests::ID],
            "<!-- contest-name -->" => $contest[\meta\contests::NAME],
            "<!-- contest-description -->" => $contest[\meta\contests::DESCRIPTION],
            "<!-- contest-start -->" => $contest[\meta\contests::START],
            "<!-- contest-end -->" => $contest[\meta\contests::END],
        ])->value();
    }

    public static function view(array $params)
    {
        $id = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contest = $crud->select($id);

        if (empty($contest)) {
            return "";
        }

        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.view.tpl.php")
        );
        return $tpl->fill([
            "<!-- contest-name -->" => $contest[\meta\contests::NAME],
            "<!-- contest-description -->" => $contest[\meta\contests::DESCRIPTION],
            "<!-- contest-start -->" => $contest[\meta\contests::START],
            "<!-- contest-end -->" => $contest[\meta\contests::END],
        ])->value();
    }

    public static function remove(array $params){
        $id = empty($params[0]) ? -1 : (int)$params[0];
        $db = new \mc\sql\database(\config::dsn);
        
        $db->delete(\meta\contests::__name__, [\meta\contests::ID => $id]);
        // delete relation contest <-> tasks
        // $db->delete(\meta\task_tests::__name__, [\meta\task_tests::TASK_ID => $id]);
        // delete files
        // TODO #: implement this
        header("location:/?q=contest/list");
        return "";
    }

    private static function insertData()
    {
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $data = [
            \meta\contests::NAME => filter_input(INPUT_POST, "contest-name"),
            \meta\contests::DESCRIPTION => filter_input(INPUT_POST, "contest-description"),
            \meta\contests::START => filter_input(INPUT_POST, "contest-start"),
            \meta\contests::END => filter_input(INPUT_POST, "contest-end"),
        ];
        \mc\logger::stdout()->info("contest data prepared: " . json_encode($data));
        return $crud->insert($data);
    }

    private static function createStructure($contestId)
    {
        mkdir(self::getContestPath($contestId));
    }

    public static function getContestPath($contestId)
    {
        return \config::contests_dir . "{$contestId}/";
    }

    public static function updateData() {
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $data = [
            \meta\contests::ID => filter_input(INPUT_POST, "contest-id"),
            \meta\contests::NAME => filter_input(INPUT_POST, "contest-name"),
            \meta\contests::DESCRIPTION => filter_input(INPUT_POST, "contest-description"),
            \meta\contests::START => filter_input(INPUT_POST, "contest-start"),
            \meta\contests::END => filter_input(INPUT_POST, "contest-end"),
        ];
        \mc\logger::stdout()->info("contest data prepared: " . json_encode($data));
        $crud->update($data);
        return $data[\meta\contests::ID];
    }
}
