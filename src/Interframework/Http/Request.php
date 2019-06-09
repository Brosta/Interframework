<?php

namespace Brosta\Http;

use Brosta\App;

class Request
{

	public $all = [];

	public $get = [];

	public $post = [];

	public $files = [];

	public $cookie = [];

	public $server = [];

	public $contents = '';

	public $extra = [];

	public $local_disk = '';

	public function __construct($signal) {
		$this->_normalize_request($signal);
	}

	public function _normalize_request($signal) {

		if(!os_is_empty($signal['post'])) {
			foreach($signal['post'] as $key => $value) {
				$key = os_lower($key);
				$this->post[$key] = $value;
			};
		}

		$this->all = $this->post;

		if(!os_is_empty($signal['get'])) {
			foreach($signal['get'] as $key => $value) {
				$key = os_lower($key);
				$this->get[$key] = $value;
				if(!array_key_exists($key, $this->all)) {
					$this->all[$key] = $value;
				}
			};
		}

		if(!os_is_empty($signal['server'])) {
			foreach($signal['server'] as $key => $value) {
				$key = os_lower($key);
				$this->server[$key] = $value;
			};
			if(os_key_in_array('request_uri', $this->server)) {
				$this->server['request_uri'] = os_trim($this->server['request_uri']);

				if(os_substr($this->server['request_uri'], 0, 1) == os_url_s()) {
					if($this->server['request_uri'] !== os_url_s()) {
						$parsed = parse_url($this->server['request_uri']);
					} else {
						$parsed['path'] = '';
						$parsed['query'] = '';
					}

					$query_string = '';
		        	if(isset($parsed['query'])) {
			            parse_str(html_entity_decode($parsed['query']), $query);
			            if(!empty($this->get)) {
			                $query_string = http_build_query(array_replace($query, $this->get), '', '&');
			            } else {
			                $query_string = $parsed['query'];
			            }
			        } else {
			        	if(!empty($this->get)) {
			        		$query_string = http_build_query($this->get, '', '&');
			        	}
			        }

					$this->server['request_path'] = os_to_url_s($parsed['path']);
			        $this->server['query_string'] = $query_string;
			        $this->server['request_uri'] = $parsed['path'];
					if($query_string) {
						$this->server['request_uri'].='?'.$query_string;
					}
				} else {
					$this->fail('Fattal error: Incorrect [ REQUEST_URI ] must begin with [ '.os_url_s().' ]');
				}
			} else {
				$this->fail('Fattal error: [ REQUEST_URI ] is missing.');
			}
		} else {
			$this->fail('Fattal error: Your request does not have the necessary information');
		}

		if(!os_is_empty($signal['files'])) {
			$this->files = $signal['files'];
		}

		if(!os_is_empty($signal['cookie'])) {
			$this->cookie = $signal['cookie'];
		}

		if(!os_is_empty($signal['extra'])) {
			$this->extra = $signal['extra'];
		}

	}


	public function _path() {
		return $this->server['request_path'];
	}

	public function _method($m = null) {
		return $m ? os_lower($m) == os_lower($this->server['request_method']) : $this->server['request_method'];
	}

	public function _isAjax() {
		return isset($this->server['http_x_requested_with']) && $this->server['http_x_requested_with'] == 'XMLHttpRequest';
	}

	public function _local_disk() {
		return $this->local_disk;
	}

	public function _is_secure() {
		return isset($this->server['https']) && $this->server['https'] == 'on';
	}

	public function _scheme() {
		return $this->_is_secure() ? 'https' : 'http';
	}

	public function _host() {
		return $this->server['http_host'];
	}

	public function _url($extend = '', $args = [], $replace = []) {
		$args = array_merge($args, $replace);
		$url = $this->_scheme();
		$url.= '://'.$this->_host();
		$url.= $extend ? os_url_s().os_to_url_s($extend) : '';
    	$url.= !os_is_empty($args) ? '?'.os_http_build_query($args) : '';
		return $url;
	}

	public function _is_gp($key) {
		return isset($this->all[$key]);
    }

	public function _gp($key) {
		return isset($this->all[$key]) ? $this->all[$key] : UNDEFINED;
    }

	public function _is_g($key) {
		return isset($this->get[$key]);
    }

	public function _g($key) {
		return isset($this->get[$key]) ? $this->get[$key] : UNDEFINED;
    }

	public function _is_p($key) {
		return isset($this->post[$key]);
    }

	public function _p($key) {
		return isset($this->post[$key]) ? $this->post[$key] : UNDEFINED;
    }

}