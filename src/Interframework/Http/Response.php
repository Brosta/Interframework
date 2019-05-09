<?php

namespace Brosta\Http;

use GuzzleHttp\Psr7\Response as GuzzleHttpResponse;

class Response
{

	protected $response;

	public function __construct() {
		$this->response = new GuzzleHttpResponse();
	}

	public function getStatusCode() {
		return $this->response->getStatusCode();
	}

	public function getProtocolVersion() {
		return $this->response->getProtocolVersion();
	}


}