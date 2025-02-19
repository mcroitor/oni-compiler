<?php

namespace meta;

class solutions {

/** table name constant */
	public const __name__ = 'solutions';

/** table columns fields */
	public $id;
	public $contestant_id;
	public $contest_task_id;
	public $timestamp;
	public $state;
	public $points;
	public $path;

/** table columns names */
	public const ID = 'id';
	public const CONTESTANT_ID = 'contestant_id';
	public const CONTEST_TASK_ID = 'contest_task_id';
	public const TIMESTAMP = 'timestamp';
	public const STATE = 'state';
	public const POINTS = 'points';
	public const PATH = 'path';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
