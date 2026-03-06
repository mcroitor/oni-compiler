<?php

namespace User;

use \Core\Helper;
use config;
use \Mc\Template;
use \Mc\Logger;
use \Mc\Route;
use \Mc\Sql\Crud;
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
                "Users" => "/?q=user/list",
            ]);
        }
    }


    public static function get($userId)
    {
        $crud = new Crud(config::$db, \meta\users::__name__);
        return $crud->Select($userId);
    }

    public static function actions(): void
    {
        config::addAsideMenu([
            "list users" => "/?q=user/list",
            "add a user" => "/?q=user/add",
        ]);
    }

    #[Route('user/list')]
    public static function list(): string
    {
        self::actions();
        $crud = new Crud(config::$db, \meta\users::__name__);
        $users = $crud->All();

        $list = "";

        foreach ($users as $user) {
            $list .= Helper::Template(
                "userlist.element",
                self::templates_dir
            )->Fill([
                "lastname" => $user[\meta\users::LASTNAME],
                "firstname" => $user[\meta\users::FIRSTNAME],
                "institution" => $user[\meta\users::INSTITUTION],
                "email" => $user[\meta\users::EMAIL],
                "role" => Role::getRoleName($user[\meta\users::ROLE_ID]),
            ])->Value();
        }
        return Helper::Template(
            "userlist",
            self::templates_dir
        )->Fill([
            "userlist-element" => $list
        ])->Value();
    }

    #[Route('user/import')]
    public static function import()
    {
        config::$logger->Info("post data: " . json_encode($_POST));
        if (isset($_POST["MAX_FILE_SIZE"])) {
            self::registerUsers();
        }
        header("location:/?q=user/list");
        return "";
    }

    // add user
    #[Route('user/add')]
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
        self::actions();
        return Helper::Template(
            "useradd",
            self::templates_dir
        )->Value();
    }

    private static function registerUser($userData)
    {
        $crud = new Crud(
            config::$db,
            \meta\users::__name__
        );
        $crud->Insert($userData);
    }

    private static function registerUsers()
    {
        Logger::StdErr()->Info("file structure: " . json_encode($_FILES['csv_file']));

        $csvLines = file($_FILES['csv_file']['tmp_name']);

        $header = array_shift($csvLines);
        foreach ($csvLines as $csvLine) {
            [$name, $lastname, $firstname, $institution, $email] = explode(";", $csvLine);
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

    #[Route('user/login')]
    public static function login()
    {
        if (empty($_POST)) {
            return Helper::Template("login", self::templates_dir)->Value();
        }

        $login = filter_input(INPUT_POST, \meta\users::NAME);
        $password = filter_input(INPUT_POST, \meta\users::PASSWORD);

        $condition = [
            \meta\users::NAME => $login,
            \meta\users::PASSWORD => self::cryptPassword($password)
        ];

        $user = config::$db->Select(\meta\users::__name__, ['*'], $condition);
        if (empty($user)) {
            return "login failed";
        }
        $_SESSION["user"] = $user[0];
        header("location:/");
        exit();
    }

    #[Route('user/logout')]
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
