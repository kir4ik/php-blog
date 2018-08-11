<?php

namespace models;

use core\DBDriver;
use core\Validator;

abstract class BaseModel
{
	protected $driverDB;
	protected $table;
	public $validator;

	public function __construct(DBDriver $driverDB, Validator $validator, $table)
	{
		$this->driverDB = $driverDB;
		$this->table = $table;
		$this->validator = $validator;
	}

	public function getAll()
	{
		return $this->driverDB->select($this->table);
	}

	public function existsValue($colName, $value)
	{
		$response = $this->getByValue($colName, $value);

		return !empty($response);
	}

	public function getByValue($colName, $value, $fetch = 'one')
	{
		return $this->driverDB->select( $this->table, [$colName => $value], $fetch );
	}

	public function add(array $params, $whithCheck = true)
	{
		if (!$whithCheck) {
			return $this->driverDB->insert($this->table, $params);
		}
		
		$this->validator->execute($params);

		if($this->validator->success) {
			return $this->driverDB->insert($this->table, $this->validator->clean);
		} else {
			return $this->validator->errors;
		}
	}

	public function edit(array $params, array $where)
	{
		$this->validator->execute($params);
		if($this->validator->success) {
			return $this->driverDB->update($this->table, $this->validator->clean, $where);
		} else {
			return $this->validator->errors;
		}
	}

	public function deleteByValue($colName, $value)
	{
		return $this->driverDB->delete( $this->table, [$colName => $value] );
	}

	public function shortenStr($str, $max_length = 33)
	{
		if(mb_strlen($str, "UTF-8") > $max_length) {
			return $short_str = mb_substr($str, 0, ($max_length - 3), "UTF-8") . '...'; 
		}
		return $str;
	}
}
