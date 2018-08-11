<?php

namespace core;

class Request
{
	const ACT_GET = 'get';
	const ACT_SET = 'set';
	const ACT_UNSET = 'unset';

	const METHOD_POST = 'POST';
	const METHOD_GET = 'GET';

	private $get;
	private $post;
	private $server;
	private $cookie;
	private $file;
	private $session;

	public function __construct(&$get, &$post, &$server, &$cookie, &$file, &$session)
	{
		$this->get 			= &$get;
		$this->post 		= &$post;
		$this->server 	= &$server;
		$this->cookie 	= &$cookie;
		$this->file 		= &$file;
		$this->session 	= &$session;
	}

	public function get($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('get', $param, $act);
	}

	public function post($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('post', $param, $act);
	}

	public function server($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('server', $param, $act);
	}

	public function cookie($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('cookie', $param, $act);
	}

	public function file($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('file', $param, $act);
	}

	public function session($param = null, $act = self::ACT_GET)
	{
		return $this->handlerArr('session', $param, $act);
	}

	private function handlerArr($arrName, $params, $act)
	{
		switch($act) {
			case self::ACT_GET:
				if( is_array($params) ) {
					throw new Exception("Error handlerArr => $params can not be array", 50);
					break;
				}
				if ($params === null) {
					return $this->$arrName;
				} elseif( isset($this->$arrName[$params]) ) {
					return $this->$arrName[$params];
				}
				return null;

			case self::ACT_SET:
				if( is_array($params) && !empty($params)) {
					foreach ($params as $key => $value) {
						$this->$arrName[$key] = $value;
					}
				} else {
					throw new Exception("Error handlerArr => incorrect $params", 50);
				}
			break;

			case self::ACT_UNSET:
				if( is_array($params) && !empty($params) ) {
					foreach ($params as $key) {
						unset($this->$arrName[$key]);
					}
				} elseif( !is_array($params) ) {
					unset($this->$arrName[$params]);
				} elseif($params === null) {
					unset($this->$arrName);
				} else {
					throw new Exception("Error handlerArr => incorrect $params", 50);
				}
			break;

			default:
			throw new Exception("Error handlerArr => incorrect $act", 50);
		}
	}

	public function isPost()
	{
		return $this->server['REQUEST_METHOD'] === self::METHOD_POST;
	}
}