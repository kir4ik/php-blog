<?php

namespace core;

class Validator
{
	const TYPE_INTEGER = 'integer';
	const TYPE_STRING = 'string';

	public $success = false;
	public $errors = [];
	public $clean = [];
	public $rules;

	public function execute(array $fields)
	{
		if (!$this->rules) {
			throw new \Exception("Error execute rules", 50);
		}

		foreach ($this->rules as $name => $rules) {

			// field require
			if( !isset($fields[$name]) ) {
				if( isset($rules['require']) && $rules['require'] ) {
					$this->errors[$name][] = sprintf('Error: field %s REQUIRE', $name);
				}
				continue;
			}

			// field blank
			if( isset($rules['not_blank']) && $rules['not_blank'] && $this->isBlank($fields[$name]) ) {
				$this->errors[$name][] = sprintf('Error: field %s BLANK', $name);
			}

			// field type
			if( isset($rules['type']) && !$this->isTypeMatch($fields[$name], $rules['type']) ) {
				$this->errors[$name][] = sprintf('Error: field %s has an incorrect TYPE data', $name);
			}

			// field length
			if( isset($rules['length']) && !$this->isLengthMatch($fields[$name], $rules['length']) ) {
					$this->errors[$name][] = sprintf('Error: field %s has an incorrect LENGTH', $name);
			}

			if( empty($this->errors[$name]) ) {
				if( isset($rules['type']) && $rules['type'] === self::TYPE_INTEGER ) {
					$this->clean[$name] = (int)$fields[$name];
				} elseif( isset($rules['type']) && $rules['type'] === self::TYPE_STRING) {
					$this->clean[$name] = trim( htmlspecialchars($fields[$name]) );
				} else {
					$this->clean[$name] = $fields[$name];
				}
			}

		}

		if( empty($this->errors) ) {
			$this->success = true;
		}

	}

	public function setRules(array $rules)
	{
		$this->rules = $rules;
	}

	public function isBlank($field)
	{
		$field = trim($field);
		return $field === null || $field === '';
	}

	public function isTypeMatch($field, $type)
	{
		switch($type) {
			case self::TYPE_STRING:
				return is_string($field);
			case self::TYPE_INTEGER:
				return gettype($field) === self::TYPE_INTEGER || ctype_digit($field);
				default:
				throw new \Exception("Error execute isTypeMatch => $type", 50);
		}
	}

	public function isLengthMatch($field, $length)
	{
		$isArray = is_array($length);
		$isInt = is_int($length);

		if($isArray) {
			$min = isset($length[0]) ? $length[0] : false;
			$max = isset($length[1]) ? $length[1] : false;
		} elseif($isInt) {
			$min = false;
			$max = $length;
		} else {
			throw new \Exception("Error execute isLengthMatch => $length", 50);
		}

		if( $isArray && (!$min || !$max) ) {
			throw new \Exception("Error execute isLengthMatch => array $length", 50);
			
		} elseif( $isInt && !$max ) {
			throw new \Exception("Error execute isLengthMatch => int $length", 50);
		}

		$maxIsMatch = $max ? $this->isLengthMaxMatch($field, $max) : false;
		$minIsMatch = $min ? $this->isLengthMinMatch($field, $min) : false;

		return $isArray ? $maxIsMatch && $minIsMatch : $maxIsMatch;
	}

	public function isLengthMaxMatch($field, $length)
	{
		return mb_strlen($field) > $length === false;
	}

	public function isLengthMinMatch($field, $length)
	{
		return mb_strlen($field) < $length === false;
	}
}