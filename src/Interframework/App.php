<?php

namespace Brosta;

class App
{

	private $instant;
	private static $instance;
	private static $functions;

	public function __construct($object = null) {
		if(!$object) {
			if(!self::$instance) {
				$this->load_functions();
				self::$instance = new Signal;
			}
		} else {
			$this->instant = $object;
		}
	}

	public static function call_function($method, $arguments) {
		return call_user_func_array(self::$functions[$method], $arguments);
	}

	public function load_functions() {
		self::$functions = [
			'_pos' => function($haystack, $needle) {
				return mb_strpos($haystack, $needle);
			},
			'_preg_replace' => function($regex, $expresion, $string) {
				return preg_replace($regex, $expresion, $string);
			},
			'_remove_spaces' => function(string $str) {
				return preg_replace('/\s+/', '', $str);
			},
			'_replace_spaces_with_one' => function($str) {
				return preg_replace('/\s+/', ' ', $str);
			},
			'_replace' => function($search, $replace, $subject) {
				return str_replace($search, $replace, $subject);
			},
			'_fwrite' => function($file, $contents, $lock = false) {
				return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
			},
			'_substr' => function(string $string, $start = 0, $length = null) {
				if(is_null($length)) {
					return mb_substr($string, $start);
				} else {
					return mb_substr($string, $start, $length);
				}
			},
			'_is_array' => function($obj) {
				return is_array($obj) ? 1 : 0;
			},
			'_is_dir' => function($obj) {
				return is_dir($obj);
			},
			'_is_empty' => function($obj) {
				return empty($obj);
			},
			'_get_file_contents' => function($file, $lock = false) {
				return file_get_contents($file, $lock);
			},
			'_key_in_array' => function($key, $array) {
				return array_key_exists($key, $array);
			},
			'_get_file_contents' => function($file, $lock = false) {
				return file_get_contents($file, $lock);
			},
			'_exit' => function() {
				exit;
			},
			'_unlink' => function($file) {
				return @unlink($file);
			},
			'_count' => function($array) {
				return count($array);
			},
			'_basename' => function($path) {
				return basename($path);
			},
			'_array_zero' => function($array) {
				return array_values($array);
			},
			'_implode' => function($sep, $array) {
				return implode($sep, $array);
			},
			'_explode' => function($sep, $string) {
				return explode($sep, $string);
			},
			'_in_array' => function($key, $array) {
				return in_array($key, $array);
			},
			'_file_append' => function($file, $contents) {
				return file_put_contents($file, $contents, FILE_APPEND);
			},
			'_make_dir_force' => function($file, $mode = 493, $recursive = true) {
				return @mkdir($file, $mode, $recursive);
			},
			'_print' => function($str) {
				return print_r($str);
			},
			'_json_encode' => function($data) {
				return json_encode($data);
			},
			'_json_decode' => function($data) {
				return json_decode($data);
			},
			'_copy_file' => function($path, $target) {
				return copy($path, $target);
			},
			'_length' => function($str) {
				return mb_strlen($str);
			},
			'_is_object' => function($element) {
				return is_object($element);
			},
			'_is_null' => function($element) {
				return is_null($element);
			},
			'_is_string' => function($element) {
				return is_string($element);
			},
			'_lower' => function($str) {
				return strtolower($str);
			},
			'_upper' => function($str) {
				return strtoupper($str);
			},
			'_file_extention' => function($file) {
				return pathinfo($file, PATHINFO_EXTENSION);
			},
			'_file_exists' => function($file) {
				return file_exists($file);
			},
			'_require_local' => function($file, $data = []) {
				$html = new self($this->instant);
				return require($file);
			},
			'_trim' => function($str, $mask = null) {
				if(is_null($mask)) {
					return trim($str);
				} else {
					return trim($str, $mask);
				}
			},
			'_rtrim' => function($source, $sym = null) {
				if(is_null($sym)) {
		    		return rtrim($source);
		    	} else {
					return rtrim($source, $sym);
		    	}
			},
			'_ltrim' => function($source, $sym = null) {
				if(is_null($sym)) {
		    		return ltrim($source);
		    	} else {
					return ltrim($source, $sym);
		    	}
			},
		];
	}

	public function __call($method, $arguments) {
		$method = '_'.ltrim($method, '_');
		if(!$this->instant) {
			$this->instant = self::$instance;
		}
		$results = call_user_func_array([$this->instant, $method], $arguments);
		if(is_object($results) && $results instanceof $this->instant) {
			return $this;
		}
		return $results;
	}

	public static function __callStatic($method, $arguments) {
		$method = '_'.ltrim($method, '_');
		return call_user_func_array([self::$instance, $method], $arguments);
	}


}