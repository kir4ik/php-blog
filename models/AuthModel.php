<?php

namespace models;

use models\UserModel;
use models\SessionModel;
use core\Request;

class AuthModel
{
	private $UserModel;
	private $SessionModel;
	private $request;
	private $isAuth;
	private $current_user;

	public function __construct(UserModel $UserModel, SessionModel $SessionModel, Request $request)
	{
		$this->UserModel = $UserModel;
		$this->SessionModel = $SessionModel;
		$this->request = $request;
		$this->isAuth = false;
		$this->current_user = null;
	}

	public function setAuth()
	{
		if( $this->isSetCookie() ) {
			$this->isAuth = true;
			$this->request->session(['id_user' => $this->request->cookie('id_user')], $this->request::ACT_SET);
		} elseif( $this->request->session('id_user') !== null ) {
			$this->isAuth = true;
		}

		if($this->isAuth) {
			$this->SessionModel->renewal( $this->request->session('id_user') );
		}
	}

	public function getStatusAuth()
	{
		return ($this->request->session('id_user') !== null || $this->isSetCookie()) ? true : false;
	}

	public function isSetCookie()
	{
		if( $this->request->cookie('id_user') !== null &&
			$this->request->cookie('user_name') !== null
		) {
			$this->current_user = $this->UserModel->getByValue(USER_PRIMARY_KEY, $this->request->cookie('id_user')) ?? null;

			if( $this->current_user !== null && hash('md5', $this->current_user[USER_LOGIN]) === $this->request->cookie('user_name') ) {
				return true;
			}
		}

		return false;
	}

	public function get_current_user()
	{
		if ($this->current_user !== null) {
			return $this->current_user;
		} elseif ($this->isAuth) {
			return $this->UserModel->getByValue(USER_PRIMARY_KEY, $this->request->session('id_user'));
		}
		return false;
	}

	public function rememberAuth($name, $value)
	{
		$currentTime = explode('::', date('H::i'));
		$timeToEnd = ( (23 - $currentTime[0]) * 3600 ) + ( (60 - $currentTime[1]) * 60);	// time to the end day
		
		setcookie($name, $value, time() + $timeToEnd, '/');
	} 

	public function cleanAuth()
	{
		if( $this->request->cookie('id_user') !== null ) {
			setcookie('id_user', '', time() - 9999, '');
		}
		if( $this->request->cookie('user_name') !== null ) {
			setcookie('user_name', '', time() - 9999, '');
		}
		if( $this->request->session('id_user') !== null ) {
			$this->SessionModel->renewal($this->request->session('id_user'), 0);

			$this->request->session('id_user', $this->request::ACT_UNSET);
		}
	}
}