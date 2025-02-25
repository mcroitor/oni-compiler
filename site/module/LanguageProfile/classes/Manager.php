<?php

namespace LanguageProfile;

include_once __DIR__ . "/LanguageProfile.php";

use config;
use \mc\route;

class Manager {
    public const dir = config::module_dir . "/LanguageProfile/";
    public const templates_dir = self::dir . "/templates/";
    public static function init() {}

    public static function actions(): void {
        config::addAsideMenu([
            "list profile" => "profile/list",
            "New profile" => "profile/create",
        ]);
    }

    #[route("profile/list")]
    public static function list(array $params): string
    {
        self::actions();
        $profiles = [];
        $configs = [];
        if (file_exists(config::languages_config)) {
            $configs = json_decode(file_get_contents(config::languages_config), true);
        }
        foreach ($configs as $config) {
            $profiles[] = new LanguageProfile($config);
        }

        $list = "";
        foreach ($profiles as $profile) {
            $list .= \mc\template::load(
                self::templates_dir . "profile-list.element.tpl.php",
                \mc\template::comment_modifiers
            )->fill([
                "profile-name" => $profile->GetName(),
                "compile-command" => $profile->CompileLine(),
                "execute-command" => $profile->ExecuteLine(),
            ])->value();
        }
        return \mc\template::load(
            self::templates_dir . "profile-list.tpl.php",
            \mc\template::comment_modifiers
        )->fill([
            "profile-list-element" => $list
        ])->value();
    }

    #[route("profile/create")]
    public static function create(array $params): string
    {
        if (isset($_POST["profile-name"])) {
            $configs = json_decode(file_get_contents(config::languages_config), true);

            $configs[] = [
                "name" => $_POST["profile-name"],
                "extensions" => explode(",", $_POST["extensions"]),
                "compiler_path" => $_POST["compiler-path"],
                "compiler_output" => $_POST["compiler-output"],
                "compile_command" => $_POST["compile-command"],
                "interpreter_path" => $_POST["interpreter-path"],
                "execute_command" => $_POST["execute-command"],
            ];

            file_put_contents(config::languages_config, json_encode($configs, JSON_PRETTY_PRINT));

            header("location:/?q=profile/list");
            exit();
        }
        self::actions();

        return \mc\template::load(
            self::templates_dir . "profile.create.tpl.php",
            \mc\template::comment_modifiers
        )
        ->fill([
            "compile-command" => "{compiler-path} {source} -o {compiler-output}",
            "execute-command" => "{interpreter-path} {source}",
        ])
        ->value();

    }
}