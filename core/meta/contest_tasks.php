<?php

namespace meta;

class contest_tasks {

/** table name constant */
	public const __name__ = 'contest_tasks';

/** table columns fields */
	public $id;
	public $contest_id;
	public $task_id;
	public $weight;

/** table columns names */
	public const ID = 'id';
	public const CONTEST_ID = 'contest_id';
	public const TASK_ID = 'task_id';
	public const WEIGHT = 'weight';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
