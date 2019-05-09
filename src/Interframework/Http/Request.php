<?php

namespace Brosta\Http;

use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request as GuzzleHttpRequest;
use GuzzleHttp\Psr7\Response;

class Request
{

	protected $request;

	public function __construct() {
		$this->request = new GuzzleHttpRequest($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	}

	public function uri() {
		return $this->request->getUri();
	}

	public function path() {
		return $this->request->getUri()->getPath();
	}


}