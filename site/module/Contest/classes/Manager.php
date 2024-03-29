<?php

namespace Contest;

class Manager
{

    /**
     * @var string the module directory
     */
    public const dir = \config::module_dir . "/Contest/";

    /**
     * @var string the templates directory
     */
    public const templates_dir = self::dir . "/templates/";

    /**
     * show list of contests
     * @param array $params
     * @return string html view of list of contests
     */
    public static function list(array $params)
    {
        $from = empty($params[0]) ? 0 : (int) $params[0];
        $offset = empty($params[1]) ? 20 : (int) $params[1];

        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contests = $crud->all($from, $offset);

        $list = "";

        foreach ($contests as $contest) {
            $list .= (new \mc\template(
                file_get_contents(self::templates_dir . "contestlist.element.tpl.php")
            ))->fill([
                "<!-- name -->" => $contest[\meta\contests::NAME],
                "<!-- start -->" => $contest[\meta\contests::START],
                "<!-- end -->" => $contest[\meta\contests::END],
                "<!-- id -->" => $contest[\meta\contests::ID],
            ])->value();
        }
        return (new \mc\template(
            file_get_contents(self::templates_dir . "contestlist.tpl.php")
        ))->fill([
            "<!-- contestlist element -->" => $list
        ])->value();
    }

    /**
     * create new contest if is set $_POST["create-contest"],
     * otherwise show create contest form
     * @param array $params not used
     * @return string create contest form or empty string
     */
    public static function create(array $params)
    {
        if (isset($_POST["create-contest"])) {
            $contestId = self::insertData();
            self::createStructure($contestId);
            header("location:/?q=contest/update/{$contestId}");
            return "";
        }

        return file_get_contents(self::templates_dir . "contest.create.tpl.php");
    }

    /**
     * update contest if is set $_POST["update-contest"],
     * otherwise show update contest form
     * @param array $params if not post request, contains $contestId
     * @return string update contest form or empty string
     */
    public static function update(array $params)
    {
        if (isset($_POST["update-contest"])) {
            $contestId = filter_input(INPUT_POST, "contest-id");
            self::updateData();
            header("location:/?q=contest/update/{$contestId}");
            return "";
        }
        $contestId = empty($params[0]) ? 0 : (int) $params[0];
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contest = $crud->select($contestId);
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.update.tpl.php")
        );

