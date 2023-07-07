<?php

namespace User;

use config;
use mc\template;

class Manager {

    public const dir = config::module_dir . "/User/";
    public const templates_dir = self::dir . "/templates/";

    public static function init() {
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
    
    public static function get($userId) {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\users::__name__);
        return $crud->select($userId);
    }

    public static function list() {
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

    #[router('user/import')]
    public static function import() {
        \mc\logger::stdout()->info("post data: " . json_encode($_POST));
        if (isset($_POST["MAX_FILE_SIZE"])) {
            self::registerUsers();
        }
        header("location:/?q=user/list");
        return "";
    }
    
    private static function registerUsers() {
        \mc\logger::stdout()->info("file structure: " . json_encode($_FILES['csv_file']));
        
        $csvLines = file($_FILES['csv_file']['tmp_name']);
        
        $header = array_shift($csvLines);
        $crud = new \mc\sql\crud(new \mc\sql\database(\config::dsn), \meta\users::__name__);
        foreach ($csvLines as $csvLine) {
            list($name, $lastname, $firstname, $institution, $email) = explode(";", $csvLine);
            $crud->insert([
                \meta\users::NAME => $name,
                \meta\users::LASTNAME => $lastname,
                \meta\users::FIRSTNAME => $firstname,
                \meta\users::INSTITUTION => $institution,
                \meta\users::EMAIL => $email,
                \meta\users::PASSWORD => '',
            ]);
        }
    }
}
