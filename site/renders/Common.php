<?php

namespace mc\render;

class Common {

    public static function participants(array $participants) {
        $result = new \mc\template(
            file_get_contents(\config::templates_dir . "/participants.tpl.php"));
        $template = new \mc\template(
            file_get_contents(\config::templates_dir . "/participants-item.tpl.php"));
        $participants_items = "";
        foreach ($participants as $participant) {
            $participants_items .= $template->fill([
                "<!-- participant -->" => $participant->name(),
                "<!-- participant-firstname -->" => $participant->firstname(),
                "<!-- participant-lastname -->" => $participant->lastname(),
                ])->value();
        }
        return $result->fill(["<!-- participants-items -->" => $participants_items])->value();
    }

    public static function tasks(array $tasks) {
        $result = "<ul>";
        $template = new \mc\template(file_get_contents(\config::templates_dir . "/task.tpl.php"));
        foreach ($tasks as $task) {
            $result .= $template->fill(["<!-- task -->" => $task->name()])->value();
        }
        $result .= "</ul>";
        return $result;
    }

    public static function participant(\mc\Participant $participant) {
        $template = new \mc\template(
            file_get_contents(\config::templates_dir . "/participant.tpl.php"));
        $tasks_html = "<ul>";
        foreach ($participant->files() as $file) {
            $tasks_html .= "<li>{$file}</li>";
        }
        $tasks_html .= "</ul>";

        return $template->fill([
            "<!-- participant -->" => $participant->name(),
            "<!-- participant-firstname -->" => $participant->firstname(),
            "<!-- participant-lastname -->" => $participant->lastname(),
            "<!-- tasks -->" => $tasks_html,
        ])->value();
    }
}