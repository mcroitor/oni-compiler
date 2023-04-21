<?php

include_once __DIR__ . "/../config.php";

$TABLE_SCHEMA = [
    "capabilities",
    "roles",
    "role_capabilities",
    "users",
    "tasks",
    "contests",
    "contestants",
    "contest_tasks",
    "solutions",
    "task_tests",
    "modules",
];

$TABLE_DATA = [
    "capabilities",
    "roles",
//    "role_capabilities",
//    "users",
    "modules",
];

use \mc\logger;
use \mc\sql\database;

$stdout = logger::stdout();

$stdout->info("create database");
// unlink db??
$db = new database(config::dsn);
$db->query_sql("PRAGMA foreign_keys = ON;");

$stdout->info("create tables");

foreach ($TABLE_SCHEMA as $table) {
    $query = file_get_contents(config::database_dir . "/structure/{$table}.sql");
    $stdout->info("create table {$table}");
    $stdout->info("table schema: {$query}");
    $db->query_sql($query);
    $stdout->info("table `{$table}` is created");
}

$stdout->info("all tables are created.");
$stdout->info("create initial data.");

foreach ($TABLE_DATA as $data){
    $dump_file = config::database_dir . "/data/{$data}.sql";
    $stdout->info("insert data into {$data}");
    $db->parse_sqldump($dump_file);
    $stdout->info("data for `{$data}` is created");
}

$stdout->info("all data was inserted.");
$stdout->info("create administrator.");

$data = [
    "name" => "admin",
    "email" => "admin@loves.you",
    "password" => crypt("password", config::salt),
    "firstname" => "Super",
    "lastname" => "Admin",
    "institution" => "",
    "role_id" => $db->select("roles", ["id"], ["name" => "administrator"])[0]["id"]
];

$db->insert("users", $data);