        return $tpl->fill([
            "<!-- contest-id -->" => $contest[\meta\contests::ID],
            "<!-- contest-name -->" => $contest[\meta\contests::NAME],
            "<!-- contest-description -->" => $contest[\meta\contests::DESCRIPTION],
            "<!-- contest-start -->" => $contest[\meta\contests::START],
            "<!-- contest-end -->" => $contest[\meta\contests::END],
            "<!-- tasks -->" => self::tasksInContest($contest[\meta\contests::ID])
        ])->value();
    }

    /**
     * Contest view
     * @param array $params first element is $contestId
     * @return string html representation of contest
     */
    public static function view(array $params)
    {
        $contestId = empty($params[0]) ? -1 : (int) $params[0];
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contest = $crud->select($contestId);

        if (empty($contest)) {
            return "";
        }

        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.view.tpl.php")
        );
        return $tpl->fill([
            "<!-- contest-name -->" => $contest[\meta\contests::NAME],
            "<!-- contest-description -->" => $contest[\meta\contests::DESCRIPTION],
            "<!-- contest-start -->" => $contest[\meta\contests::START],
            "<!-- contest-end -->" => $contest[\meta\contests::END],
            "<!-- tasks -->" => self::tasksInContest($contestId),
        ])->value();
    }

    /**
     * remove contest by id
     * @param array $params first element is $contestId
     * @return string empty string
     */
    public static function remove(array $params)
    {
        $id = empty($params[0]) ? -1 : (int) $params[0];
        $db = new \mc\sql\database(\config::dsn);

        $db->delete(\meta\contests::__name__, [\meta\contests::ID => $id]);
        // delete relation contest <-> tasks
        // $db->delete(\meta\task_tests::__name__, [\meta\task_tests::TASK_ID => $id]);
        // delete files
        // TODO #: implement this
        header("location:/?q=contest/list");
        return "";
    }

    /**
     * insert contest data into database
     * @return string contest id
     */
    private static function insertData()
    {
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $data = [
            \meta\contests::NAME => filter_input(INPUT_POST, "contest-name"),
            \meta\contests::DESCRIPTION => filter_input(INPUT_POST, "contest-description"),
            \meta\contests::START => filter_input(INPUT_POST, "contest-start"),
            \meta\contests::END => filter_input(INPUT_POST, "contest-end"),
        ];
        \mc\logger::stdout()->info("contest data prepared: " . json_encode($data));
        return $crud->insert($data);
    }

    /**
     * create file structure for contest
     * @param string $contestId
     */
    private static function createStructure($contestId)
    {
        mkdir(self::getContestPath($contestId));
    }

    /**
     * returns contest path in FS
     * @param string $contestID
     * @return string
     */
    public static function getContestPath($contestId)
    {
        return \config::contests_dir . "{$contestId}/";
    }

    /**
     * update contest data into database
     * @return string contest id
     */
    public static function updateData()
    {
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $data = [
            \meta\contests::ID => filter_input(INPUT_POST, "contest-id"),
            \meta\contests::NAME => filter_input(INPUT_POST, "contest-name"),
            \meta\contests::DESCRIPTION => filter_input(INPUT_POST, "contest-description"),
            \meta\contests::START => filter_input(INPUT_POST, "contest-start"),
            \meta\contests::END => filter_input(INPUT_POST, "contest-end"),
        ];
        \mc\logger::stdout()->info("contest data prepared: " . json_encode($data));
        $crud->update($data);
        return $data[\meta\contests::ID];
    }

    public static function tasks(array $params)
    {
        $contestId = empty($params[0]) ? -1 : (int) $params[0];
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.tasks.tpl.php")
        );
        return $tpl->fill([
            "<!-- contest-id -->" => $contestId,
            "<!-- in-contest-tasks -->" => self::tasksInContest($contestId),
            "<!-- out-contest-tasks -->" => self::tasksOutOfContest($contestId),
        ])->value();
    }

    private static function tasksInContest($contestId)
    {
        $db = new \mc\sql\database(\config::dsn);
        $taskIds = $db->select_column(
            \meta\contest_tasks::__name__,
            \meta\contest_tasks::TASK_ID,
            [\meta\contest_tasks::CONTEST_ID => $contestId]
        );

        $result = "";
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.tasks.element-in.tpl.php")
        );
        foreach ($taskIds as $taskId) {
            $task = \Task\Manager::get($taskId);
            $result .= $tpl->fill([
                "<!-- task-id -->" => $task[\meta\tasks::ID],
                "<!-- task-name -->" => $task[\meta\tasks::NAME],
                "<!-- task-time -->" => $task[\meta\tasks::TIME],
                "<!-- task-memory -->" => $task[\meta\tasks::MEMORY],
            ])->value();
        }
        return $result;
    }

    private static function tasksOutOfContest($contestId)
    {
        $db = new \mc\sql\database(\config::dsn);
        $taskIds = $db->select_column(
            \meta\contest_tasks::__name__,
            \meta\contest_tasks::TASK_ID,
            [\meta\contest_tasks::CONTEST_ID => $contestId]
        );

        $tasks = $db->select(\meta\tasks::__name__);
        $result = "";
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.tasks.element-out.tpl.php")
        );
        $count = 0;
        foreach ($tasks as $task) {
            if (array_search($task[\meta\tasks::ID], $taskIds) !== false) {
                continue;
            }
            ++$count;
            $result .= $tpl->fill([
                "<!-- task-id -->" => $task[\meta\tasks::ID],
                "<!-- task-name -->" => $task[\meta\tasks::NAME],
                "<!-- task-time -->" => $task[\meta\tasks::TIME],
                "<!-- task-memory -->" => $task[\meta\tasks::MEMORY],
            ])->value();
            if ($count >= \config::items_per_page) {
                break;
            }
        }
        return $result;
    }

    public static function addTasks(array $params)
    {
        $contestId = empty($params[0]) ? 0 : (int) $params[0];
        $args = [
            "tasks" => [
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY,
            ],
        ];
        $post = filter_input_array(INPUT_POST, $args);
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contest_tasks::__name__);
        $selectedTasks = $post["tasks"];

        foreach ($selectedTasks as $taskId => $value) {
            $data = [
                \meta\contest_tasks::CONTEST_ID => $contestId,
                \meta\contest_tasks::TASK_ID => $taskId,
                \meta\contest_tasks::WEIGHT => 0,
            ];
            $crud->insert($data);
        }
        header("location:/?q=contest/update/{$contestId}");
        return "";
    }

    public static function participants(array $params)
    {
        $contestId = empty($params[0]) ? 0 : (int) $params[0];
        $data = file_get_contents(self::templates_dir . "contest.addparticipants.tpl.php");
        return (new \mc\template($data))
            ->fill([
                "<!-- contest-id -->" => $contestId,
                "<!-- in-contest-users -->" => self::usersInContest($contestId),
                "<!-- out-contest-users -->" => self::usersOutOfContest($contestId),
            ])
            ->value();
    }

    private static function usersInContest($contestId)
    {
        $db = new \mc\sql\database(\config::dsn);
        $userIds = $db->select_column(
            \meta\contestants::__name__,
            \meta\contestants::USER_ID,
            [\meta\contestants::CONTEST_ID => $contestId]
        );

        $result = "";
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.participants.tpl.php")
        );
        foreach ($userIds as $userId) {
            $task = \User\Manager::get($userId);
            $result .= $tpl->fill([
                "<!-- user-id -->" => $task[\meta\users::ID],
                "<!-- user-firstname -->" => $task[\meta\users::FIRSTNAME],
                "<!-- user-lastname -->" => $task[\meta\users::LASTNAME],
            ])->value();
        }
        return $result;
    }

    private static function usersOutOfContest($contestId)
    {
        $db = new \mc\sql\database(\config::dsn);
        $usersIds = $db->select_column(
            \meta\contestants::__name__,
            \meta\contestants::USER_ID,
            [\meta\contest_tasks::CONTEST_ID => $contestId]
        );

        $users = $db->select(\meta\users::__name__);
        $result = "";
        $tpl = new \mc\template(
            file_get_contents(self::templates_dir . "contest.users.tpl.php")
        );
        $count = 0;
        foreach ($users as $user) {
            if (array_search($user[\meta\users::ID], $usersIds) !== false) {
                continue;
            }
            ++$count;
            $result .= $tpl->fill([
                "<!-- user-id -->" => $user[\meta\users::ID],
                "<!-- user-firstname -->" => $user[\meta\users::FIRSTNAME],
                "<!-- user-lastname -->" => $user[\meta\users::LASTNAME],
            ])->value();
            if ($count >= \config::items_per_page) {
                break;
            }
        }
        return $result;
    }

    public static function addParticipants(array $params)
    {
        $contestId = empty($params[0]) ? 0 : (int) $params[0];
        $args = [
            "users" => [
                'filter' => FILTER_VALIDATE_INT,
                'flags' => FILTER_REQUIRE_ARRAY,
            ],
        ];
        $post = filter_input_array(INPUT_POST, $args);
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contestants::__name__);
        $selectedUsers = $post["users"];

        foreach ($selectedUsers as $userId => $value) {
            $data = [
                \meta\contestants::CONTEST_ID => $contestId,
                \meta\contestants::USER_ID => $userId,
            ];
            $crud->insert($data);
        }
        header("location:/?q=contest/participants/{$contestId}");
        return "";
    }

    public static function getAsideMenu()
    {
        return (new \core\html\widget\nav([
            "View Contests" => "contest/list",
            "Create Contest" => "contest/create",
            "View Contests" => "contest/update",
            "View Contests" => "contest/view",
            "View Contests" => "contest/tasks",
            "View Contests" => "contest/addtasks",
            "View Contests" => "contest/participants",
            "View Contests" => "contest/addparticipants",
        ]))->active("");
    }
}
