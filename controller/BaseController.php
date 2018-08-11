<?php

namespace controller;

use core\DBDriver;
use core\Request;
use core\Validator;
use models\ArticleModel;
use models\UserModel;
use models\SessionModel;
use models\AuthModel;

class BaseController
{
	protected $db;
	protected $driverDB;
	protected $request;
	protected $validator;
	protected $ArticleModel;
	protected $UserModel;
	protected $SessionModel;
	protected $AuthModel;
	protected $head_title;
	protected $frame_content;
	protected $articles;
	protected $current_user;
	protected $current_id_user;
	protected $current_user_name;
	protected $errors;

	public function __construct()
	{
		$this->db 								= \core\DBConnector::getConnect();
		$this->driverDB 					= new DBDriver($this->db);
		$this->request 						= new Request($_GET, $_POST, $_SERVER, $_COOKIE, $_FILES, $_SESSION);
		$this->ArticleModel 			= new ArticleModel($this->driverDB, new Validator());
		$this->UserModel 					= new UserModel($this->driverDB, new Validator());
		$this->SessionModel 			= new SessionModel($this->driverDB, new Validator());
		$this->AuthModel 					= new AuthModel($this->UserModel, $this->SessionModel, $this->request);
		$this->head_title 				= 'PHP-2 | MVC';
		$this->frame_content 			= '';
		$this->articles 					= $this->ArticleModel->getAll();
		$this->current_id_user 		= $this->request->session('id_user');
		$this->current_user_name 	= 'Войти';
		$this->errors 						= [];
	}

	public function __call($name, $params)
	{
		try {
			throw new \Exception("Error call undefined function $name or with incorect params", 50);
		} catch(\Exception $e) {
			$this->internalErrorAction($e->getMessage());
			$this->render();
			die;
		}
	}

	public function render()
	{
		$this->AuthModel->setAuth();
		$this->current_user = $this->AuthModel->get_current_user();

		if($this->current_user) {
			$this->current_user_name = sprintf('%s | Выйти', $this->current_user[USER_LOGIN]);
			$this->current_id_user = $this->current_user[USER_PRIMARY_KEY];
		}

		foreach ($this->articles as $value) {
			$artNames[ $value[ARTICLE_PRIMARY_KEY] ]				= $value[ARTICLE_TITLE];
			$shortArtNames[ $value[ARTICLE_PRIMARY_KEY] ] 	= $this->ArticleModel->shortenStr($value[ARTICLE_TITLE], 25);
		}

		echo $this->build('BaseTemplate',
			[
				'title' => $this->head_title,
				'content' => $this->frame_content,
				'artNames' => $artNames,
				'shortArtNames' => $shortArtNames,
				'current_user_name' => $this->current_user_name
			]);
	}

	protected function build($template, array $params = [])
	{
		ob_start();
		extract($params);
		include_once __DIR__ . '/../view/' . $template . '.html.php';

		return ob_get_clean();
	}

	public function error404Action()
	{
		$this->frame_content = $this->build('error404');
	}

	public function handlerAction()
	{
		$this->AuthModel->cleanAuth();

		header('location: /login');
		die;
	}

	public function internalErrorAction($message)
	{
		$this->frame_content = $this->build('internalError',
			[
				'message' => $message
			]
		);
	}
}