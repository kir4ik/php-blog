<?php

namespace core;

class DBDriver
{
	const FETCH_ALL = 'all';
	const FETCH_ONE = 'one';

	private $pdo;
	private $sqlErrors;

	public function __construct(\PDO $pdo)
	{
		$this->pdo = $pdo;
		$this->sqlErrors = [];
	}

	public function select($table, array $where = [], $fetch = self::FETCH_ALL)
	{
		if( empty($where) ) {
			$sql = sprintf('SELECT * FROM %s', $table);
		}
		else {
			$condition = $this->getStrColMask($where);
			$sql = sprintf('SELECT * FROM %s WHERE %s', $table, $condition);
		}

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($where);

		$this->checkError($stmt, $sql);

		return $fetch === self::FETCH_ALL ? $stmt->fetchAll() : $stmt->fetch();
	}

	public function insert($table, array $params)
	{
		$columns = sprintf('%s', implode(', ', array_keys($params)));
		$masks = sprintf(':%s', implode(', :', array_keys($params)));

		$sql = sprintf('INSERT INTO %s(%s) VALUES(%s)', $table, $columns, $masks);

		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);

		$this->checkError($stmt, $sql);

		return $this->pdo->lastInsertId();
	}

	public function update($table, array $params, array $where = [])
	{
		$success = false;

		$strSet = $this->getStrColMask($params);

		if( empty($where) ) {
			$sql = sprintf('UPDATE %s SET %s', $table, $strSet);
		} 
		else {
			$condition = $this->getStrColMask($where);
			$sql = sprintf('UPDATE %s SET %s WHERE %s', $table, $strSet, $condition);
			
			foreach ($where as $key => $value) {
				$params[$key] = $value;
			}
		}

		$stmt = $this->pdo->prepare($sql);
		$success = $stmt->execute($params);

		$this->checkError($stmt, $sql);

		return $success;
	}

	public function delete($table, array $where)
	{
		$success = false;

		$condition = $this->getStrColMask($where);
		$sql = sprintf('DELETE FROM %s WHERE %s', $table, $condition);

		$stmt = $this->pdo->prepare($sql);
		$success = $stmt->execute($where);

		$this->checkError($stmt, $sql);

		return $success;
	}

	private function isError($stmt, $sql)
	{
		if($stmt === false) {
			$this->sqlErrors[] = "Error sql: '$sql'";
			return true;
		}

		$info = $stmt->errorInfo();

		if($info[0] != \PDO::ERR_NONE ) {
			$this->sqlErrors[] = $info[2];
			$this->sqlErrors[] = "sql : '$sql'";
			return true;
		}

		return false;
	}

	private function checkError($stmt, $sql)
	{
		if( $this->isError($stmt, $sql) ) {
			$this->showSqlErrors();
			die;
		}
	}

	private function showSqlErrors()
	{
		echo "<pre>";
		print_r($this->sqlErrors);
		echo "</pre>";
	}

	private function getStrColMask(array $params)
	{
		$res = '';
		foreach ($params as $key => $value) {
			$res .= "$key=:$key, ";
		}

		return $res = substr($res, 0, -2);
	}
}