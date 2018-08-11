<?php

namespace models;

use core\DBDriver;
use core\Validator;

class UserModel extends BaseModel
{
	private $rules = [
		USER_PRIMARY_KEY => [
			'require' => false,
			'type' => 'integer',
			'length' => 11,
			'primary' => true
		],

		USER_LOGIN => [
			'require' => true,
			'not_blank' => true,
			'type' => 'string',
			'length' => 25,
		],

		USER_PASSWORD => [
			'require' => true,
			'not_blank' => true,
			'type' => 'string',
			'length' => 25,
		]
	];

	public function __construct(DBDriver $driverDB, Validator $validator)
	{
		parent::__construct($driverDB, $validator, 'users');
		$this->validator->setRules($this->rules);
	}
	
	// public function isUserExit($isAuth, $logout)
	// {
	// 	if($isAuth && $logout === 'logout') {
	// 		unset($_SESSION['is_auth']);
	// 		unset($_SESSION['id_user']);

	// 		return true;
	// 	}

	// 	return false;
	// }
}