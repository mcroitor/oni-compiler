<?php

namespace User;

use config;
use mc\template;

class Manager
{
    public const dir = config::module_dir . "/User/";
    public const templates_dir = Manager::dir . "/templates/";

    public static function init()
    {
        if (empty($_SESSION["user"])) {
            $_SESSION["user"] = [
                \meta\users::ID => 0,
                \meta\users::EMAIL => "",
                \meta\users::FIRSTNAME => "Guest",
                \meta\users::LASTNAME => "User",
                \meta\users::INSTITUTION => "Unknown",
                \meta\users::NAME => "guest",
                \meta\users::PASSWORD => "",
                \meta\users::ROLE_ID => 1
            ];
        }
        $user = new \meta\users($_SESSION["user"]);
    }

    public static function list()
    {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\users::__name__);
        $users = $crud->all();

        $list = "";

        foreach ($users as $user) {
            $list .= (new template(
                file_get_contents(self::templates_dir . "userlist.element.tpl.php")
            ))->fill([
                "<!-- lastname -->" => $user[\meta\users::LASTNAME],
                "<!-- firstname -->" => $user[\meta\users::FIRSTNAME],
                "<!-- institution -->" => $user[\meta\users::INSTITUTION],
                "<!-- email -->" => $user[\meta\users::EMAIL],
            ])->value();
        }
        return (new template(
            file_get_contents(self::templates_dir . "userlist.tpl.php")
        ))->fill([
            "<!-- userlist element -->" => $list
        ])->value();
    }
}
