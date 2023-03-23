<?php

class config {
    public const root_dir = __DIR__;
    public const core_dir = __DIR__ . "/core/";
    public const templates_dir = __DIR__ . "/templates/";
    public const styles_dir = __DIR__ . "/styles/";
    public const contests_dir = self::root_dir . "/contests/";

    public const dsn = "sqlite:" . self::root_dir . "/database/database.sqlite";

    private const CORE = [
        "mc/classifier",
        "mc/crud",
        "mc/database",
        "mc/filesystem",
        "mc/logger",
        "mc/router",
        "mc/template",
        "Contest",
        "ContestTable",
        "Participant",
        "ParticipantResult",
        "Profile",
        "Task",
        "TaskResult",
        "Test",
        "/../renders/Common",
        "/../renders/admin/Contest",
        "/../renders/admin/Task",
    ];

    public static function core() {
        foreach (self::CORE as $module) {
            include_once (self::core_dir . "{$module}.php");
        }
    }
}

config::core();