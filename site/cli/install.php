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
    "contests",
//    "role_capabilities",
//    "users",
    "modules",
];

use \Mc\Logger;
use \Mc\Sql\Database;

$stdout = Logger::StdOut();

$stdout->Info("create database");
// unlink db??
$db = new Database(config::dsn);
$db->Query("PRAGMA foreign_keys = ON;");

$stdout->Info("create tables");

foreach ($TABLE_SCHEMA as $table) {
    $query = file_get_contents(__DIR__ . "/../database/structure/{$table}.sql");
    $stdout->Info("create table {$table}");
    $stdout->Info("table schema: {$query}");
    $db->Query($query);
    $stdout->Info("table `{$table}` is created");
}

$stdout->Info("all tables are created.");
$stdout->Info("create initial data.");

foreach ($TABLE_DATA as $data){
    $dump_file = __DIR__ . "/../database/data/{$data}.sql";
    $stdout->Info("insert data into {$data}");
    $db->parseSqlDump($dump_file);
    $stdout->Info("data for `{$data}` is created");
}

$stdout->Info("all data was inserted.");
$stdout->Info("create administrator.");

$data = [
    "name" => "admin",
    "email" => "admin@loves.you",
    "password" => crypt("password", config::salt),
    "firstname" => "Super",
    "lastname" => "Admin",
    "institution" => "",
    "role_id" => $db->select("roles", ["id"], ["name" => "administrator"])[0]["id"]
];

$db->Insert("users", $data);
