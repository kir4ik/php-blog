<?php

namespace models;

class CheckArticle
{
	private $errors = [];
	private $colName; // название колонки с именем полюьзователя
	private $ArticleModel;

	public function __construct(object $model)
	{
		$this->ArticleModel = $model;
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

	public function title($title)
	{
		if($title === '') {
			return $this->errors[] = 'Title пустой';
		} 
		elseif($this->ArticleModel->existsValue(ARTICLE_TITLE, $title)) {
			return $this->errors[] = 'этот title уже занят';
		}

		return $this->errors;
	}

	public function content($content)
	{
		if($content == '') {
			return $this->errors[] = 'Контент не заполнен';
		}

		return $this->errors;
	}

	public function entry($content, $title = null)
	{
		if($title !== null) {
			$this->title($title);
		}
		$this->content($content);

		return $this->errors;
	}
}