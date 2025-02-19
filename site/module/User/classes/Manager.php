<?php

namespace User;

use config;
use \mc\template;
use \mc\route;
use \User\Role;

class Manager
{

    public const dir = config::module_dir . "/User/";
    public const templates_dir = self::dir . "/templates/";

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
                \meta\users::ROLE_ID => Role::GUEST
            ];
        }

        // main menu
        if (Manager::isLogged()) {
            config::addMainMenu([
                "Users" => "/?q=user",
            ]);
        }
    }

    public static function get($userId)
    {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\users::__name__);
        return $crud->select($userId);
    }

    #[route('user')]
    public static function actions(array $params) {
        $html = "<ul class='vertical-menu'>";
        $links = [
            "list users" => "/?q=user/list",
            "add a user" => "/?q=user/add",
            "import users" => "/?q=user/import",
        ];

        foreach ($links as $name => $link) {
            $html .= "<li><a href='{$link}' class='button w-200px'>{$name}</a></li>";
        }
        $html .= "</ul>";

        return $html;
    }

    #[route('user/list')]
    public static function list(): string
    {
        $db = new \mc\sql\database(config::dsn);
        $crud = new \mc\sql\crud($db, \meta\users::__name__);
        $users = $crud->all();

        $list = "";

        foreach ($users as $user) {
            $list .= template::load(
                self::templates_dir . "userlist.element.tpl.php",
                template::comment_modifiers
            )->fill([
                "lastname" => $user[\meta\users::LASTNAME],
                "firstname" => $user[\meta\users::FIRSTNAME],
                "institution" => $user[\meta\users::INSTITUTION],
                "email" => $user[\meta\users::EMAIL],
                "role" => Role::getRoleName($user[\meta\users::ROLE_ID]),
            ])->value();
        }
        return template::load(
            self::templates_dir . "userlist.tpl.php",
            template::comment_modifiers
        )->fill([
            "userlist element" => $list
        ])->value();
    }

    #[route('user/import')]
    public static function import()
    {
        \mc\logger::stderr()->info("post data: " . json_encode($_POST));
        if (isset($_POST["MAX_FILE_SIZE"])) {
            self::registerUsers();
        }
        header("location:/?q=user/list");
        return "";
    }

    // add user
    #[route('user/add')]
    public static function add()
    {
        if (!empty($_POST["username"])) {
            $userData = [
                \meta\users::NAME => filter_input(INPUT_POST, "username"),
                \meta\users::LASTNAME => filter_input(INPUT_POST, "lastname"),
                \meta\users::FIRSTNAME => filter_input(INPUT_POST, "firstname"),
                \meta\users::INSTITUTION => filter_input(INPUT_POST, "institution"),
                \meta\users::EMAIL => filter_input(INPUT_POST, "email"),
                \meta\users::PASSWORD => '',
                \meta\users::ROLE_ID => Role::CONTESTANT
            ];
            self::registerUser($userData);
            header("location:/?q=user/list");
            return "";
        }
        return template::load(
            self::templates_dir . "useradd.tpl.php",
            template::comment_modifiers
        )->value();
    }

    private static function registerUser($userData)
    {
        $crud = new \mc\sql\crud(
            new \mc\sql\database(\config::dsn),
            \meta\users::__name__
        );
        $crud->insert($userData);
    }

    private static function registerUsers()
    {
        \mc\logger::stderr()->info("file structure: " . json_encode($_FILES['csv_file']));

        $csvLines = file($_FILES['csv_file']['tmp_name']);

        $header = array_shift($csvLines);
        foreach ($csvLines as $csvLine) {
            list($name, $lastname, $firstname, $institution, $email) = explode(";", $csvLine);
            $userData = [
                \meta\users::NAME => $name,
                \meta\users::LASTNAME => $lastname,
                \meta\users::FIRSTNAME => $firstname,
                \meta\users::INSTITUTION => $institution,
                \meta\users::EMAIL => $email,
                \meta\users::PASSWORD => '',
            ];
            self::registerUser($userData);
        }
    }

    private static function cryptPassword($password)
    {
        return crypt($password, config::salt);
    }

    #[route('user/login')]
    public static function login()
    {
        if (empty($_POST)) {
            return template::load(
                self::templates_dir . "login.tpl.php",
                template::comment_modifiers
            )->value();
        }
        $db = new \mc\sql\database(config::dsn);

        $login = filter_input(INPUT_POST, \meta\users::NAME);
        $password = filter_input(INPUT_POST, \meta\users::PASSWORD);

        $condition = [
            \meta\users::NAME => $login,
            \meta\users::PASSWORD => self::cryptPassword($password)
        ];

        $user = $db->select(\meta\users::__name__, ['*'], $condition);
        if (empty($user)) {
            return "login failed";
        }
        $_SESSION["user"] = $user[0];
        header("location:/");
        exit();
    }

    #[route('user/logout')]
    public static function logout()
    {
        session_destroy();
        header("location:/");
        return "";
    }

    public static function isLogged()
    {
        return $_SESSION["user"][\meta\users::ID] > 0;
    }
}
