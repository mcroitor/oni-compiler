<?php

namespace LanguageProfile;

include_once __DIR__ . "/LanguageProfile.php";

use config;
use \Mc\Route;
use \Mc\Template as Template;

class Manager
{
    public const dir = config::module_dir . "/LanguageProfile/";
    public const templates_dir = self::dir . "/templates/";

    private static $languageProfiles = [];

    private static function reloadProfiles(): void
    {
        self::$languageProfiles = [];
        if (!file_exists(config::languages_config)) {
            return;
        }
        self::$languageProfiles = json_decode(file_get_contents(config::languages_config), true);
    }

    private static function getProfileId(string $name): ?int
    {
        foreach (self::$languageProfiles as $key => $profile) {
            if ($profile["name"] == $name) {
                return $key;
            }
        }
        return null;
    }

    private static function saveProfiles(): void
    {
        file_put_contents(config::languages_config, json_encode(self::$languageProfiles, JSON_PRETTY_PRINT));
    }

    public static function init(): void
    {
        self::reloadProfiles();
    }


    public static function actions(): void
    {
        config::addAsideMenu([
            "list profile" => "?q=profile/list",
            "New profile" => "?q=profile/create",
        ]);
    }

    #[Route("profile/list")]
    public static function list(array $params): string
    {
        self::actions();

        $list = "";
        foreach (self::$languageProfiles as $key => $languageProfile) {
            $profile = new LanguageProfile($languageProfile);
            $list .= Template::Load(
                self::templates_dir . "profile-list.element.tpl.php",
                Template::CM
            )->Fill([
                "id" => $key,
                "profile-name" => $profile->GetName(),
                "compile-command" => $profile->CompileLine(),
                "execute-command" => $profile->ExecuteLine(),
            ])->Value();
        }
        return Template::Load(
            self::templates_dir . "profile-list.tpl.php",
            Template::CM
        )->Fill([
            "profile-list-element" => $list
        ])->Value();
    }

    #[Route("profile/create")]
    public static function create(array $params): string
    {
        if (isset($_POST["profile-name"])) {
            if (self::getProfileId($_POST["profile-name"]) !== null) {
                header("location:/?q=profile/list");
                exit();
            }
            self::$languageProfiles[] = [
                "name" => $_POST["profile-name"],
                "extensions" => explode(",", $_POST["extensions"]),
                "compiler_path" => $_POST["compiler-path"],
                "compiler_output" => $_POST["compiler-output"],
                "compile_command" => $_POST["compile-command"],
                "interpreter_path" => $_POST["interpreter-path"],
                "execute_command" => $_POST["execute-command"],
            ];

            self::saveProfiles();
            header("location:/?q=profile/list");
            exit();
        }
        self::actions();

        return Template::Load(
            self::templates_dir . "profile.create.tpl.php",
            Template::CM
        )->Fill([
            "profile-name" => "",
            "extensions" => "",
            "compiler-path" => "",
            "compiler-output" => "",
            "compile-command" => "{compiler-path} {source} -o {compiler-output}",
            "interpreter-path" => "",
            "execute-command" => "{interpreter-path} {source}",
            "action" => "create",
        ])->Value();
    }

    #[Route("profile/update")]
    public static function update(array $params): string
    {
        if (empty($params)) {
            header("location:/?q=profile/list");
            exit();
        }
        $profileId = $params[0];

        if (isset($_POST["profile-name"])) {
            self::$languageProfiles[$profileId] = [
                "name" => $_POST["profile-name"],
                "extensions" => explode(",", $_POST["extensions"]),
                "compiler_path" => $_POST["compiler-path"],
                "compiler_output" => $_POST["compiler-output"],
                "compile_command" => $_POST["compile-command"],
                "interpreter_path" => $_POST["interpreter-path"],
                "execute_command" => $_POST["execute-command"]
            ];

            self::saveProfiles();
            header("location:/?q=profile/list");
            exit();
        }
        self::actions();
        $languageProfile = new LanguageProfile(self::$languageProfiles[$profileId]);

        return Template::Load(
            self::templates_dir . "profile.create.tpl.php",
            Template::CM
        )->Fill([
            "profile-name" => $languageProfile->GetName(),
            "extensions" => implode(",", $languageProfile->GetExtensions()),
            "compiler-path" => $languageProfile->GetCompilerPath(),
            "compiler-output" => $languageProfile->GetCompilerOutput(),
            "compile-command" => $languageProfile->GetCompileCommand(),
            "interpreter-path" => $languageProfile->GetInterpreterPath(),
            "execute-command" => $languageProfile->GetExecuteCommand(),
            "action" => "update",
        ])->Value();
    }

    #[Route("profile/delete")]
    public static function delete(array $params): string
    {
        if (empty($params)) {
            header("location:/?q=profile/list");
            exit();
        }
        $profileId = $params[0];
        unset(self::$languageProfiles[$profileId]);
        self::saveProfiles();
        header("location:/?q=profile/list");
        exit();
    }

    #[Route("profile/validate")]
    public static function validate(array $params): string
    {
        if (empty($params)) {
            header("location:/?q=profile/list");
            exit();
        }
        self::actions();
        $profileId = $params[0];
        $languageProfile = new LanguageProfile(self::$languageProfiles[$profileId]);
        $compileCommand = $languageProfile->CompileLine();
        $executeCommand = $languageProfile->ExecuteLine();

        return Template::Load(
            self::templates_dir . "profile.validate.tpl.php",
            Template::CM
        )->Fill([
            "profile-name" => $languageProfile->GetName(),
            "compile-command" => $compileCommand,
            "compile-output" => json_encode(shell_exec($compileCommand)),
            "execute-command" => $executeCommand,
            "execute-output" => json_encode(shell_exec($executeCommand)),
        ])->Value();
    }
}
