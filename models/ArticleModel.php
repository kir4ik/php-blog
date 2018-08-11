<?php 

namespace models;

use core\DBDriver;
use core\Validator;

class ArticleModel extends BaseModel
{
	private $rules = [
		ARTICLE_PRIMARY_KEY => [
			'require' => false,
			'type' => 'integer',
			'length' => 11,
			'primary' => true
		],

		ARTICLE_TITLE => [
			'require' => true,
			'not_blank' => true,
			'type' => 'string',
			'length' => [5, 75],
		],

		ARTICLE_CONTENT => [
			'require' => true,
			'not_blank' => true,
			'type' => 'string',
			'length' => 100
		],

		ARTICLE_USER_ID => [
			'require' => false,
			'type' => 'integer',
			'length' => 11,
			'primary' => false
		]
	];

	public function __construct(DBDriver $driverDB, Validator $validator)
	{
		parent::__construct($driverDB, $validator, 'articles');
		$this->validator->setRules($this->rules);
	}
}