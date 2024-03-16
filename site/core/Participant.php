<?php

namespace mc;

class Participant
{
    private $name;
    private $firstname;
    private $lastname;
    private $institution;
    private $files = [];

    public function __construct($participant)
    {
        $this->name = $participant->name;
        $this->firstname = $participant->firstname;
        $this->lastname = $participant->lastname;
        $this->institution = $participant->institution;
    }

    public function load(string $path, array $extensions, array $tasks)
    {
        $files = array_diff(scandir($path . $this->name), [".", ".."]);

        foreach ($files as $file) {
            $fileName = \mc\filesystem::fileName($file);
            $extension = \mc\filesystem::extension($file);
            $taskName = explode(".", $fileName)[0];
            // \mc\logger::stdout()->info("ext: {$extension}, filename: {$fileName}");
            if (
                array_search($extension, $extensions) !== false &&
                array_search($taskName, $tasks) !== false
            ) {
                $this->files[] = $file;
            }
        }
        // \mc\logger::stdout()->info(json_encode($this->files));
    }


    public function name()
    {
        return $this->name;
    }

    public function firstname()
    {
        return $this->firstname;
    }

    public function lastname()
    {
        return $this->lastname;
    }

    public function institution()
    {
        return $this->institution;
    }

    public function files()
    {
        return $this->files;
    }
}
