<?php

class Diamesolavitis
{

	public $key;

	public static $rebornes;
	public static $instance;

	public function __construct($key = null) {
		if(!self::$instance) {
			require(__DIR__.'/Signal.php');
			self::$rebornes = $this;
			self::$instance = new Signal;
			$this->start($key);
		} else {
			$this->key = $key;
		}
	}

	public function __get($key) {
		return $this->__call('get', [$key]);
	}

	public function __set($key, $value) {
		return $this->__call('set', [$key, $value]);
	}

	public function __unset($key) {
		return $this->__call('delete', [$key]);
	}

	public function __call($method, $arguments) {
		return self::$instance->bridge($this, $method, $arguments);
	}

	public static function __callStatic($method, $arguments) {
		return self::$instance->bridge(self::$rebornes, $method, $arguments);
	}

}

$path = realpath(__DIR__.'/../');

$document = new Diamesolavitis($path);

