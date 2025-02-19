<?php

namespace meta;

class contestants {

/** table name constant */
	public const __name__ = 'contestants';

/** table columns fields */
	public $id;
	public $contest_id;
	public $user_id;

/** table columns names */
	public const ID = 'id';
	public const CONTEST_ID = 'contest_id';
	public const USER_ID = 'user_id';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
