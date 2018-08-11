<?php

namespace controller;

class ArticleController extends BaseController
{
	protected $title;
	protected $content;

	public function __construct()
	{
		parent::__construct();
		$this->title = '';
		$this->content = '';
	}

	public function indexAction()
	{	
		$this->frame_content = $this->build('home',
			[
				'articles' => $this->articles
			]);
	}

	public function articleAction($id)
	{
		if($id === null) {
			return $this->error404Action();
		}

		$article = $this->ArticleModel->getByValue(ARTICLE_PRIMARY_KEY, $id);

		if(!$article) {
			return $this->error404Action();
		}

		$user = $this->UserModel->getByValue(USER_PRIMARY_KEY, $article['id_user']);

		$this->frame_content = $this->build('article',
			[
				'title' => $article[ARTICLE_TITLE],
				'content' => $article[ARTICLE_CONTENT],
				'data_add' => $article[ARTICLE_DATE],
				'user_name' => $user[USER_LOGIN],
			]);
	}

	public function addAction()
	{
		if( $this->request->isPost() ) {
			$this->title = $this->request->post('name');
			$this->content = $this->request->post('content');

			if( $this->ArticleModel->existsValue(ARTICLE_TITLE, $this->title) ) {
				$this->errors[ARTICLE_TITLE][] = sprintf('Error: %s with such a title already exist', ARTICLE_TITLE);
			} else {
				$response = $this->ArticleModel->add(
					[
						ARTICLE_TITLE => $this->request->post('name'),
						ARTICLE_CONTENT => $this->request->post('content'),
						ARTICLE_USER_ID => $this->current_id_user
					]
				);

				if(!is_array($response)) {
					$newId = $this->db->lastInsertId();
					header("Location: /article/$newId");
					die;
				}

				$this->errors = $response;
			}
		}

		$this->frame_content = $this->build('add',
			[
				'name' => $this->title,
				'content' => $this->content,
				'errors' => $this->errors
			]);
	}

	public function editAction($id_news)
	{
		if( $this->request->get('delete') !== null ) {
			$this->ArticleModel->deleteByValue(ARTICLE_PRIMARY_KEY,$id_news);
			header('Location: /');
			die;
		}

		$article = $this->ArticleModel->getByValue(ARTICLE_PRIMARY_KEY, $id_news);

		if( !is_array($article) ) {
			return $this->error404Action();
		}

		if( $this->request->isPost() ) {
			$this->title = $this->request->post('name');
			$this->content = $this->request->post('content');

			if( $article[ARTICLE_TITLE] !== $this->title && $this->ArticleModel->existsValue(ARTICLE_TITLE, $this->title) ) {
				$this->errors[ARTICLE_TITLE][] = sprintf('Error: %s with such a title already exist', ARTICLE_TITLE);
			} else {
				$response = $this->ArticleModel->edit(
					[
						ARTICLE_TITLE => $this->title,
						ARTICLE_CONTENT => $this->content
					],
					[
						ARTICLE_PRIMARY_KEY => $id_news
					]
				);

				if(!is_array($response)) {
					header('Location: /');
					die;
				}

				$this->errors = $response;
			}
		} else {
			$this->title = $article[ARTICLE_TITLE];
			$this->content = $article[ARTICLE_CONTENT];
		}

		$this->frame_content = $this->build('edit',
			[
				'name' => $this->title,
				'content' => $this->content,
				'errors' => $this->errors
			]);
	}

	// protected function check($id_news = null)
	// {
		// $saveChange = false;
		// $currentTitle = null;

		// if($id_news !== null) {
		// 	$article = $this->ArticleModel->getByValue(ARTICLE_PRIMARY_KEY, $id_news);
		// 	$currentTitle = $article['name'];
		// }

		// if(count($_POST) > 0) {
		// 	$this->title = trim($_POST['name']);
		// 	$this->content = trim($_POST['content']);

		// 	if($currentTitle !== $this->title) {
		// 		$this->errors = $this->CheckArticle->entry($this->content, $this->title);
		// 	} else {
		// 		$this->errors = $this->CheckArticle->entry($this->content);
		// 	}

		// 	if( empty($this->errors) ) {
		// 		$saveChange = true;
		// 	}

		// } else {
		// 	if($currentTitle !== null) {
		// 		$this->title = $article['name'];
		// 		$this->content = $article['content'];
		// 	} else {
		// 		$this->title = '';
		// 		$this->content = '';
		// 	}
		// }

		// return $saveChange;
	// }
}