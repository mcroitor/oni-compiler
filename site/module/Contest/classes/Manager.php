<?php

namespace Contest;

use \mc\route;

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

    public static function init()
    {
        // main menu
        
        if (\User\Manager::isLogged()) {
            \config::addMainMenu([
                "Contests" => "/?q=contest",
            ]);
        }
    }

    #[route('contest')]
    public static function actions(array $params) {
        $html = "<ul class='vertical-menu'>";
        $links = [
            "list contests" => "contest/list",
            "create contest" => "contest/create",
        ];
        foreach ($links as $name => $link) {
            $html .= "<li><a href='/?q={$link}' class='button w-200px'>{$name}</a></li>";
        }
        $html .= "</ul>";
        return $html;
    }

    /**
     * show list of contests
     * @param array $params
     * @return string html view of list of contests
     */
    #[route('contest/list')]
    public static function list(array $params)
    {
        $from = empty($params[0]) ? 0 : (int) $params[0];
        $offset = empty($params[1]) ? 20 : (int) $params[1];

        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contests = $crud->all($from, $offset);

        $list = "";

        \mc\logger::stderr()->info("contests: " . json_encode($contests));

        foreach ($contests as $contest) {
            $list .= \mc\template::load(
                self::templates_dir . "contestlist.element.tpl.php",
                \mc\template::comment_modifiers
            )->fill([
                "name" => $contest[\meta\contests::NAME],
                "start" => $contest[\meta\contests::START],
                "end" => $contest[\meta\contests::END],
                "id" => $contest[\meta\contests::ID],
            ])->value();
        }
        return \mc\template::load(
            self::templates_dir . "contestlist.tpl.php",
            \mc\template::comment_modifiers
        )->fill([
            "contestlist element" => $list
        ])->value();
    }

    /**
     * create new contest if is set $_POST["create-contest"],
     * otherwise show create contest form
     * @param array $params not used
     * @return string create contest form or empty string
     */
    #[route('contest/create')]
    public static function create(array $params)
    {
        if (isset($_POST["create-contest"])) {
            $contestId = self::insertData();
            self::createStructure($contestId);
            header("location:/?q=contest/update/{$contestId}");
            return "";
        }

        return \mc\template::load(
            self::templates_dir . "contest.create.tpl.php",
            \mc\template::comment_modifiers
        )->value();
    }

    /**
     * update contest if is set $_POST["update-contest"],
     * otherwise show update contest form
     * @param array $params if not post request, contains $contestId
     * @return string update contest form or empty string
     */
    #[route('contest/update')]
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
        $tpl = \mc\template::load(
            self::templates_dir . "contest.update.tpl.php",
            \mc\template::comment_modifiers
        );

        return $tpl->fill([
            "contest-id" => $contest[\meta\contests::ID],
            "contest-name" => $contest[\meta\contests::NAME],
            "contest-description" => $contest[\meta\contests::DESCRIPTION],
            "contest-start" => $contest[\meta\contests::START],
            "contest-end" => $contest[\meta\contests::END],
            "tasks" => self::tasksInContest($contest[\meta\contests::ID])
        ])->value();
    }

    /**
     * Contest view
     * @param array $params first element is $contestId
     * @return string html representation of contest
     */
    #[route('contest/view')]
    public static function view(array $params)
    {
        $contestId = empty($params[0]) ? -1 : (int) $params[0];
        $db = new \mc\sql\database(\config::dsn);
        $crud = new \mc\sql\crud($db, \meta\contests::__name__);
        $contest = $crud->select($contestId);

        if (empty($contest)) {
            return "";
        }

        $tpl = \mc\template::load(
            self::templates_dir . "contest.view.tpl.php",
            \mc\template::comment_modifiers
        );
        return $tpl->fill([
            "contest-name" => $contest[\meta\contests::NAME],
            "contest-description" => $contest[\meta\contests::DESCRIPTION],
            "contest-start" => $contest[\meta\contests::START],
            "contest-end" => $contest[\meta\contests::END],
            "tasks" => self::tasksInContest($contestId),
        ])->value();
    }

    /**
     * remove contest by id
     * @param array $params first element is $contestId
     * @return string empty string
     */
    #[route('contest/remove')]
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
        \mc\logger::stderr()->info("contest data prepared: " . json_encode($data));
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
        \mc\logger::stderr()->info("contest data prepared: " . json_encode($data));
        $crud->update($data);
        return $data[\meta\contests::ID];
    }

    #[route('contest/tasks')]
    public static function tasks(array $params)
    {
        $contestId = empty($params[0]) ? -1 : (int) $params[0];
        $tpl = \mc\template::load(
            self::templates_dir . "contest.tasks.tpl.php",
            \mc\template::comment_modifiers
        );
        return $tpl->fill([
            "contest-id" => $contestId,
            "in-contest-tasks" => self::tasksInContest($contestId),
            "out-contest-tasks" => self::tasksOutOfContest($contestId),
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
        $tpl = \mc\template::load(
            self::templates_dir . "contest.tasks.element-in.tpl.php",
            \mc\template::comment_modifiers
        );
        foreach ($taskIds as $taskId) {
            $task = \Task\Manager::get($taskId);
            $result .= $tpl->fill([
                "task-id" => $task[\meta\tasks::ID],
                "task-name" => $task[\meta\tasks::NAME],
                "task-time" => $task[\meta\tasks::TIME],
                "task-memory" => $task[\meta\tasks::MEMORY],
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
        $tpl = \mc\template::load(
            self::templates_dir . "contest.tasks.element-out.tpl.php",
            \mc\template::comment_modifiers
        );
        $count = 0;
        foreach ($tasks as $task) {
            if (array_search($task[\meta\tasks::ID], $taskIds) !== false) {
                continue;
            }
            ++$count;
            $result .= $tpl->fill([
                "task-id" => $task[\meta\tasks::ID],
                "task-name" => $task[\meta\tasks::NAME],
                "task-time" => $task[\meta\tasks::TIME],
                "task-memory" => $task[\meta\tasks::MEMORY],
            ])->value();
            if ($count >= \config::items_per_page) {
                break;
            }
        }
        return $result;
    }

    #[route('contest/addtasks')]
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

    #[route('contest/participants')]
    public static function participants(array $params)
    {
        $contestId = empty($params[0]) ? 0 : (int) $params[0];

        return \mc\template::load(
            self::templates_dir . "contest.addparticipants.tpl.php",
            \mc\template::comment_modifiers
        )->fill([
            "contest-id" => $contestId,
            "in-contest-users" => self::usersInContest($contestId),
            "out-contest-users" => self::usersOutOfContest($contestId),
        ])->value();
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
        $tpl = \mc\template::load(
            self::templates_dir . "contest.participants.tpl.php",
            \mc\template::comment_modifiers
        );
        foreach ($userIds as $userId) {
            $task = \User\Manager::get($userId);
            $result .= $tpl->fill([
                "user-id" => $task[\meta\users::ID],
                "user-firstname" => $task[\meta\users::FIRSTNAME],
                "user-lastname" => $task[\meta\users::LASTNAME],
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
        $tpl = \mc\template::load(
            self::templates_dir . "contest.users.tpl.php",
            \mc\template::comment_modifiers
        );
        $count = 0;
        foreach ($users as $user) {
            if (array_search($user[\meta\users::ID], $usersIds) !== false) {
                continue;
            }
            ++$count;
            $result .= $tpl->fill([
                "user-id" => $user[\meta\users::ID],
                "user-firstname" => $user[\meta\users::FIRSTNAME],
                "user-lastname" => $user[\meta\users::LASTNAME],
            ])->value();
            if ($count >= \config::items_per_page) {
                break;
            }
        }
        return $result;
    }

    #[route('contest/addparticipants')]
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
            "Update Contest" => "contest/update",
            "View Contest" => "contest/view",
            "Contest Tasks" => "contest/tasks",
            "Add tasks" => "contest/addtasks",
            "Participants" => "contest/participants",
            "Add Participants" => "contest/addparticipants",
        ]))->active("");
    }
}
