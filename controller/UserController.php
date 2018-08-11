<?php

namespace controller;

use models\CheckUser;

class UserController extends BaseController
{
	protected $CheckUser;

	public function __construct()
	{
		parent::__construct();
		$this->CheckUser = new CheckUser($this->UserModel);
	}

	public function loginAction()
	{
		if( $this->AuthModel->getStatusAuth() ) {
			header('Location: /handler');
			die;
		}

		$login = '';
		$password = '';

		if( $this->request->isPost() ) {
			$login = $this->request->post('login');
			$password = $this->request->post('password');

			$this->UserModel->validator->execute(
				[
					USER_LOGIN => $login,
					USER_PASSWORD => $password
				]
			);

			if($this->UserModel->validator->success) {
				$login = $this->UserModel->validator->clean[USER_LOGIN];
				$password = $this->UserModel->validator->clean[USER_PASSWORD];
				$user = $this->UserModel->getByValue(USER_LOGIN, $login);

				if( empty($user) ) {
					$this->errors[USER_LOGIN][] = 'Неверный логин';
				}
				elseif($user[USER_PASSWORD] !== hash('md5', $password . SALT)) {
					$this->errors[USER_PASSWORD][] = 'Неверный пароль';
				}

				if( empty($this->errors) ) {
					$this->request->session( ['id_user' => $user[USER_PRIMARY_KEY]], $this->request::ACT_SET );
					
					if( $this->request->post('remember') ) {
						$this->AuthModel->rememberAuth("id_user", $user[USER_PRIMARY_KEY]);
						$this->AuthModel->rememberAuth("user_name", hash('md5', $login));
					}

					header('Location: /');
					die;
				}
			} else {
				$this->errors = $this->UserModel->validator->errors;
			}

		}

		$this->frame_content = $this->build('login',
			[
				'login' => $login,
				'password' => $password,
				'errors' => $this->errors
			]);
	}

	public function signUpAction()
	{
		if( $this->AuthModel->getStatusAuth() ) {
			header('Location: /');
			die;
		}

		$login = '';
		$password = '';

		if( $this->request->isPost() ) {
			$login = $this->request->post('login');
			$password = $this->request->post('password');

			$this->UserModel->validator->execute(
				[
					USER_LOGIN => $login,
					USER_PASSWORD => $password
				]
			);

			if($this->UserModel->validator->success) {
				$login = $this->UserModel->validator->clean[USER_LOGIN];
				$password = $this->UserModel->validator->clean[USER_PASSWORD];
				$user = $this->UserModel->getByValue(USER_LOGIN, $login);

				if( !empty($user) ) {
					$this->errors[USER_LOGIN][] = 'Этот логин уже занят, придумай другой';
				}

				if( empty($this->errors) ) {
					// в табл users добавить пользователя
					$id = $this->UserModel->add(
						[
							USER_LOGIN => $login,
							USER_PASSWORD => hash('md5', $password . SALT)
						],
						false
					);
					// в табл session добавить сессию пользователя
					$this->SessionModel->addSession($id);

					// сразу авторизуем и приветствуем
					$this->request->session( ['id_user' => $id], $this->request::ACT_SET );
					header('Location: /greeting');
					die;
				}
			} else {
				$this->errors = $this->UserModel->validator->errors;
			}
		}

		$this->frame_content = $this->build('sign-up',
			[
				'login' => $login,
				'password' => $password,
				'errors' => $this->errors
			]);
	}
}