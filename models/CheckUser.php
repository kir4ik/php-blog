<?php

namespace models;

class CheckUser
{
	private $errors = [];
	private $colName; // название колонки с именем полюьзователя
	private $UserModel;

	public function __construct(object $model)
	{
		$this->UserModel = $model;
	}

	private function isAuth()
	{
		if(isset($_SESSION['is_auth']) && $_SESSION['is_auth']) {
			return true;
		}
		// elseif(isset($_COOKIE['login']) && isset($_COOKIE['password'])) {
		// 	if($_COOKIE['login'] == 'admin' && $_COOKIE['password'] == hash('sha256', 'qwerty')) {
		// 		$_SESSION['is_auth'] = true;
		// 		$isAuth = true;
		// 	}
		// }

		return false;
	}

	public function newName($nickName)
	{
		if($nickName === '') {
			return $this->errors[] = 'Нужно придумать nick';
		} 
		elseif( $this->UserModel->getByValue(USER_LOGIN, $nickName) ) {
			return $this->errors[] = 'этот nick уже занят';
		}

		return $this->errors;
	}

	public function newPass($password)
	{
		if($password === '') {
			return $this->errors[] = 'Нужно придумать пароль';
		} 
		elseif(mb_strlen($password) < 6) {
			return $this->errors[] = 'Пароль должен содержать не менее 6 символов';
		}

		return $this->errors;
	}

	public function newData($nickName, $password)
	{
		$this->newName($nickName);
		$this->newPass($password);

		return $this->errors;
	}

	public function entrance($nickName, $password)
	{
		if($nickName !== '' && $password !== '') {
			$user = $this->UserModel->getByValue(USER_LOGIN, $nickName);

			if( empty($user) ) {
				$this->errors[] = 'Неверный логин';
			}
			elseif($user[USER_PASSWORD] !== $password) {
				$this->errors[] = 'Неверный пароль';
			}
		}
		else {
			$this->errors[] = 'Заполните все поля';
		}

		return $this->errors;
	}
}