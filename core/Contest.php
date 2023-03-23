<?php

namespace mc;

class Contest
{
    private const CONTESTANTS = "contestants";
    private const TASKS = "tasks";
    private const RESULT = "results";

    private $path;

    private $profiles = [];
    private $id = "0";
    private $name = "";
    private $tasks = [];
    private $participants = [];
    private $result = [];

    public function __construct(string $id)
    {
        $this->id = $id;
        $this->path = \config::contests_dir . "/{$this->id}/";
        $this->loadProfiles($this->path . "config.json");
        $this->loadContest($this->path . "contest.json");

        foreach ($this->participants() as $participant) {
            $this->result[$participant->name()] = [];
            foreach ($this->tasks() as $task) {
                $this->result[$participant->name()][$task->name()] = "0";
            }
        }
    }

    public function loadProfiles(string $file)
    {
        $data = file_get_contents($file);
        $profiles = json_decode($data);
        foreach ($profiles as $profile) {
            $this->profiles[$profile->extension] = new Profile($profile);
        }
    }

    public function loadContest(string $file)
    {
        $data = file_get_contents($file);
        $contest = json_decode($data);

        $this->name = $contest->name;
        foreach ($contest->tasks as $task) {
            $this->tasks[$task->name] = new Task($task);
        }

        foreach ($contest->participants as $participant) {
            $this->participants[$participant->name] = new Participant($participant);
        }
    }

    public function name()
    {
        return $this->name;
    }

    public function profiles()
    {
        return $this->profiles;
    }

    public function tasks()
    {
        return $this->tasks;
    }

    public function participants()
    {
        return $this->participants;
    }

    public function loadData()
    {
        foreach ($this->participants() as $participant) {
            $path = $this->path . \mc\filesystem::US . self::CONTESTANTS . \mc\filesystem::US;
            $extensions = array_keys($this->profiles());
            $tasks = array_keys($this->tasks());

            $participant->load($path, $extensions, $tasks);
            // get all files from directory
            // filter only allowed extensions
            // check only allowed names
        }
    }

    public function compile()
    {
        $logger = \mc\logger::stdout();
        foreach ($this->participants() as $participant) {
            $logger->info("participant: {$participant->name()}");
            foreach ($participant->files() as $file) {
                // extract taskname from filename
                $extension = \mc\filesystem::extension($file);
                $taskName = \mc\filesystem::fileName($file);
                $task = $this->task($taskName);
                // detect profile
                $profile = $this->profile($extension);
                // compile file in the workdir of task
                $logger->info("compile '{$file}', task '{$task->name()}', profile '{$profile->name}'");
                // register compiled
                $compileLine = str_replace(
                    ['{$compiler}', '{$source}', '{$app}'],
                    [$profile->compiler, $file, "{$taskName}.exe"],
                    $profile->compile
                );
                system($compileLine);
            }
        }
    }

    public function evaluate()
    {
        foreach ($this->participants() as $participant) {
            foreach ($participant->files() as $file) {
                // extract taskname from filename
                $extension = \mc\filesystem::extension($file);
                // detect profile
                $profile = $this->profile($extension);
                // compile file in the workdir of task
            }
        }
    }

    public function task(string $taskName)
    {
        return $this->tasks[$taskName];
    }
    public function profile(string $profileName): Profile
    {
        return $this->profiles[$profileName];
    }
}
