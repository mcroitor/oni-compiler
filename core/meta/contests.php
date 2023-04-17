<?php

namespace meta;

class contests {

/** table name constant */
	public const __name__ = 'contests';

/** table columns fields */
	public $id;
	public $name;
	public $start;
	public $end;

/** table columns names */
	public const ID = 'id';
	public const NAME = 'name';
	public const START = 'start';
	public const END = 'end';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
