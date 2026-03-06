<?php

class config
{
    public const items_per_page = 20;

    public const DS = DIRECTORY_SEPARATOR;

    public const root_dir = __DIR__;
    public const core_dir = self::root_dir . self::DS . "core";
    public const module_dir = self::root_dir . self::DS . "module";
    public const database_dir = self::root_dir . self::DS . ".." . self::DS . "data";
    public const data_dir = self::root_dir . self::DS . ".." . self::DS . "data";
    public const templates_dir = self::root_dir . self::DS . "templates";
    public const styles_dir = self::root_dir . self::DS . "styles";
    public const contests_dir = self::data_dir . self::DS . "contests";
    public const tasks_dir = self::data_dir . self::DS . "tasks";
    public const tmp_dir = self::data_dir . self::DS . "tmp";

    public const languages_config = self::data_dir . self::DS . "profiles.json";

    public const salt = "unpredictable_salt_value";

    public const dsn = "sqlite:" . self::database_dir . self::DS . "database.sqlite";

    private const CORE = [
        "mc/classifier",
        "mc/sql/crud",
        "mc/sql/database",
        "mc/filesystem/manager",
        "mc/filesystem/path",
        "mc/filesystem/sausage",
        "mc/logger",
        "mc/router",
        "mc/template",
        "helper",
        // meta data
        "meta/capabilities",
        "meta/contest_tasks",
        "meta/contestants",
        "meta/contests",
        "meta/role_capabilities",
        "meta/roles",
        "meta/solutions",
        "meta/tasks",
        "meta/task_tests",
        "meta/users",
        // html
        "html/style",
        "html/buildable",
        "html/text",
        "html/a",
        "html/html_element",
        "html/page",
        "html/builder",
        "html/form",
        "html/input",
        "html/label",
        "html/option",
        "html/widget/nav",
    ];

    public static $db = null;
    public static $logger = null;

    public static function core()
    {
        foreach (self::CORE as $module) {
            include_once self::core_dir . self::DS . "{$module}.php";
        }
        self::$db = new Mc\Sql\Database(self::dsn);
        self::$logger = Mc\Logger::stderr();
    }

    public static function load_modules()
    {
        $crud = new \Mc\Sql\Crud(self::$db, "modules");
        $modules = $crud->all();
        foreach ($modules as $module) {
            $module_name = $module["name"];
            include_once config::module_dir . self::DS . "{$module_name}"
                . self::DS . "index.php";
        }

        foreach($modules as $module) {
            $module_name = $module["name"];
            $module_init = "\\$module_name\\init";
            if(function_exists($module_init)) {
                $module_init();
            }
        }
    }

    // main menu

    private static $mainMenu = [
    ];

    public static function addMainMenu(array $links)
    {
        foreach ($links as $title => $link) {
            self::$mainMenu[$title] = $link;
        }
    }

    public static function getMainMenu()
    {
        return self::$mainMenu;
    }

    // aside menu
    public static $asideMenu = [];

    public static function addAsideMenu(array $links)
    {
        foreach ($links as $title => $link) {
            self::$asideMenu[$title] = $link;
        }
    }

    public static function getAsideMenu()
    {
        return self::$asideMenu;
    }
}

config::core();
