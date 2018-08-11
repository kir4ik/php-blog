<?php 

namespace models;

use core\DBDriver;
use core\Validator;

class SessionModel extends BaseModel
{
	private $rules = [
		SESS_PRIMARY_KEY => [
			'require' => true,
			'not_blank' => true,
			'type' => 'integer',
			'length' => 11
		],

		SESS_ONLINE => [
			'require' => true,
			'not_blank' => true,
			'type' => 'integer',
			'length' => 1,
		],

		SESS_RENEWAL => [
			'require' => false
		]
	];

	public function __construct(DBDriver $driverDB, Validator $validator)
	{
		parent::__construct($driverDB, $validator, 'session');
		$this->validator->setRules($this->rules);
	}

	public function renewal($id, $online = 1)
	{
		$this->edit(
			[
				SESS_PRIMARY_KEY => $id,
				SESS_ONLINE => $online,
				SESS_RENEWAL => date("Y-m-d H:i:s")
			],
			[
				SESS_PRIMARY_KEY => $id
			]
		);
	}

	public function addSession($id, $online = 1)
	{
		$this->add(
			[
				SESS_PRIMARY_KEY => $id,
				SESS_ONLINE => $online,
				SESS_RENEWAL => date("Y-m-d H:i:s")
			]
		);
	}
}