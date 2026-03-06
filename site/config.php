<?php

$core_dir = __DIR__ . DIRECTORY_SEPARATOR . "core";

spl_autoload_register(function ($class) use ($core_dir) {
    $prefixes = [
        "Mc\\" => [$core_dir . "/mc/"],
        "Core\\" => [$core_dir . "/"],
        "core\\html\\" => [$core_dir . "/html/"],
        "meta\\" => [$core_dir . "/meta/"],
    ];

    foreach ($prefixes as $prefix => $paths) {
        $len = strlen($prefix);
        if (strncmp($prefix, $class, $len) === 0) {
            $relativeClass = substr($class, $len);
            foreach ($paths as $path) {
                $file = $path . str_replace("\\", "/", $relativeClass) . ".php";
                if (file_exists($file)) {
                    require $file;
                    return;
                }
            }
        }
    }
});

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

    public static $db = null;
    public static $logger = null;

    public static function core()
    {
        self::$db = new \Mc\Sql\Database(self::dsn);
        self::$logger = \Mc\Logger::stderr();
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
