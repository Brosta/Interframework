<?php

namespace Brosta;

use Closure;
use Brosta\App;

class Collection
{
	public $length = 0;
	public $current = 0;
	private $prefix;

	public function __construct($prefix = 'hdyeolfjvmkdyegh') {
		$this->prefix = $prefix;
	}

	public function set($key, $value = null, $absolute = 0) {
		return App::_set($this->prefix.'.'.$key, $value, $absolute);
	}

    public function isset($key) {
		return App::_isset($this->prefix.'.'.$key);
	}

    public function delete($key) {
		return App::_delete($this->prefix.'.'.$key);
	}

	public function push($key, $value) {
		return App::_push($this->prefix.'.'.$key, $value);
	}

	public function get($key, $default = null) {
		return App::_get($this->prefix.'.'.$key, $default);
	}

	public function count($key) {
		return App::_count($this->get($key));
	}

	public function find($key, $array = null) {
		return App::_find($this->prefix.'.'.$key, $array);
	}

	public function __call($method, $arguments) {
		return call_user_func($this->get('functions.'.$method), $arguments);
	}

}
