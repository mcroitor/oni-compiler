<?php

namespace meta;

class tasks {

/** table name constant */
	public const __name__ = 'tasks';

/** table columns fields */
	public $id;
	public $name;
	public $description;
	public $memory;
	public $time;

/** table columns names */
	public const ID = 'id';
	public const NAME = 'name';
	public const DESCRIPTION = 'description';
	public const MEMORY = 'memory';
	public const TIME = 'time';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
