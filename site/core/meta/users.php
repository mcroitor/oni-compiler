<?php

namespace meta;

class users {

/** table name constant */
	public const __name__ = 'users';

/** table columns fields */
	public $id;
	public $name;
	public $email;
	public $password;
	public $firstname;
	public $lastname;
	public $institution;
	public $role_id;

/** table columns names */
	public const ID = 'id';
	public const NAME = 'name';
	public const EMAIL = 'email';
	public const PASSWORD = 'password';
	public const FIRSTNAME = 'firstname';
	public const LASTNAME = 'lastname';
	public const INSTITUTION = 'institution';
	public const ROLE_ID = 'role_id';

/** constructor */
	public function __construct(array|object $data) {
		foreach($data as $key => $value) {
			$this->$key = $value;
		}
	}
}
