<?php 

require_once 'core/constDB.php';

use controller\ArticleController;
use controller\UserController;

function __autoload($className) {
	require_once __DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
}

session_start();

$uri = core\Uri::getUri();

$view = isset($uri[0]) && $uri[0] !== '' ? $uri[0] : 'index';
$id = isset($uri[1]) && ctype_digit($uri[1]) ? $uri[1] : null;

switch ($view) {
	case 'index':
		$controller = 'Article';
		break;

	case 'article':
		$controller = 'Article';
		break;

	case 'add':
		$controller = 'Article';
		break;

	case 'edit':
		$controller = 'Article';
		$id = isset($_GET['id']) && ctype_digit($_GET['id']) ? $_GET['id'] : null;
		break;

	case 'login':
		$controller = 'User';
		break;

	case 'sign-up':
		$controller = 'User';
		break;

	case 'greeting':
		$controller = 'User';
		break;

	case 'handler':
		$controller = 'User';
		break;


	default:
		header("HTTP/1.0 404 Not Found");
		$view = 'error404';
		$controller = 'Article';
}

$controller = sprintf('controller\%sController', $controller);

$view = explode("-", $view);
for ($i = 1; $i < count($view); $i++) {
	$view[$i] = ucfirst($view[$i]);
}
$view = implode("", $view);

$view = sprintf('%sAction', $view);

$controller = new $controller();

try {
	$controller->$view($id);
} catch(\Exception $e) {
	$view = sprintf('%sAction', 'internalError');
	$controller->$view($e->getMessage());
}

$controller->render();