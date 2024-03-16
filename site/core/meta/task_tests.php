<?php

namespace meta;

class task_tests {

/** table name constant */
	public const __name__ = 'task_tests';

/** table columns fields */
	public $id;
	public $task_id;
	public $label;
	public $input;
	public $output;
	public $points;

/** table columns names */
	public const ID = 'id';
	public const TASK_ID = 'task_id';
	public const LABEL = 'label';
	public const INPUT = 'input';
	public const OUTPUT = 'output';
	public const POINTS = 'points';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
