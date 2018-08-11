<?php

namespace core;

class Uri
{
	public static function getUri()
	{
		$uri = explode('/', $_GET['uri']);

		return self::validUri($uri);
	}

	private function validUri($uri)
	{
		$end = count($uri) - 1;

		if($uri[$end] === '') {
			unset($uri[$end]);
		}

		return empty($uri) ? false : $uri;
	}
}

