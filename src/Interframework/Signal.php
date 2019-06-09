<?php


namespace Brosta;

use Closure;
use stdClass;
use DateTime;
use DateTimeZone;
use ReflectionClass;
use ReflectionMethod;
use FilesystemIterator;
use Brosta\App;
use Brosta\init;
use Brosta\Http\Request;
use Brosta\Database;
use Brosta\Collection;
use Brosta\Growth;

class Signal {

	public $app;

	public $_stop = 0;

	public $_request;

	public $_memory = [
		'static' => [
			'inits' => [
				'request',
				'response',
			],
			'license_id' => 'GDFS-HSKA-OLEK-OWTD',
			'config' => [],
			'collection_type' => 'array',
			'on' => [],
			'doctype' => 'html',
			'bodyclass' => [],
			'tag' => [
				'text_lined' => 0
			],
			'prefixes' => [
				'data' => [
					'monitor' => 'data.monitor'
				]
			],
			'unclosed_tags' => 0,
			'contents_before' => 0,
			'start_code_space_level' => 4,
			'resources' => [
				'before' => [
					'css_require' => [],
					'js_require' => [],
					'css_auto_view' => [],
					'js_auto_view' => [],
					'css_dynamic' => [],
					'js_dynamic' => [],
					'scripts' => [],
					'meta' => [],
				],
				'after' => [
					'css_require' => [],
					'js_require' => [],
					'css_auto_view' => [],
					'js_auto_view' => [],
					'css_dynamic' => [],
					'js_dynamic' => [],
					'scripts' => [],
					'meta' => [],
				],
			],
		]
	];

	public function _construct($path = null, array $signal = []) {
		$this->_request = $this->_new('Brosta\Http\Request', [$signal]);
		$this->_reset();
		if($path) {
			$this->_set_path($path);
		}
		$this->_set('static.url_paths', $this->_explode($this->_url_s(), $this->_request->path()));
		$this->_conclude();
		return $this;
	}

	public function _new($name = null, $constructor = []) {

		if($name) {
			$class = $this->_class_separator_fix($name);
			if($this->_file_exists($this->_vendor_path($name))) {
				//
			}
			$instance = new $class(...$constructor);
			if($instance instanceof self) {
				$instance->_reset();
				$instance->_set('static', $this->_get('static'));
			}
			return new App($instance);
		}
	}

	public function _use_as($name) {
		if($name == 'editor') {
			$this->_set_text_lined(1);
			$this->_set_start_code_space_level(0);
			$this->_set_doc_type('php');
		}
	}

	public function _set_memory($key, $value) {
		return $this->_set('static.'.$key, $value);
	}

	public function _assets_url($url = '') {
		return $this->_url('assets/'.$this->_to_url_s($url));
	}

	public function _contains_in($haystack, $needle) {
		if($needle !== '' && $this->_pos($haystack, $needle) !== false) {
			return true;
		}
        return false;
	}

	public function _generate_app($haystack, $needle) {
		
	}

	public function _delete_file($files) {
		$success = true;
        foreach($this->_is_array($files) ? $files : [$files] as $file) {
            if(!$this->_unlink($file)) {
				$success = false;
			}
        }
        return $success;
	}

	public function _explode_dot($data) {
		return $this->_explode('.', $data);
	}

	public function _explode_lines($data) {
		return $this->_explode("\n", $data);
	}

	public function _find($array, $something = null, $start = 0) {
		if(!$start) {
    		$start = 1;
    		if($something === null) {
		    	if(_is_string($array)) {
		    		return $this->_get($array);
		    	}
    		}
    	}
    	if($this->_is_empty($array)) {
    		return $something;
    	}
		for($i=0;$i<$this->_count($array);$i++) {
			$key = $array[$i]; unset($array[$i]);
			$array = $this->_array_zero($array);
			if(!$this->_key_in_array($key, $something)) {
				return TIPOTA;
			}
			return $this->_find($array, $something[$key], $start);
		}
	}

	public function _first_in($haystack, $needle) {
		if($needle !== '' && $this->_pos($haystack, 0, $this->_length($needle)) === (string) $needle) {
			return true;
		}
        return false;
	}

	public function _include($file, $data = []) {
		$this->_set($this->_get('static.prefixes.data.monitor'), $data);
		$data = new Collection($this->_get('static.prefixes.data.monitor'));
		$file = $this->_template_path($file.'.php');
		return $this->_require_local($file, $data);
	}

	public function _isset($key) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		if($this->_key_in_array($base, $this->_memory)) {
			$count = $this->_count($keys);
			if($count > 0) {
				$results = $this->_find($keys, $this->_memory[$base]);
				if($results === TIPOTA) {
					return false;
				}
				return true;
			} else {
		   		if($this->_key_in_array($base, $this->_memory)) {
		   			return true;
		   		}
		   		return false;
			}
		}
		return false;
	}

	public function _last_in($haystack, $needle) {
		if($this->_substr($haystack, -$this->_length($needle)) === (string)$needle) {
			return true;
		}
        return false;
	}

	public function _push($key, $value) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($count > 0) {
			$data = $this->_find($keys, $this->_memory[$base]);
			$data[] = $value;
			$value = $data;
			$keys[$count] = $value;
			$data = $this->_array_sm($keys);
			$this->_memory[$base] = $this->_array_replace($this->_memory[$base], $data);
		} else {
			$this->_memory[$base][] = $value;
		}
		return $value;
	}

	public function _redirect($url) {
		$this->_header("Location: ".$this->_url($url));
	}

	public function _require($file, $position = 'require') {
		if($this->_file_exists($this->_assets_path($file))) {
			$url = $this->_assets_url($file);
			$ext = $this->_lower($this->_file_extention($url));
			if(!$this->_isset('settings.'.$ext.'_'.$position)) {
				if(!$this->_in_array($url, $this->_get('static.resources.after.'.$ext.'_'.$position)) && !$this->_in_array($url, $this->_get('static.resources.before.'.$ext.'_'.$position))) {
					$this->_push('static.resources.'.$this->_get('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		}
	}

	public function _snippet($file, $data = []) {
		$this->_require('snippets/'.$file.'.css');
		$this->_require('snippets/'.$file.'.js');
		$this->_include('_common/snippets/'.$file, $data);
	}

	public function _args_to_string_vars($array) {
		$res = '';
		for($i=0;$i<$this->_count($array);$i++) {
			if($res) {
				$res.=', ';
			}
			$res.='$'.$array[$i]['name'];
		}
		return $res;
	}

	public function _unique_id($length = 1, $str = '', $id = null) {

		if(!$this->_isset('static.unique_ids')) {
			$this->_set('static.unique_ids', []);
		}

		$crypt = ['A','a','B','b','C','c','D','d','E','e','F','f','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z'];
		$rand = [];
		while(count($crypt)) {
			$el = array_rand($crypt);
			$rand[$el] = $crypt[$el];
			unset($crypt[$el]);
		}
		$crypt = array_values($rand);
		for($i=0;$i<count($crypt);$i++) {
			if(!$id) {
				$id = '';
			}
			$id.=$crypt[$i];
			if(strlen($id) < $length) {
				return $this->_unique_id($length, $str, $id);
			}
			if($this->_in_array($id, $this->_get('static.unique_ids'))) {
				return $this->_unique_id($length, $str, $id);
			}
			$this->_push('static.unique_ids', $id);
			if($str) {
				$id = $str.$id;
			}
			return  $id;
		}
	}

	public function _acceptable($value) {
		return $this->_in_array($value, ['yes', 'on', '1', 1, true, 'true'], true) ? 1 : 0;
	}

	public function _add_class($data = null) {
		if(!$data) {
			return $this;
		}
		$this->_set('tag.attr.class', $this->_get('tag.attr.class') ? $this->_set('tag.attr.class', $this->_get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	public function _append($data) {
		return $this->_append_after_tag($data);
	}

	public function _append_after_tag($data = null) {
		$this->_push('tag.append_after_tag', $data);
		return $this;
	}

	public function _append_after_text($data = null) {
		$this->_push('tag.append_after_text', $data);
		return $this;
	}

	public function _append_before_tag($data = null) {
		$this->_push('tag.append_before_tag', $data);
		return $this;
	}

	public function _append_before_text($data = null) {
		$this->_push('tag.append_before_text', $data);
		return $this;
	}

	public function _array_replace($defaults, $replaces) {
		foreach($replaces as $key => $value) {
			$key = $this->_lower($key);
			if(!isset($defaults[$key]) || (isset($defaults[$key]) && !$this->_is_array($defaults[$key]))) {
				$defaults[$key] = [];
			}
			if($this->_is_array($value)) {
				$value = $this->_array_replace($defaults[$key], $value);
			}
			$defaults[$key] = $value;
		}

		return $defaults;
	}

	public function _array_merge($defaults, $replaces) {
		foreach($replaces as $key => $value) {
			if(!isset($defaults[$key]) || (isset($defaults[$key]) && !$this->_is_array($defaults[$key]))) {
				$defaults[$key] = [];
			}
			if($this->_is_array($value)) {
				$value = $this->_array_merge($defaults[$key], $value);
			}
			$defaults[$key] = $value;
		}
		return $defaults;
	}

	public function _array_sm($array, $count = 0, $current = 0, $first = 0) {
		if($first == 0) {
			$count = $this->_count($array);
		}
	    if($count - 1 === $current) {
	        $array = $array[$current];
	    } else {
	    	$array = [$array[$current] => $this->_array_sm($array, $count, $current + 1, 1)];
	    }
	    return $array;
	}

	public function _array_unset_recursive($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {
		foreach($replaces as $key => $value) {
			if(!$this->_stop && !$this->_is_array($unset_key) && $key == $unset_key) {
				$this->_stop = 1;
			} else {
				if(!$this->_key_in_array($key, $results)) {
					$results[$key] = [];
				}
				if($this->_is_array($value)) {
					if($lock == $level) {
						$unlock = 1;
					}
					if(!$this->_stop) {
						if($unlock) {
							if($this->_key_in_array($key, $unset_key)) {
								$unset_key = $unset_key[$key];
							} else {
								$lock = $level;
								$unlock = 0;
							}
						}
					}
					$level++;
					$value = $this->_array_unset_recursive($unset_key, $value, $results[$key], $level, $lock, $stop, $unlock);
					$level--;
				}
				$results[$key] = $value;
			}
		}
		return $results;
	}

	public function _ascii_to_text($contents) {
		$contents = $this->_explode(' ', $this->_trim($contents));
		$results = $this->_results($this->_get_exe_options($contents));
		return $results->contents;
	}

	public function _assets_images_url($url = '') {
		return $this->_url('assets/img/'.$this->_to_url_s($url));
	}

	public function _assets_img($img) {
		return '';
	}

	public function _attr($attr, $data = null) {
		$this->_set('tag.attr.'.$attr, $data);
		return $this;
	}

	public function _auto_route() {
		$path = $this->_request->path();
		$path = $this->_explode($this->_url_s(), $path);

		if(isset($path)) {
			if($path[0] == 'admin') {
				$this->_set('static.is_admin', 1);
				unset($path[0]);
				$path = $this->_array_zero($path);
			}
		}

		$path = $this->_implode('/', $path);

		$path = $path ? $path : '/';
		$this->_set('route', [
			'args' => [],
			'method' => 'index',
			'controller' => 'desktop',
		]);

		if($path && $path != $this->_url_s()) {
			$path = $this->_explode($this->_url_s(), $path);

			if(isset($path[0])) {
				if($path[0] == 'desktop' && !isset($path[1])) {
					$this->_fail('404: Page not found.');
				} else {
					$this->_set('route.controller', $path[0]);
					unset($path[0]);
				}
				if(isset($path[1])) {
					if($path[1] == 'index') {
						$this->_fail('404: Page not found.');
					} else {
						$this->_set('route.method', $path[1]);
						unset($path[1]);
					}
					if(isset($path[2])) {
						$this->_set('route.args', $this->_is_empty($path) ? false : $this->_array_zero($path));
					}
				}
			}
		}
	}

	public function _body_class($classes = '') {
		$classes = $this->_replace_spaces_with_one($classes);
		foreach($this->_explode(' ', $classes) as $class) {
			if(!$this->_in_array($class, $this->_get('static.bodyclass'))) {
				$this->_push('static.bodyclass', $class);
			}
		}
		return $this;
	}

	public function _back_slash() {
		return DIRECTORY_SEPARATOR;
	}

	public function _build_document($data) {
		return $this->_new('Brosta\Builders\OS_'.$this->_ucfirst($this->_get_doc_type()))->build($data);
	}

	public function _cache($file, $contents = null) {
		$file = $this->_disk('storage/interframework/cache/'.$this->_to_back_slash($file.'.php'));
    	if(!$this->_is_null($contents)) {
    		if(!$this->_is_dir($dir = $this->_get_dir_file($file))) {
    			$this->_mkdir($dir);
    		}
    		if($this->_file_exists($file)) {
    			$this->_delete_file($file);
    		}
			return $this->_fmk($file, $contents);
		}
		if($this->_file_exists($file)) {
			return $this->_get_file_contents($file);
		}
		return '';
	}

	public function _character($output) {
		$output->b = $output->a;
		$output->b_o = $output->a_o;
		if($output->decimal >= 0 && $output->decimal <= 127) {
			if($output->decimal >= 0 && $output->decimal <= 32 || $output->decimal == 127) {
				if($output->chars_control == null) {
					$output->chars_control = $this->_get_control_chars();
				}
				$output->a = $output->chars_control[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				if($output->decimal == 7) {
					$output->a_o = "\a";
				}
				if($output->decimal == 8) {
					$output->a_o = "\b";
				}
				if($output->decimal == 9) {
					$output->a_o = "\t";
				}
				if($output->decimal == 10) {
					$output->line = $output->line + 1;
					$output->a_o = "\n";
				}
				if($output->decimal == 11) {
					$output->a_o = "\v";
				}
				if($output->decimal == 12) {
					$output->a_o = "\f";
				}
				if($output->decimal == 13) {
					$output->a_o = "\r";
				}
				if($output->decimal == 27) {
					$output->a_o = "\e";
				}
				if($output->decimal == 32) {
					$output->a_o = " ";
				}
				$output->type = 'control';
			}
			elseif($output->decimal >= 65 && $output->decimal <= 90 || $output->decimal >= 97 && $output->decimal <= 122) {
				if($output->decimal >= 65 && $output->decimal <= 90) {
					if($output->chars_alpha_upper == null) {
						$output->chars_alpha_upper = $this->_get_alpha_upper();
					}
					$output->a_o = $output->chars_alpha_upper[$output->decimal]['symbol'];
					$output->a = $this->_get_alpha_from_upper_to_lower($output->decimal);
					$output->is_lower = 0;
					$output->is_upper = 1;
				}
				elseif($output->decimal >= 97 && $output->decimal <= 122) {
					if($output->chars_alpha_lower == null) {
						$output->chars_alpha_lower = $this->_get_alpha_lower();
					}
					$output->a_o = $output->chars_alpha_lower[$output->decimal]['symbol'];
					$output->a = $output->a_o;
					$output->is_upper = 0;
					$output->is_lower = 1;
				}
				$output->type = 'alpha';
			}
			elseif($output->decimal >= 48 && $output->decimal <= 57) {
				if($output->chars_numbers == null) {
					$output->chars_numbers = $this->_get_numbers();
				}
				$output->a = $output->chars_numbers[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'number';
			}
			elseif($output->decimal >= 33 && $output->decimal <= 47 || $output->decimal >= 58 && $output->decimal <= 64 || $output->decimal >= 91 && $output->decimal <= 96 || $output->decimal >= 123 && $output->decimal <= 126) {
				if($output->chars_symbols == null) {
					$output->chars_symbols = $this->_get_symbol_chars();
				}
				$output->a = $output->chars_symbols[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'symbol';
			}
		} else {
			if($output->chars_unknown == null) {
				$output->chars_unknown = $this->_get_unknown_chars();
			}
			$output->a = $output->chars_unknown[$output->decimal]['symbol'];
			$output->a_o = $output->a;
		}
		return $output;
	}

	public function _checked() {
		$this->_set('tag.attr.checked', 'checked');
		return $this;
	}

	public function _chkeep() {
		if(!$this->_is_empty($this->_get('keep'))) {
			if($this->_isset('keep.attr')) {
				if($this->_get('keep.attr.name') !== '') {
					if($this->_is_same('keep.attr.level', 'this.level')) {
						$this->_delete('keep.attr');
					}
				}
			}
			if($this->_isset('keep.form')) {
				if($this->_is_same('keep.form.level', 'this.level')) {
					$this->_delete('keep.form');
				}
			}
		}
	}

	public function _class($data = '') {
		$this->_set('tag.attr.class', $data);
		return $this;
	}

	public function _class_separator_fix(string $class) {
		return $this->_trim($this->_replace(['/', '.'], '\\', $class), '\\');
	}

	public function _collection_type_is($type) {
		return $this->_get('static.collection_type', $type);
	}

	public function _component_exists($path) {
		return $this->_file_exists($this->_assets_path($path));
	}

	public function _conclude() {
		$config_views = require($this->_disk('config/views.php'));

		if($config_views['auto_route'] === true) {
			$this->_auto_route();
		}

		$controllername = $this->_get_controller($this->_get('route.controller'));
		$controllermethod = $this->_get('route.method');

		if(class_exists($controllername)) {
			$controller = new $controllername;
			if(method_exists($controller, $controllermethod)) {
				$this->_data = $controller->{$controllermethod}(new App($this), $this->_request);
			} else {
				$this->_fail('Method not exists');
			}
		} else {
			$this->_fail('Class not exists');
		}

		if($this->_monitor()) {
			$contents = $this->_finalize();
			$this->_set('static.contents.before', 0);
			if($this->_is_string($contents)) {
				$this->_set_text($contents);
			} else {
				$this->_fail('Server info: Only string type accepted. Your type [ '.$this->_get_type($contents).' ] does not supported from your system copyrights.');
			}
		} else {
			$this->_fail('NO SIGNAL');
		}
	}

	public function _has_contents_before() {
		return $this->_get('static.contents.before', 1);
	}

	public function _config(string $key) {
		return $this->_isset('static.config.'.$key) ? $this->_get('static.config.'.$key) : null;
	}

	public function _copy_dir($directory, $destination, $options = null) {
		if(!$this->_is_dir($directory)) {
            return false;
        }
        $options = $options ?: FilesystemIterator::SKIP_DOTS;
        if (!$this->_is_dir($destination)) {
            $this->_mkdir($destination, 0777, true);
        }
        $items = new FilesystemIterator($directory, $options);
        foreach ($items as $item) {
            $target = $destination.'/'.$item->getBasename();
            if ($item->isDir()) {
                $path = $item->getPathname();
                if(!$this->_copy_dir($path, $target, $options)) {
                    return false;
                }
            }
            else {
                if (!$this->_copy_file($item->getPathname(), $target)) {
                    return false;
                }
            }
        }
        return true;
	}

	public function _credential() {
		if($this->_get('tag.tag') !== 'untaged') {
			if($this->_get('tag.tag', 'form')) {
				$this->_set('keep.form', [
					'name' => $this->_get('tag.attr.name'),
					'index' => $this->_get('this.index'),
					'level' => $this->_get('this.level')
				]);
			}
			if($this->_isset('tag.attr.name')) {
				$this->_set('keep.attr', [
					'level' => $this->_get('this.level'),
					'name' => $this->_replace('[]', '', $this->_get('tag.attr.name')),
					'type' => $this->_isset('tag.attr.type') ? $this->_get('tag.attr.type') : false,
					'defineds' => $this->_get('tag.defineds')
				]);
			}
			if($this->_isset('keep.attr.name')) {
				$posted = $this->_isset('keep.attr.defineds.posted');
				$type = $this->_lower($this->_get('keep.attr.type'));
				$default = [];

				if($this->_isset('old') && $this->_get('keep.attr.name') && $this->_isset('old.'.$this->_get('keep.attr.name'))) {
					$default[$this->_get('keep.attr.name')] = $this->_get('old.'.$this->_get('keep.attr.name'));
				} else {
					if($this->_key_in_array('default_checked', $this->_get('keep.attr.defineds'))) {
						$default[$this->_get('keep.attr.name')] = $this->_get('keep.attr.defineds.default_checked');
					}
					elseif($this->_key_in_array('default_selected', $this->_get('keep.attr.defineds'))) {
						$default[$this->_get('keep.attr.name')] = $this->_get('keep.attr.defineds.default_selected');
					}
					elseif($this->_key_in_array('default_value', $this->_get('keep.attr.defineds'))) {
						$default[$this->_get('keep.attr.name')] = $this->_get('keep.attr.defineds.default_value');
					}
					elseif($this->_key_in_array('default_text', $this->_get('keep.attr.defineds'))) {
						$default[$this->_get('keep.attr.name')] = $this->_get('keep.attr.defineds.default_text');
					}
				}
				if(!$posted && !$this->_get('keep.attr.name')) {
					if($this->_key_in_array($this->_get('keep.attr.name'), $default)) {
						unset($default[$this->_get('keep.attr.name')]);
					}
				}
				if($this->_key_in_array($this->_get('keep.attr.name'), $default)) {
					if($this->_key_in_array('value', $this->_get('tag.attr'))) {
						if($this->_is_array($default[$this->_get('keep.attr.name')])) {
							if($this->_in_array($this->_get('tag.attr.value'), $default[$this->_get('keep.attr.name')])) {
								if($this->_get('tag.tag') == 'input') {
									if($type == 'checkbox' || $type == 'radio') {
										$this->_checked();
									}
								}
								if($this->_get('tag.tag') == 'option') {
									$this->_selected();
								}
							}
						} else {
							if($this->_get('tag.tag') == 'input') {
								if($type == 'checkbox' || $type == 'radio') {
									if($this->_get('tag.attr.value') == $default[$this->_get('keep.attr.name')]) {
										$this->_checked();
									}
								} else {
									$this->_set('tag.attr.value', $default[$this->_get('keep.attr.name')]);
								}
							}
							if($this->_get('tag.tag') == 'option') {
								if($this->_get('tag.attr.value') == $default[$this->_get('keep.attr.name')]) {
									$this->_selected();
								}
							}
						}
					} else {
						if($this->_get('tag.tag') == 'input') {
							if($type == 'checkbox' || $type == 'radio') {
								if($this->_acceptable($default[$this->_get('keep.attr.name')])) {
									$this->_checked();
								}
							}
							elseif($type == 'text') {
								$this->_set('tag.attr.value', $default[$this->_get('keep.attr.name')]);
							} else {
								
							} 
						} else {
							if($this->_get('tag.tag') == 'option') {
								if($this->_acceptable($default[$this->_get('keep.attr.name')])) {
									$this->_selected();
								}
							} else {
								if($this->_get('tag.tag') == 'textarea') {
									$this->_set('tag.text', $default[$this->_get('keep.attr.name')]);
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	public function _current_type_is(string $type) {
		return $this->_get('static.doctype', $type);
	}

	public function _get_doc_type() {
		return $this->_get('static.doctype');
	}

	public function _set_doc_type(string $type) {
		return $this->_set('static.doctype', $type);
	}

	public function _default_checked($data = '') {
		$this->_set('tag.defineds.default_checked', $data);
        return $this;
	}

	public function _default_selected($data = '') {
		$this->_set('tag.defineds.default_selected', $data);
        return $this;
	}

	public function _default_text($data = '') {
		$this->_set('tag.defineds.default_text', $data);
        return $this;
	}

	public function _default_value($data = '') {
		$this->_set('tag.defineds.default_value', $data);
        return $this;
	}

	public function _delete($key) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($count > 0) {
			if($count == 1) {
				unset($this->_memory[$base][$keys[0]]);
			} else {
				$this->_memory[$base] = $this->_array_unset_recursive($this->_array_sm($keys), $this->_memory[$base]);
				$this->_stop = 0;
			}
		} else {
		   	unset($this->_memory[$base]);
		}
	}

	public function _delete_path($directory, $preserve = false) {
		if(!$this->_is_dir($directory)) {
            return false;
        }
        $items = new FilesystemIterator($directory);
        foreach ($items as $item) {
            if ($item->is_dir() && ! $item->isLink()) {
                $this->_delete_path($item->getPathname());
            }
            else {
               $this->_delete_file($item->getPathname());
            }
        }
        if (! $preserve) {
            @rmdir($directory);
        }
        return true;
	}

	public function _document() {
		if($this->_get('this.waiting')) {
				if($this->_credential()) {

					if($this->_isset('static.cspace_lock') && $this->_get('tag.cspace', 0)) {
						$this->_set('tag.cspace', $this->_get('static.cspace_lock'));
					}

					$this->_set('this.items.'.$this->_get('this.index'), [
						'tag'						=> $this->_get('tag.tag'),
					    'attr'						=> $this->_get('tag.attr'),
					    'text'						=> $this->_get('tag.text'),
					    'append_before_tag' 		=> $this->_get('tag.append_before_tag'),
					    'append_after_tag'			=> $this->_get('tag.append_after_tag'),
					    'append_before_text'		=> $this->_get('tag.append_before_text'),
					    'append_after_text'			=> $this->_get('tag.append_after_text'),
						'nested'					=> $this->_get('tag.nested'),
						'contents'					=> $this->_get('tag.contents'),
						'open_tag'					=> $this->_get('tag.open_tag'),
						'close_tag'					=> $this->_get('tag.close_tag'),
						'tag_after'					=> $this->_get('tag.tag_after'),
						'tag_before'				=> $this->_get('tag.tag_before'),
						'items'						=> $this->_get('tag.items'),
						'fake_line'					=> $this->_get('tag.fake_line'),
						'cspace'					=> $this->_get('tag.cspace'),
					    'index'						=> $this->_get('this.index'),
					    'level'						=> $this->_get('this.level'),
						'start_code_space_level'	=> $this->_get_start_code_space_level(),
					]);

					$this->_set('tag', $this->_new_tag());
					$this->_set('this.count', $this->_get('this.count') + 1);
					$this->_set('this.index', $this->_get('this.index') + 1);

					$this->_set('this.waiting', 0);
				} else {
					$this->_fail('Security error: Credentials has fail to process your request.');
				}
			}
	}

	public function _dot_to_underscore($str) {
		return $this->_trim($this->_replace('.', '_', $str), '_');
	}

	public function _dot_to_back_slash($str) {
		return $this->_trim($this->_replace('.', $this->_back_slash(), $str), $this->_back_slash());
	}

	public function _dot_to_url_s($str) {
		return $this->_trim($this->_replace('.', $this->_url_s(), $str), $this->_url_s());
	}

	public function _echo($string) {
		echo($string);
	}

	public function _escape($str) {
		$js_escape = [
			"\r" => '\r',
			"\n" => '\n',
			"\t" => '\t',
			"'" => "\\'",
			'"' => '\"',
			'\\' => '\\\\'
		];
        return $this->_str_trans($str, $js_escape);
	}

	public function _unescape($str) {
		$js_escape = [
			'\r' => "\r",
			'\n' => "\n",
			'\t' => "\t",
			"\\'" => "'",
			'\"' => '"',
			'\\\\' => '\\'
		];
        return $this->_str_trans($str, $js_escape);
	}

	public function _export(array $opts = []) {
		$results = "";
		if($opts['type'] == 'json') {
			$results = $this->_get_exported_string($this->_array_merge([
				'quote' => '"',
				'value' => [],
				'prefix_key' => "o_",
				'opentagsymbol' => "{",
				'closetagsymbol' => "}",
				'key_separator_value' => " : ",
			], $opts));
		}
		elseif($opts['type'] == 'array') {
			$results = $this->_get_exported_string($this->_array_merge([
				'quote' => '"',
				'value' => [],
				'opentagsymbol' => "[",
				'closetagsymbol' => "]",
				'key_separator_value' => " => ",
				'spacetab' => "\t",
				'key_to_indent' => 0,
				'value_to_indent' => 0,
				'without_numeric_keys' => 1,
			], $opts));
			return trim($results);
		} else {
			$this->_fail('unknown file type for export');
		}
	}

	public function _fail($msg) {
		$this->_echo($msg);
		$this->_exit();
	}

	public function _file_append_to_top($file, $contents) {
		return $this->_fwrite($file, $contents.$this->_get_file_contents($file));
	}

	public function _finalize(int $reset = null) {
		$document = $this->_get_ready_document($reset);
		if($this->_trim($document)) {
			$this->_cache($this->_get('view'), $document);
		}
		return $document;
	}

	public function _fix_type($value) {
		$type = gettype($value);
		$type = $this->_lower($type);
		switch($type) {
			case 'boolean':
				$value = $value ? 'true' : 'false';
			break;
			case 'integer':
			case 'double':
				//
			break;
			case 'string':
				$value = "'".$this->_escape($value)."'";
			break;
			case 'null':
				$value = 'null';
			break;
			case 'array':
				if($this->_is_empty($value)) {
					$value = '[]';
				} else { 
					$value = "[\n\t".$this->_export([
						'value' => $value,
						'quote' => "'",
						'type' => 'array',
					])."\n]";
				}
			break;
			default:
				
			break;
		}
		return $value;
	}

	public function _function_exists($name) {
		return $this->_isset('functions.'.$name);
	}

	public function _get($key, $default = null) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		if($this->_key_in_array($base, $this->_memory)) {
			if($this->_count($keys) > 0) {
				$results = $this->_find($keys, $this->_memory[$base]);
			} else {
		   		$results = $this->_memory[$base];
			}
		} else {
			return '';
		}
		if($results === TIPOTA) {
			return null;
		}
		if($default !== null) {
			return $default === $results;
		}
		return $results;
	}

	public function _get_alpha_from_lower_to_upper($code = null, $type = 'symbol') {
		$codes =  [
				97 => [
					'hex' => '41',
					'symbol' => 'A',
					'chars' => [
						0 => 65
					],
				],
				98 => [
					'hex' => '42',
					'symbol' => 'B',
					'chars' => [
						0 => 66
					],
				],
				99 => [
					'hex' => '43',
					'symbol' => 'C',
					'chars' => [
						0 => 67
					],
				],
				100 => [
					'hex' => '44',
					'symbol' => 'D',
					'chars' => [
						0 => 68
					],
				],
				101 => [
					'hex' => '45',
					'symbol' => 'E',
					'chars' => [
						0 => 69
					],
				],
				102 => [
					'hex' => '46',
					'symbol' => 'F',
					'chars' => [
						0 => 70
					],
				],
				103 => [
					'hex' => '47',
					'symbol' => 'G',
					'chars' => [
						0 => 71
					],
				],
				104 => [
					'hex' => '48',
					'symbol' => 'H',
					'chars' => [
						0 => 72
					],
				],
				105 => [
					'hex' => '49',
					'symbol' => 'I',
					'chars' => [
						0 => 73
					],
				],
				106 => [
					'hex' => '4A',
					'symbol' => 'J',
					'chars' => [
						0 => 74
					],
				],
				107 => [
					'hex' => '4B',
					'symbol' => 'K',
					'chars' => [
						0 => 75
					],
				],
				108 => [
					'hex' => '4C',
					'symbol' => 'L',
					'chars' => [
						0 => 76
					],
				],
				109 => [
					'hex' => '4D',
					'symbol' => 'M',
					'chars' => [
						0 => 77
					],
				],
				110 => [
					'hex' => '4E',
					'symbol' => 'N',
					'chars' => [
						0 => 78
					],
				],
				111 => [
					'hex' => '4F',
					'symbol' => 'O',
					'chars' => [
						0 => 79
					],
				],
				112 => [
					'hex' => '50',
					'symbol' => 'P',
					'chars' => [
						0 => 80
					],
				],
				113 => [
					'hex' => '51',
					'symbol' => 'Q',
					'chars' => [
						0 => 81
					],
				],
				114 => [
					'hex' => '52',
					'symbol' => 'R',
					'chars' => [
						0 => 82
					],
				],
				115 => [
					'hex' => '53',
					'symbol' => 'S',
					'chars' => [
						0 => 83
					],
				],
				116 => [
					'hex' => '54',
					'symbol' => 'T',
					'chars' => [
						0 => 84
					],
				],
				117 => [
					'hex' => '55',
					'symbol' => 'U',
					'chars' => [
						0 => 85
					],
				],
				118 => [
					'hex' => '56',
					'symbol' => 'V',
					'chars' => [
						0 => 86
					],
				],
				119 => [
					'hex' => '57',
					'symbol' => 'W',
					'chars' => [
						0 => 87
					],
				],
				120 => [
					'hex' => '58',
					'symbol' => 'X',
					'chars' => [
						0 => 88
					],
				],
				121 => [
					'hex' => '59',
					'symbol' => 'Y',
					'chars' => [
						0 => 89
					],
				],
				122 => [
					'hex' => '5A',
					'symbol' => 'Z',
					'chars' => [
						0 => 90
					],
				],
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_alpha_from_upper_to_lower($code = null, $type = 'symbol') {
		$codes =  [
				65 => [
					'hex' => '61',
					'symbol' => 'a',
					'chars' => [
						0 => 97
					],
				],
				66 => [
					'hex' => '62',
					'symbol' => 'b',
					'chars' => [
						0 => 98
					],
				],
				67 => [
					'hex' => '63',
					'symbol' => 'c',
					'chars' => [
						0 => 99
					],
				],
				68 => [
					'hex' => '64',
					'symbol' => 'd',
					'chars' => [
						0 => 100
					],
				],
				69 => [
					'hex' => '65',
					'symbol' => 'e',
					'chars' => [
						0 => 101
					],
				],
				70 => [
					'hex' => '66',
					'symbol' => 'f',
					'chars' => [
						0 => 102
					],
				],
				71 => [
					'hex' => '67',
					'symbol' => 'g',
					'chars' => [
						0 => 103
					],
				],
				72 => [
					'hex' => '68',
					'symbol' => 'h',
					'chars' => [
						0 => 104
					],
				],
				73 => [
					'hex' => '69',
					'symbol' => 'i',
					'chars' => [
						0 => 105
					],
				],
				74 => [
					'hex' => '6A',
					'symbol' => 'j',
					'chars' => [
						0 => 106
					],
				],
				75 => [
					'hex' => '6B',
					'symbol' => 'k',
					'chars' => [
						0 => 107
					],
				],
				76 => [
					'hex' => '6C',
					'symbol' => 'l',
					'chars' => [
						0 => 108
					],
				],
				77 => [
					'hex' => '6D',
					'symbol' => 'm',
					'chars' => [
						0 => 109
					],
				],
				78 => [
					'hex' => '6E',
					'symbol' => 'n',
					'chars' => [
						0 => 110
					],
				],
				79 => [
					'hex' => '6F',
					'symbol' => 'o',
					'chars' => [
						0 => 111
					],
				],
				80 => [
					'hex' => '70',
					'symbol' => 'p',
					'chars' => [
						0 => 112
					],
				],
				81 => [
					'hex' => '71',
					'symbol' => 'q',
					'chars' => [
						0 => 113
					],
				],
				82 => [
					'hex' => '72',
					'symbol' => 'r',
					'chars' => [
						0 => 114
					],
				],
				83 => [
					'hex' => '73',
					'symbol' => 's',
					'chars' => [
						0 => 115
					],
				],
				84 => [
					'hex' => '74',
					'symbol' => 't',
					'chars' => [
						0 => 116
					],
				],
				85 => [
					'hex' => '75',
					'symbol' => 'u',
					'chars' => [
						0 => 117
					],
				],
				86 => [
					'hex' => '76',
					'symbol' => 'v',
					'chars' => [
						0 => 118
					],
				],
				87 => [
					'hex' => '77',
					'symbol' => 'w',
					'chars' => [
						0 => 119
					],
				],
				88 => [
					'hex' => '78',
					'symbol' => 'x',
					'chars' => [
						0 => 120
					],
				],
				89 => [
					'hex' => '79',
					'symbol' => 'y',
					'chars' => [
						0 => 121
					],
				],
				90 => [
					'hex' => '7A',
					'symbol' => 'z',
					'chars' => [
						0 => 122
					],
				]
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_alpha_lower($code = null, $type = 'symbol') {
		$codes =  [
				97 => [
					'hex' => '61',
					'symbol' => 'a',
					'chars' => [
						0 => 97
					],
				],
				98 => [
					'hex' => '62',
					'symbol' => 'b',
					'chars' => [
						0 => 98
					],
				],
				99 => [
					'hex' => '63',
					'symbol' => 'c',
					'chars' => [
						0 => 99
					],
				],
				100 => [
					'hex' => '64',
					'symbol' => 'd',
					'chars' => [
						0 => 100
					],
				],
				101 => [
					'hex' => '65',
					'symbol' => 'e',
					'chars' => [
						0 => 101
					],
				],
				102 => [
					'hex' => '66',
					'symbol' => 'f',
					'chars' => [
						0 => 102
					],
				],
				103 => [
					'hex' => '67',
					'symbol' => 'g',
					'chars' => [
						0 => 103
					],
				],
				104 => [
					'hex' => '68',
					'symbol' => 'h',
					'chars' => [
						0 => 104
					],
				],
				105 => [
					'hex' => '69',
					'symbol' => 'i',
					'chars' => [
						0 => 105
					],
				],
				106 => [
					'hex' => '6A',
					'symbol' => 'j',
					'chars' => [
						0 => 106
					],
				],
				107 => [
					'hex' => '6B',
					'symbol' => 'k',
					'chars' => [
						0 => 107
					],
				],
				108 => [
					'hex' => '6C',
					'symbol' => 'l',
					'chars' => [
						0 => 108
					],
				],
				109 => [
					'hex' => '6D',
					'symbol' => 'm',
					'chars' => [
						0 => 109
					],
				],
				110 => [
					'hex' => '6E',
					'symbol' => 'n',
					'chars' => [
						0 => 110
					],
				],
				111 => [
					'hex' => '6F',
					'symbol' => 'o',
					'chars' => [
						0 => 111
					],
				],
				112 => [
					'hex' => '70',
					'symbol' => 'p',
					'chars' => [
						0 => 112
					],
				],
				113 => [
					'hex' => '71',
					'symbol' => 'q',
					'chars' => [
						0 => 113
					],
				],
				114 => [
					'hex' => '72',
					'symbol' => 'r',
					'chars' => [
						0 => 114
					],
				],
				115 => [
					'hex' => '73',
					'symbol' => 's',
					'chars' => [
						0 => 115
					],
				],
				116 => [
					'hex' => '74',
					'symbol' => 't',
					'chars' => [
						0 => 116
					],
				],
				117 => [
					'hex' => '75',
					'symbol' => 'u',
					'chars' => [
						0 => 117
					],
				],
				118 => [
					'hex' => '76',
					'symbol' => 'v',
					'chars' => [
						0 => 118
					],
				],
				119 => [
					'hex' => '77',
					'symbol' => 'w',
					'chars' => [
						0 => 119
					],
				],
				120 => [
					'hex' => '78',
					'symbol' => 'x',
					'chars' => [
						0 => 120
					],
				],
				121 => [
					'hex' => '79',
					'symbol' => 'y',
					'chars' => [
						0 => 121
					],
				],
				122 => [
					'hex' => '7A',
					'symbol' => 'z',
					'chars' => [
						0 => 122
					],
				],
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_alpha_upper($code = null, $type = 'symbol') {
		$codes =  [
				65 => [
					'hex' => '41',
					'symbol' => 'A',
					'chars' => [
						0 => 65
					],
				],
				66 => [
					'hex' => '42',
					'symbol' => 'B',
					'chars' => [
						0 => 66
					],
				],
				67 => [
					'hex' => '43',
					'symbol' => 'C',
					'chars' => [
						0 => 67
					],
				],
				68 => [
					'hex' => '44',
					'symbol' => 'D',
					'chars' => [
						0 => 68
					],
				],
				69 => [
					'hex' => '45',
					'symbol' => 'E',
					'chars' => [
						0 => 69
					],
				],
				70 => [
					'hex' => '46',
					'symbol' => 'F',
					'chars' => [
						0 => 70
					],
				],
				71 => [
					'hex' => '47',
					'symbol' => 'G',
					'chars' => [
						0 => 71
					],
				],
				72 => [
					'hex' => '48',
					'symbol' => 'H',
					'chars' => [
						0 => 72
					],
				],
				73 => [
					'hex' => '49',
					'symbol' => 'I',
					'chars' => [
						0 => 73
					],
				],
				74 => [
					'hex' => '4A',
					'symbol' => 'J',
					'chars' => [
						0 => 74
					],
				],
				75 => [
					'hex' => '4B',
					'symbol' => 'K',
					'chars' => [
						0 => 75
					],
				],
				76 => [
					'hex' => '4C',
					'symbol' => 'L',
					'chars' => [
						0 => 76
					],
				],
				77 => [
					'hex' => '4D',
					'symbol' => 'M',
					'chars' => [
						0 => 77
					],
				],
				78 => [
					'hex' => '4E',
					'symbol' => 'N',
					'chars' => [
						0 => 78
					],
				],
				79 => [
					'hex' => '4F',
					'symbol' => 'O',
					'chars' => [
						0 => 79
					],
				],
				80 => [
					'hex' => '50',
					'symbol' => 'P',
					'chars' => [
						0 => 80
					],
				],
				81 => [
					'hex' => '51',
					'symbol' => 'Q',
					'chars' => [
						0 => 81
					],
				],
				82 => [
					'hex' => '52',
					'symbol' => 'R',
					'chars' => [
						0 => 82
					],
				],
				83 => [
					'hex' => '53',
					'symbol' => 'S',
					'chars' => [
						0 => 83
					],
				],
				84 => [
					'hex' => '54',
					'symbol' => 'T',
					'chars' => [
						0 => 84
					],
				],
				85 => [
					'hex' => '55',
					'symbol' => 'U',
					'chars' => [
						0 => 85
					],
				],
				86 => [
					'hex' => '56',
					'symbol' => 'V',
					'chars' => [
						0 => 86
					],
				],
				87 => [
					'hex' => '57',
					'symbol' => 'W',
					'chars' => [
						0 => 87
					],
				],
				88 => [
					'hex' => '58',
					'symbol' => 'X',
					'chars' => [
						0 => 88
					],
				],
				89 => [
					'hex' => '59',
					'symbol' => 'Y',
					'chars' => [
						0 => 89
					],
				],
				90 => [
					'hex' => '5A',
					'symbol' => 'Z',
					'chars' => [
						0 => 90
					],
				],
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _items_length() {
		return $this->_get('this.count');
	}

	public function _get_builded_text($item, $level) {
		$contents = "";
		if($item['tag_before'] !== null && $item['lower_tag'] != 'untaged') {
			$contents.=$item['new_line_before'];
		}
		if($item['nested'] !== null && $item['tag_after'] !== null && $item['lower_tag'] != 'untaged') {
			$item['tag_after'] = $item['new_line_after'].$item['space'].$item['tag_after'];
		}
		$contents.=$item['space'];
		$contents.=$this->_implode('', $item['append_before_tag']);
		$contents.=$item['tag_before'];
		$contents.=$this->_implode('', $item['append_before_text']);
		$contents.=$item['text'];
		$contents.=$this->_implode('', $item['append_after_text']);
		$contents.=$item['nested'];
		$contents.=$item['tag_after'];
		$contents.=$this->_implode('', $item['append_after_tag']);
		return $contents;
	}

	public function _class_get_reflection($instance) {
		$class = new ReflectionClass($instance);
		$name = $class->getName();
		$in_namespace = $class->inNamespace() ? 1 : 0;
		$namespace_name = '';
		$namespace_name_with_class = '';

		if($in_namespace) {
			$name = $class->getShortName();
			$namespace_name = $class->getNamespaceName();
			$namespace_name_with_class = $class->getName();
		}

		return [
			'name' => $name,
			'in_namespace' => $in_namespace,
			'namespace_name' => $namespace_name,
			'namespace_name_with_class' => $namespace_name_with_class,
		];
	}

	public function _class_get_properties($instance) {

		$class = new ReflectionClass($instance);
		$class_name = get_class($instance);
		$class_newe = new $class_name;

		$props = $class->getProperties(); //sort($props);
		$results = [];
		foreach($props as $property) {
			$name = $property->getName();
			$real = $class->getProperty($name);

			$is_static = 0;
			if($real->isStatic()) {
				$is_static = 1;
			}

			if($real->isPublic()) {
				$visibility = 'public';
			}
			elseif($real->isPrivate()) {
				$real->setAccessible(true);
				$visibility = 'private';
			}
			elseif($real->isProtected()) {
				$real->setAccessible(true);
				$visibility = 'protected';
			}

			$value = $real->getValue($class_newe);

			$results[$name] = [
				'name' => $name,
				'is_static' => $is_static,
				'visibility' => $visibility,
				'value' => $value,
			];
		}
		return $results;
	}

	public function _class_get_methods($instance) {
		$class = new ReflectionClass($instance);
		$methods = $class->getMethods();
		//sort($methods);
		$results = [];
		foreach($methods as $method) {
			$rm = new ReflectionMethod($this, $method->name);

			$is_static = $method->isStatic();
			$visibility = '';
			if($method->isPublic()) {
				$visibility = 'public';
			}
			elseif($method->isPrivate()) {
				$visibility = 'private';
			}
			elseif($method->isProtected()) {
				$visibility = 'protected';
			}

			$j = 0;
			$args = [];
			foreach($rm->getParameters() as $param) { 
				$args[$j]['name'] = $this->_lower($param->name);
				$args[$j]['type'] = $param->hasType() ? $param->getType()->getName() : '';
				$args[$j]['bref'] = $param->isPassedByReference() ? 1 : 0;
				if($param->isOptional()) {
					$args[$j]['value'] = $param->getDefaultValue();
				}
				$j++;
			}

			$body = '';
	        if(!$method->isAbstract()) {
				$file = $rm->getFileName();
		        if($file) {
					$start	= $rm->getStartLine() - 1;
					$end	= $rm->getEndLine() - $start + 1;
			        $lines = file($file, FILE_IGNORE_NEW_LINES);
			        $lines = array_slice($lines, $start, $end, true);
			        $lines = implode("\n", $lines);
			        $obrace = strpos($lines, '{');
			        $cbrace = strrpos($lines, '}');
			        $body = substr($lines, $obrace + 1, $cbrace - $obrace - 1);
			        $body = $this->_text_to_ascii($body);
		        }
	        }

			$results[$this->_lower($method->name)] = [
				'name' => $method->name,
				'args' => $args,
				'body' => $body,
				'static' => $is_static,
				'visibility' => $visibility
			];
		}
		return $results;
	}

	public function _class_get_as_array($instance) {
		if($this->_is_string($instance)) {
			$instance = new $instance;
		}
		$app = $this->_class_get_reflection($instance);
		$app['properties'] = $this->_class_get_properties($instance);
		$app['methods'] = $this->_class_get_methods($instance);
		return $app;
	}

	public function _get_control_chars($code = null, $type = 'symbol') {
		$codes =  [
			0 => [
				'hex' => '0',
				'symbol' => 'NUL',
				'chars' => [
					0 => 78,
					1 => 85,
					2 => 76,
				],
			],
			1 => [
				'hex' => '1',
				'symbol' => 'SOH',
				'chars' => [
					0 => 83,
					1 => 79,
					2 => 72,
				],
			],
			2 => [
					'hex' => '2',
					'symbol' => 'STX',
					'chars' => [
						0 => 83,
						1 => 84,
						2 => 88,
					],
				],
				3 => [
					'hex' => '3',
					'symbol' => 'ETX',
					'chars' => [
						0 => 69,
						1 => 84,
						2 => 88,
					],
				],
				4 => [
					'hex' => '4',
					'symbol' => 'EOT',
					'chars' => [
						0 => 69,
						1 => 79,
						2 => 84,
					],
				],
				5 => [
					'hex' => '5',
					'symbol' => 'ENQ',
					'chars' => [
						0 => 69,
						1 => 78,
						2 => 81,
					],
				],
				6 => [
					'hex' => '6',
					'symbol' => 'ACK',
					'chars' => [
						0 => 65,
						1 => 67,
						2 => 75,
					],
				],
				7 => [
					'hex' => '7',
					'symbol' => 'BEL',
					'chars' => [
						0 => 66,
						1 => 69,
						2 => 76,
					],
				],
				8 => [
					'hex' => '8',
					'symbol' => 'BS',
					'chars' => [
						0 => 66,
						1 => 83,
					],
				],
				9 => [
					'hex' => '9',
					'symbol' => 'HT',
					'chars' => [
						0 => 84,
						1 => 65,
						2 => 66,
					],
				],
				10 => [
					'hex' => 'A',
					'symbol' => 'LF',
					'chars' => [
						0 => 76,
						1 => 70,
					],
				],
				11 => [
					'hex' => 'B',
					'symbol' => 'VT',
					'chars' => [
						0 => 86,
						1 => 84,
					],
				],
				12 => [
					'hex' => 'C',
					'symbol' => 'FF',
					'chars' => [
						0 => 70,
						1 => 70,
					],
				],
				13 => [
					'hex' => 'D',
					'symbol' => 'CR',
					'chars' => [
						0 => 67,
						1 => 82,
					],
				],
				14 => [
					'hex' => 'E',
					'symbol' => 'SO',
					'chars' => [
						0 => 83,
						1 => 79,
					],
				],
				15 => [
					'hex' => 'F',
					'symbol' => 'SI',
					'chars' => [
						0 => 83,
						1 => 73,
					],
				],
				16 => [
					'hex' => '10',
					'symbol' => 'DLE',
					'chars' => [
						0 => 68,
						1 => 76,
						2 => 69,
					],
				],
				17 => [
					'hex' => '11',
					'symbol' => 'DC1',
					'chars' => [
						0 => 68,
						1 => 67,
						2 => 49,
					],
				],
				18 => [
					'hex' => '12',
					'symbol' => 'DC2',
					'chars' => [
						0 => 68,
						1 => 67,
						2 => 50,
					],
				],
				19 => [
					'hex' => '13',
					'symbol' => 'DC3',
					'chars' => [
						0 => 68,
						1 => 67,
						2 => 51,
					],
				],
				20 => [
					'hex' => '14',
					'symbol' => 'DC4',
					'chars' => [
						0 => 68,
						1 => 67,
						2 => 52,
					],
				],
				21 => [
					'hex' => '15',
					'symbol' => 'NAK',
					'chars' => [
						0 => 78,
						1 => 65,
						2 => 75,
					],
				],
				22 => [
					'hex' => '16',
					'symbol' => 'SYN',
					'chars' => [
						0 => 83,
						1 => 89,
						2 => 78,
					],
				],
				23 => [
					'hex' => '17',
					'symbol' => 'ETB',
					'chars' => [
						0 => 69,
						1 => 84,
						2 => 66,
					],
				],
				24 => [
					'hex' => '18',
					'symbol' => 'CAN',
					'chars' => [
						0 => 67,
						1 => 65,
						2 => 78,
					],
				],
				25 => [
					'hex' => '19',
					'symbol' => 'EM',
					'chars' => [
						0 => 69,
						1 => 77,
					],
				],
				26 => [
					'hex' => '1A',
					'symbol' => 'SUB',
					'chars' => [
						0 => 83,
						1 => 85,
						2 => 66,
					],
				],
				27 => [
					'hex' => '1B',
					'symbol' => 'ESC',
					'chars' => [
						0 => 69,
						1 => 83,
						2 => 67,
					],
				],
				28 => [
					'hex' => '1C',
					'symbol' => 'FS',
					'chars' => [
						0 => 70,
						1 => 83,
					],
				],
				29 => [
					'hex' => '1D',
					'symbol' => 'GS',
					'chars' => [
						0 => 71,
						1 => 83,
					],
				],
				30 => [
					'hex' => '1E',
					'symbol' => 'RS',
					'chars' => [
						0 => 82,
						1 => 83,
					],
				],
				31 => [
					'hex' => '1F',
					'symbol' => 'US',
					'chars' => [
						0 => 85,
						1 => 83,
					],
				],
				32 => [
					'hex' => '20',
					'symbol' => 'space',
					'chars' => [
						0 => 115,
						1 => 112,
						2 => 97,
						3 => 99,
						4 => 101,
					],
				],
				127 => [
					'hex' => '7F',
					'symbol' => 'delete',
					'chars' => [
						0 => 32
					],
				]
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_controller($name, $arguments = []) {
		return $controller = '\App'.($this->_is_admin() ? '\Controllers\Admin\\' : '\Controllers\\').$this->_ucfirst($name);
	}

	public function _get_database_names(array $op = null) {
		$results = [];
		$files = $this->_get_paths_only($this->_database_path());
		for($i=0;$i<$this->_count($files);$i++) {
			$name = $this->_basename($files[$i]);
			if($op) {
				if(isset($op['with_tables']) && $op['with_tables'] == 1) {
					$tables = $this->_get_database_tables($name);
					if(isset($op['with_tables_data']) && $op['with_tables_data'] == 1) {
						if(isset($op['keyed']) && $op['keyed'] == 1) {
							for($t=0;$t<$this->_count($tables);$t++) {
								$results[$name][$tables[$t]] = $this->_get_database_table_data($name, $tables[$t]);
							}
						} else {
							$array_tables_data = [];
							for($t=0;$t<$this->_count($tables);$t++) {
								$array_tables_data[] = [
									'name' => $tables[$t],
									'data' => $this->_get_database_table_data($name, $tables[$t])
								];
							}
							$results[] = [
								'name' => $name,
								'tables' => $array_tables_data
							];
						}
					} else {
						if(isset($op['keyed']) && $op['keyed'] == 1) {
							$results[$name] = $tables;
						} else {
							$results[] = [
								'name' => $name,
								'tables' => $tables
							];
						}
					}
				}
			} else {
				if(isset($op['keyed']) && $op['keyed'] == 1) {
					$results[$name] = $name;
				} else {
					$results[] = $name;
				}
			}
		}
		return $results;
	}

	public function _get_database_names_with_tables($keyed = 0) {
		return $this->_get_database_names([
			'with_tables' => 1,
			'with_tables' => 1,
			'keyed' => $keyed
		]);
	}

	public function _get_database_names_with_tables_and_data($keyed = 0) {
		return $this->_get_database_names([
			'with_tables' => 1,
			'with_tables_data' => 1,
			'keyed' => $keyed
		]);
	}

	public function _get_database_structure(string $env) {
		$results = [];
		$array = $this->_explode_lines($this->_get_file_contents($this->_database_path($env.'/structure.txt')));
		for($i=0;$i<$this->_count($array);$i++) {
			$item = $this->_explode('=', $array[$i]);
			if($item[0] == 'tables') {
				if($this->_trim($item[1])) {
					$results['tables'] = $this->_explode('|', $item[1]);
				}
			}
		}
		return $results;
	}

	public function _get_database_table_columns($database, $table) {
		$results = [];
		$data = $this->_get_database_table_config_array($database, $table);
		for($i=0;$i<$this->_count($data);$i++) {
			$results[] = $data[$i]['name'];
		}
		return $results;
	}

	public function _get_database_table_config_array(string $database, string $table, array $opts = []) {
		$results = [];
		$data = $this->_explode_lines($this->_get_database_table_config_string($database, $table));
		for($i=0;$i<$this->_count($data);$i++) {
			$item = $this->_explode(':', $data[$i]);
			$results[$i] = [
				'name' => $item[0],
				'type' => $item[1],
				'data' => []
			];
		}
		return $results;
	}

	public function _get_database_table_config_string($database, $table) {
		return $this->_get_file_contents($this->_database_path($database.'/'.$table.'/structure.txt'));
	}

	public function _get_database_table_data($database, $tables) {
		$results = [];
		if(!$this->_is_array($tables)) {
			$tables = [$tables];
		}
		$allow = $this->_get_database_tables($database);
		for($i=0;$i<$this->_count($tables);$i++) {
			if($this->_in_array($tables[$i], $allow)) {
				$dbts = $this->_get_database_table_config_array($database, $tables[$i]);
				$dbtc = $this->_explode_lines($this->_get_database_table_dataString($database, $tables[$i]));
				for($b=0;$b<$this->_count($dbtc);$b++) {
					$tmp = [];
					$item = $this->_explode('|', $dbtc[$b]);
					for($j=0;$j<$this->_count($dbts);$j++) {
						$tmp[$dbts[$j]['name']] = $this->_set_type($dbts[$j]['type'], $item[$j]);
					}
					$results[] = $tmp;
				}
			} else {
				$this->_fail('Table [ '.$table.' ] not exists in database [ '.$database.' ]');
			}
		}
		return $results;
	}

	public function _get_database_table_data_all($database) {
		return $this->_get_database_table_data($database, $this->_get_database_tables($database));
	}

	public function _get_database_table_data_string($database, $table) {
		return $this->_get_file_contents($this->_database_path($database.'/'.$table.'/data.txt'));
	}

	public function _get_database_tables(string $database) {
		return $this->_get_database_structure($database)['tables'];
	}

	public function _get_date_time_zone($zone) {
		return new DateTimeZone($zone);
	}

	public function _get_dir_file($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

	public function _get_exported_string($list = []) {
		$ready = [
			'first' => array_key_exists('first', $list) ? $list['first'] : 0,
			's' => array_key_exists('s', $list) ? $list['s'] : "",
			'ss' => array_key_exists('ss', $list) ? $list['ss'] : "",
			'var' => array_key_exists('var', $list) ? $list['var'] : "",
			'without_numeric_keys' => array_key_exists('without_numeric_keys', $list) ? $list['without_numeric_keys'] : 0,
			'count' => array_key_exists('value', $list) ? count($list['value']) : 0,
			'index' => array_key_exists('index', $list) ? $list['index'] : 0,
			'level' => array_key_exists('level', $list) ? $list['level'] : 1,
			'space' => array_key_exists('space', $list) ? $list['space'] : 0,
			'prefix_key' => array_key_exists('prefix_key', $list) ? $list['prefix_key'] : '',
			'value' => array_key_exists('value', $list) ? $list['value'] : 0,
			'caption' => array_key_exists('caption', $list) ? $list['caption'] : '',
			'keytype' => "",
			'newline' => array_key_exists('newline', $list) ? $list['newline'] : "",
			'spacetab' => array_key_exists('spacetab', $list) ? $list['spacetab'] : "",
			'newspace' => array_key_exists('newspace', $list) ? $list['newspace'] : "",
			'key_to_indent' => array_key_exists('key_to_indent', $list) ? $list['key_to_indent'] : 0,
			'value_to_indent' => array_key_exists('value_to_indent', $list) ? $list['value_to_indent'] : 0,
			'quote' => array_key_exists('quote', $list) ? $list['quote'] : '"',
			'valuetype' => 'array',
			'escape_key' => array_key_exists('escape_key', $list) ? $list['escape_key'] : null,
			'index_prev' => 0,
			'rowseparator' => ",",
			'opentagsymbol' => array_key_exists('opentagsymbol', $list) ? $list['opentagsymbol'] : "[",
			'closetagsymbol' => array_key_exists('closetagsymbol', $list) ? $list['closetagsymbol'] : "]",
			'key_separator_value' => array_key_exists('key_separator_value', $list) ? $list['key_separator_value'] : ' => ',
		];
		$export = $ready;
		foreach($ready['value'] as $key => $value) {
			$export['key'] = $key;
			$export['value'] = $value;
			$export['newline'] = "\n";
			$export['keytype'] = $this->_get_type($export['key']);
			$export['valuetype'] = $this->_get_type($export['value']);
			if($export['keytype'] == 'integer') {
				if($export['without_numeric_keys'] == 1) {
					$export['key'] = '';
					$export['key_separator_value'] = '';
				}
			} else {
				if($export['key_to_indent']) {
					//
				} else {
					if($export['keytype'] == 'string') {
						$export['key'] = $export['quote'].$export['key'].$export['quote'];
					}
				}
			}
			if($export['valuetype'] == 'array') {
				$export['level'] = $export['level'] + 1;
				$export['s'] = "";
				$export['ss'] = "";
				for($j=0;$j<$export['level'];$j++) {
					if($j>0) {
						$export['s'].=$export['spacetab'];
					}
					$export['ss'].=$export['spacetab'];
				}
				if($export['count'] > 1 && $export['index_prev'] != $export['count'] - 1) {
					$export['rowseparator']=$ready['rowseparator'].$ready['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$ready['newline'].$export['newline'];
				}
				$export['nested'] = $this->_get_exported_string([
			    	'space' => $export['space'],
			    	'caption' => $export['caption'],
			    	'value' => $export['value'],
			    	'level' => $export['level'],
			    	'count' => count($export['value']),
			    	'index' => $export['index'],
			    	'prefix_key' => $export['prefix_key'],
			    	'spacetab' => $export['spacetab'],
			    	'key_to_indent' => $export['key_to_indent'],
			    	'index_prev' => 0,
					'opentagsymbol' => $export['opentagsymbol'],
					'closetagsymbol' => $export['closetagsymbol'],
					'without_numeric_keys' => $export['without_numeric_keys'],
					'value_to_indent' => $export['value_to_indent'],
					'quote' => $export['quote'],
					'escape_key' => $export['escape_key'],
			    ]);
				if(!$export['nested']) {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['prefix_key'].$export['key'];
					$export['var'].=$export['key_separator_value'];
					$export['var'].=$export['opentagsymbol'];
					$export['var'].=$export['closetagsymbol'];
					$export['var'].=$export['rowseparator'];
				} else {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['prefix_key'].$export['key'];
					$export['var'].=$export['key_separator_value'];
					$export['var'].=$export['opentagsymbol'];
					$export['var'].=$export['newline'];
					$export['var'].=$export['nested'];
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['closetagsymbol'];
					$export['var'].=$export['rowseparator'];
				}
				$export['level'] = $export['level'] - 1;
			} else {
				$export['s'] = "";
				$export['ss'] = "";
				for($j=0;$j<$export['level'];$j++) {
					if($j>0) {
						$export['s'].=$export['spacetab'];
					}
					$export['ss'].=$export['spacetab'];
				}
				switch($export['valuetype']) {
					case 'boolean':
						$export['value'] = $export['value'] ? 'true' : 'false';
		            break;
		            case 'integer':
		            	$export['value'] = $export['value'];
		            break;
		            case 'double':
		            	$export['value'] = $export['value'];
		            break;
		            case 'string':
		            	if($export['escape_key'] == $key) {
		            		$export['value'] = $this->_escape($export['value']);
		            	}
						if(!$export['value_to_indent']) {
							$export['value'] = $export['quote'].$export['value'].$export['quote'];
						}
		            break;
		            case 'NULL':
		            	$export['value'] = 'null';
		            break;
		        }
				if($export['count'] > 1 && $export['index_prev'] != $export['count'] - 1) {
					$export['rowseparator']=$ready['rowseparator'].$ready['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$ready['newline'].$export['newline'];
				}
				$export['var'].=$export['newspace'];
				$export['var'].=$export['ss'];
				$export['var'].=$export['prefix_key'].$export['key'];
				$export['var'].=$export['key_separator_value'];
				$export['var'].=$export['value'];
				$export['var'].=$export['rowseparator'];
			}
		}
		return $export['var'];
	}

	public function _get_items() {
		return $this->_get('this.items');
	}

	public function _get_local_array($where) {
		return $this->_get_returned_array_file('arrays', $where);
	}

	public function _get_local_form($where) {
		return $this->_get_returned_array_file('forms', $where);
	}

	public function _get_month($month) {
		$months = [
			1 => 'January',
			2 => 'February',
			3 => 'March',
			4 => 'April',
			5 => 'May',
			6 => 'June',
			7 => 'July',
			8 => 'August',
			9 => 'September',
			10 => 'October',
			11 => 'November',
			12 => 'December',
		];
		return $months[$month];
	}

	public function _get_name_last($str) {
		return basename($str);
	}

	public function _get_nested_items() {
		return $this->_nested($this->_get_items());
	}

	public function _get_non_alpha_numeric_characters() {
		return [
			"!",
			"@",
			"#",
			"&",
			"(",
			")",
			"-",
			"[",
			"{",
			"}",
			"]",
			":",
			";",
			"'",
			",",
			"?",
			"/",
			"*",
			"`",
			"~",
			"$",
			"^",
			"+",
			"=",
			"<",
			">",
			'"',
		];
	}

	public function _get_numbers($code = null, $type = 'symbol') {
		$codes =  [
				48 => [
					'hex' => '30',
					'symbol' => '0',
					'chars' => [
						0 => 48
					],
				],
				49 => [
					'hex' => '31',
					'symbol' => '1',
					'chars' => [
						0 => 49
					],
				],
				50 => [
					'hex' => '32',
					'symbol' => '2',
					'chars' => [
						0 => 50
					],
				],
				51 => [
					'hex' => '33',
					'symbol' => '3',
					'chars' => [
						0 => 51
					],
				],
				52 => [
					'hex' => '34',
					'symbol' => '4',
					'chars' => [
						0 => 52
					],
				],
				53 => [
					'hex' => '35',
					'symbol' => '5',
					'chars' => [
						0 => 53
					],
				],
				54 => [
					'hex' => '36',
					'symbol' => '6',
					'chars' => [
						0 => 54
					],
				],
				55 => [
					'hex' => '37',
					'symbol' => '7',
					'chars' => [
						0 => 55
					],
				],
				56 => [
					'hex' => '38',
					'symbol' => '8',
					'chars' => [
						0 => 56
					],
				],
				57 => [
					'hex' => '39',
					'symbol' => '9',
					'chars' => [
						0 => 57
					],
				],
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_paths($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	public function _get_paths_only($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	public function _get_ready_document(int $reset = null) {
		if($reset == UNDEFINED) {
			$reset = 0;
		}

		if($this->_has_more_opened_tags()) {
			$this->_fail('You have more opened tags than you have closed.');
		}
		if($this->_has_more_closed_tags()) {
			$this->_fail('You have more closed tags than you have opened.');
		}

		$items = $this->_get_nested_items();
		//$items = $this->_get_items();

		if($reset) {
			$this->_reset();
		}
		return $this->_build_document($items);
	}

	private function _get_returned_array_file($file, $where) {
		if(!$this->_is_array($where)) {
			$where = $this->_explode('.', $where);
		}
		$fileok = 0;
		for($i=0;$i<count($where);$i++) {
			if(!$fileok) {
				if($this->_is_file($this->_common_path($file.'/'.$where[$i].'.php'))) {
					$fileok = 1;
					$data = require($this->_common_path($file.'/'.$where[$i].'.php'));
				}
			} else {
				$data = $data[$where[$i]];
			}
			$file.='/'.$where[$i];
		}
		return $data;
	}

	public function _get_symbol_chars($code = null, $type = 'symbol') {
		$codes =  [
				33 => [
					'hex' => '21',
					'symbol' => '!',
					'chars' => [
						0 => 33
					],
				],
				34 => [
					'hex' => '22',
					'symbol' => '"',
					'chars' => [
						0 => 34
					],
				],
				35 => [
					'hex' => '23',
					'symbol' => '#',
					'chars' => [
						0 => 35
					],
				],
				36 => [
					'hex' => '24',
					'symbol' => '$',
					'chars' => [
						0 => 36
					],
				],
				37 => [
					'hex' => '25',
					'symbol' => '%',
					'chars' => [
						0 => 37
					],
				],
				38 => [
					'hex' => '26',
					'symbol' => '&',
					'chars' => [
						0 => 38
					],
				],
				39 => [
					'hex' => '27',
					'symbol' => "'",
					'chars' => [
						0 => 39
					],
				],
				40 => [
					'hex' => '28',
					'symbol' => '(',
					'chars' => [
						0 => 40
					],
				],
				41 => [
					'hex' => '29',
					'symbol' => ')',
					'chars' => [
						0 => 41
					],
				],
				42 => [
					'hex' => '2A',
					'symbol' => '*',
					'chars' => [
						0 => 42
					],
				],
				43 => [
					'hex' => '2B',
					'symbol' => '+',
					'chars' => [
						0 => 43
					],
				],
				44 => [
					'hex' => '2C',
					'symbol' => ',',
					'chars' => [
						0 => 44
					],
				],
				45 => [
					'hex' => '2D',
					'symbol' => '-',
					'chars' => [
						0 => 45
					],
				],
				46 => [
					'hex' => '2E',
					'symbol' => '.',
					'chars' => [
						0 => 46
					],
				],
				47 => [
					'hex' => '2F',
					'symbol' => '/',
					'chars' => [
						0 => 47
					],
				],
				58 => [
					'hex' => '3A',
					'symbol' => ':',
					'chars' => [
						0 => 58
					],
				],
				59 => [
					'hex' => '3B',
					'symbol' => ';',
					'chars' => [
						0 => 59
					],
				],
				60 => [
					'hex' => '3C',
					'symbol' => '<',
					'chars' => [
						0 => 60
					],
				],
				61 => [
					'hex' => '3D',
					'symbol' => '=',
					'chars' => [
						0 => 61
					],
				],
				62 => [
					'hex' => '3E',
					'symbol' => '>',
					'chars' => [
						0 => 62
					],
				],
				63 => [
					'hex' => '3F',
					'symbol' => '?',
					'chars' => [
						0 => 63
					],
				],
				64 => [
					'hex' => '40',
					'symbol' => '@',
					'chars' => [
						0 => 64
					],
				],
				91 => [
					'hex' => '5B',
					'symbol' => '[',
					'chars' => [
						0 => 91
					],
				],
				92 => [
					'hex' => '5C',
					'symbol' => '\\',
					'chars' => [
						0 => 92
					],
				],
				93 => [
					'hex' => '5D',
					'symbol' => ']',
					'chars' => [
						0 => 93
					],
				],
				94 => [
					'hex' => '5E',
					'symbol' => '^',
					'chars' => [
						0 => 94
					],
				],
				95 => [
					'hex' => '5F',
					'symbol' => '_',
					'chars' => [
						0 => 95
					],
				],
				96 => [
					'hex' => '60',
					'symbol' => '`',
					'chars' => [
						0 => 96
					],
				],
				123 => [
					'hex' => '7B',
					'symbol' => '{',
					'chars' => [
						0 => 123
					],
				],
				124 => [
					'hex' => '7C',
					'symbol' => '|',
					'chars' => [
						0 => 124
					],
				],
				125 => [
					'hex' => '7D',
					'symbol' => '}',
					'chars' => [
						0 => 125
					],
				],
				126 => [
					'hex' => '7E',
					'symbol' => '~',
					'chars' => [
						0 => 126
					],
				],
				127 => [
					'hex' => '7F',
					'symbol' => ' ',
					'chars' => [
						0 => 32
					],
				]
			];
			return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_time_in_milliseconds() {
		return time();
	}

	public function _get_type($element) {
		return gettype($element);
	}

	public function _has_more_opened_tags() {
		return $this->_get('static.unclosed_tags') > 0;
	}

	public function _has_more_closed_tags() {
		return $this->_get('static.unclosed_tags') < 0;
	}

	public function _get_unknown_chars($code = null, $type = 'symbol') {
		$codes =  [
			940 => [
				'hex' => '3ac',
				'symbol' => '',
				'chars' => [],
			],
		];
		return $code == null ? $codes : $codes[$code][$type];
	}

	public function _get_body_class() {
		return $this->_implode(' ', $this->_get('this.bodyclass'));
	}

	public function _get_exe_options($input) {
		$output = new stdClass;
		$output->tmp											= '';
		$output->is												= [
			'comment_line' => 0,
			'prepare' => 0,
			'headers' => 0,
			'top_namespace' => 0,
			'namespace' => 0,
			'namespace_name' => 0,
			'var' => 0,
			'use' => 0,
			'end' => 0,
			'static' => 0,
			'addition' => 0,
			'coma' => 0,
			'open_array' => 0,
			'close_array' => 0,
			'args' => 0,
			'open_args' => 0,
			'close_args' => 0,
			'open_body' => 0,
			'close_body' => 0,
			'function' => 0,
			'subtraction' => 0,
			'object' => 0,
			'concatenation' => 0,
			'concatenation_assign' => 0,
			'assign' => 0,
			'array_value' => 0,
			'equal' => 0,
			'not_equal' => 0,
			'identical' => 0,
			'not_identical' => 0,
			'greater_than' => 0,
			'less_than' => 0,
			'greater_than_or_equal_to' => 0,
			'less_than_or_equal_to' => 0,
			'division' => 0,
		];
		$output->may 											= [
			'comment_line' => 0,
			'namespace' => 0,
			'var' => 0,
			'end' => 0,
			'static' => 0,
			'addition' => 0,
			'coma' => 0,
			'open_array' => 0,
			'close_array' => 0,
			'args' => 0,
			'open_args' => 0,
			'close_args' => 0,
			'open_body' => 0,
			'close_body' => 0,
			'subtraction' => 0,
			'object' => 0,
			'concatenation' => 0,
			'concatenation_assign' => 0,
			'assign' => 0,
			'array_value' => 0,
			'equal' => 0,
			'not_equal' => 0,
			'identical' => 0,
			'not_identical' => 0,
			'greater_than' => 0,
			'less_than' => 0,
			'greater_than_or_equal_to' => 0,
			'less_than_or_equal_to' => 0,
			'division' => 0,
		];
		$output->prepare										= 0;
		$output->headers										= 0;
		$output->type											= '';
		$output->is_lower										= '';
		$output->is_upper										= '';
		$output->isString										= 0;
		$output->ident											= [];
		$output->ident_o										= "";
		$output->tmp_ident										= "";
		$output->tmp_ident_o									= "";
		$output->quote											= null;
		$output->contents										= '';
		$output->a												= null;
		$output->a_o											= null;
		$output->decimal										= null;
		$output->max											= $this->_count($input);
		$output->input											= $input;
		$output->chars_alpha_lower								= null;
		$output->chars_alpha_upper								= null;
		$output->chars_numbers									= null;
		$output->chars_control									= null;
		$output->chars_symbols									= null;
		$output->chars_unknown									= null;
		$output->flag											= [
			'public' => 0,
			'private' => 0,
			'protected' => 0,
		];
		$output->string											= "";
		$output->comment										= "";
		$output->comment_line									= 1;
		$output->hident											= 0;
		$output->bstring										= 0;
		$output->hstring										= 0;
		$output->lstring										= "";
		$output->hcontrol										= 0;
		$output->lcontrol										= "";
		$output->lstring_control								= "";
		$output->current										= 0;
		$output->line											= 1;
		return $output;
	}

	public function _get_include_contents($file) {
		return $this->_get_file_contents(_template_path($file.'.php'));
	}

	public function _get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	public function _gg_alpha($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_alpha_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_control($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_control_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_number($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_number_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_symbol($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _gg_symbol_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	public function _header($str) {
		return header($str);
	}

	public function _http_build_query($data, $separator = '&', $prefix = '') {
		return http_build_query($data, $prefix, $separator);
	}

	public function _include_exists($file) {
		return $this->_file_exists(_template_path($file.'.php'));
	}

	public function _is_double($element) {
		return is_double($element);
	}

	public function _is_file($file) {
		return is_file($file);
	}

	public function _is_float($element) {
		return is_float($element);
	}

	public function _is_integer($element) {
		return is_integer($element);
	}

	public function _is_numeric($element) {
		return is_numeric($element);
	}

	public function _is_admin() {
		return $this->_isset('static.is_admin') && $this->_get('static.is_admin');
	}

	public function _is_cached($file) {
		return $this->_file_exists($this->__disk('storage/interframework/cache/'.$this->_to_back_slash($file.'.'.$this->_get('static.doctype'))));
	}

	public function _is_closure($callback) {
		return is_object($callback) && ($callback instanceof Closure);
	}

	public function _is_int($element) {
		return $this->_is_integer($element);
	}

	public function _is_manual($key) {
		return $this->_isset('manual.'.$key) && $this->_get('manual.'.$key);
	}

	public function _is_same($a, $b) {
		return $this->_get($a, $this->_get($b));
	}

	public function _load_components($types) {
		if(!$this->_is_array($types)) {
			$types = [$types];
		}
		foreach($types as $type) {
			foreach([$type.'_require', $type.'_dynamic', $type.'_auto_view'] as $setch) {
				if(!$this->_isset('settings.'.$setch)) {
					foreach(['before', 'after'] as $place) {
						foreach($this->_get('static.resources.'.$place.'.'.$setch) as $comp) {
							if($type == 'css') {
								$this->_tag('link')->_attr('data-preload', true)->_attr('href', $comp)->_attr('rel', 'stylesheet')->_attr('type', 'text/css')->_tag();
							}
							elseif($type == 'js') {
								$this->_tag('script')->_attr('data-preload', true)->_attr('src', $comp)->_attr('type', 'text/javascript')->_tag();
							}
						}
					}
				}
			}
		}
		return $this;
	}

	public function _load_the_scripts_components() {
		if(!$this->_isset('settings.scripts')) {
			foreach($this->_get('static.resources.after.scripts') as $ajavascript) {
				$this->_tag('script')->_attr('preload', true)->_text($ajavascript)->_tag();
			}
			foreach($this->_get('static.resources.before.scripts') as $bjavascript) {
				$this->_tag('script')->_attr('preload', true)->_text($bjavascript)->_tag();
			}
		}
		return $this;
	}

	public function _log($msg) {
		$logs = $this->_mytime()." : ".$msg."\n";
		$logs.= $this->_get_file_contents($this->_storage('interframework/logs/log.txt'));
		$this->_fwrite($this->_storage('interframework/logs/log.txt'), $logs);
	}

	public function _make_include($file, $contents = '') {
		return $this->_fmk(_template_path($file), $contents);
	}

	public function _manual($key, $value = null) {
		return $this->_set('manual.'.$key, $value === null ? 1 : $value);
	}

	public function _mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->_in_array($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

	public function _mkdir($dir, $mode = 493, $recursive = true) {
		if(!$this->_is_dir($dir)) {
			if(!mkdir($dir, $mode, $recursive)) {
				return 0;
			}
		}
		return 1;
	}

	public function _mkdirs(array $array, $path = '') {
		$path = $path ? $path.$this->_back_slash() : '';
		foreach($array as $row) {
			if($this->_mkdir($path.$row['name'], isset($row['mode']) ? $row['mode'] : 0777)) {
				if(isset($row['files']) && !empty($row['files'])) {
					foreach($row['files'] as $file) {
						$this->_fwrite($path.$row['name'].$this->_back_slash().$file['name'], array_key_exists('contents', $file) ? $file['contents'] : '');
					}
				}
				if(isset($row['subfolders']) && !empty($row['subfolders'])) {
					$this->_mkdirs($row['subfolders'], $path.$row['name']);
				}
			} else {
				return 0;
			}
        }
        return 1;
	}

	public function _fmk($file, $contents = '', $lock = false) {
		if(!$this->_file_exists($file)) {
			return $this->_fwrite($file, $contents, $lock);
		} else {
			return 0;
		}
	}

	public function _mkfileforce($file, $contents = '') {
		if(!$this->_file_exists($file)) {
	   		if(!$this->_is_dir($this->_get_dir_file($file))) {
	   			if($this->_mkdir($this->_get_dir_file($file))) {
	   				$this->_fmk($file, $contents);
	   			}
	   		} else {
	   			$this->_fmk($file, $contents);
	   		}
	   	} else {
	   		if($this->_delete_file($file)) {
	   			$this->_fmk($file, $contents);
	   		}
	   	}
		return 1;
	}

	public function _monitor() {
		$this->_include('_common/configs');

		$this->_set(
			'view', $this->_to_back_slash($this->_get('route.controller').'/'.$this->_get('route.method'))
		);

		$this->_set_after_or_before('after');

		if($this->_is_manual('root_view')) {
			$this->_include($this->_get('manual.root_view'), $this->_data);
		} else {
			$this->_include($this->_get('view'), $this->_data);
		}

		$this->_set_text($this->_finalize(1));
		$this->_set('static.contents.before', 1);
		// ------------------------------------------
		$this->_set_after_or_before('before');
		$this->_set_start_code_space_level(0);
		$this->_include('_common/resources');
		$this->_set_after_or_before('after');
		$this->_resources();
		$this->_set_after_or_before('before');

		if($this->_request->isAjax()) {
			$this->_load_components('css');
			$this->_load_components('js');
			$this->_load_the_scripts_components();
			$this->_text($this->_get('text'));
		} else {
			$this->_text('<!-- Brostά Interframework Author: John Stamoutsos -->');
			$this->_tag('doctype')->_attr('html')->_tag();
			$this->_tag('html')->_attr('class', 'cm-html')->_attr('lang', 'en');
				$this->_tag('head');
					$this->_tag('meta')->_attr('charset', 'utf-8')->_tag();
					$this->_tag('meta')->_attr('name', 'viewport')->_attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->_tag();
					$this->_tag('meta')->_attr('httpequiv', 'Content-Type')->_attr('content', 'text/html; charset=UTF-8')->_tag();
					$this->_tag('title')->_text($this->_get('title'))->_tag();
					$this->_tag('meta')->_attr('id', 'domain')->_attr('content', 'My domain')->_tag();
					$this->_load_components('css');
				$this->_tag();
				$this->_tag('body')->_attr('class', 'cm-body');
					$this->_tag('div')->_attr('id', 'cm-container')->_attr('class', 'cm-container');
						$this->_tag('div')->_attr('id', 'cm-contents')->_attr('class', 'cm-contents');
							$this->_text($this->_get('text'));
						$this->_tag();
					$this->_tag();
					$this->_load_components('js');
					$this->_load_the_scripts_components();
				$this->_tag();
			$this->_tag();
		}
		return 1;
	}

	public function _mytime() {
		$datetime = new DateTime("now", $this->_get_date_time_zone('Europe/Athens'));
		$datetime->setTimestamp($this->_get_time_in_milliseconds());
		$time = $datetime->format('Y/d/m-H:i:s');

		$time = $this->_explode('-', $datetime->format('Y/d/m-H:i:s'));

		$date = $time[0];
		$date = $this->_explode('/', $date);

		$year = (int)$date[0];
		$day = (int)$date[1];
		$month = (int)$date[2];

		$time = $time[1];
		$time = $this->_explode(':', $time);

		$our = $time[0];
		$minutes = $time[1];
		$seconds = $time[2];

		return $day.' '.$this->_get_month($month).' '.$year.' - '.$our.':'.$minutes.':'.$seconds;
	}

	public function _nested(array $data = []) {
		$level = 0;
	    $prev = 0;
	    foreach($data as $key => $item) {
			if($level && $item['level'] > $level) {
				$data[$prev]['items'][] = $data[$key];
				unset($data[$key]);
			} else {
				$prev = $key;
				$level = $item['level'];
			}
	    }
		foreach($data as $key => $item) {
	        if(!$this->_is_empty($data[$key]['items'])) {
	            $data[$key]['items'] = $this->_nested($item['items']);
	        }
	    }
	    return $data;
	}

	public function _nested_fields($array) {
		foreach($array as $row) {
			$this->_tag('div')->_attr('class', 'cm-tree-row');
				$this->_tag('div')->_attr('class', 'cm-tree-contents');
					$this->_tag('div')->_attr('class', 'cm-tree-cyrcle')->_tag();
					$this->_tag('div')->_attr('class', 'cm-tree-label')->_text($row['name']);
						$this->_tag('div');
							$this->_tag('div')->_text('My row')->_tag();
							$this->_tag('div')->_text('My row')->_tag();
							$this->_tag('div')->_text('My row')->_tag();
							$this->_tag('div')->_text('My row')->_tag();
							$this->_tag('div')->_text('My row')->_tag();
							$this->_tag('div')->_text('My row')->_tag();
						$this->_tag();
					$this->_tag();
					if(isset($row['items'])) {
						$this->_tag('div')->_attr('class', 'cm-tree');
							$this->_nested_fields($row['items'], 0);
						$this->_tag();
					}
				$this->_tag();
			$this->_tag();
		}
	}

	public function _new_line() {
		return "\n";
	}

	public function _new_tag() {
		return [
			'defineds' 					=> [],
			'tag'						=> '',
		    'attr'						=> [],
		    'text'						=> '',
		    'append_before_tag' 		=> [],
		    'append_after_tag'			=> [],
		    'append_before_text'		=> [],
		    'append_after_text'			=> [],
			'nested'					=> null,
			'contents'					=> '',
			'open_tag'					=> '<',
			'close_tag'					=> '>',
			'tag_after'					=> null,
			'tag_before'				=> null,
			'items'						=> [],
		    'index'						=> 0,
		    'level'						=> 0,
		    'cspace'					=> 0,
			'start_code_space_level'	=> 0,
		];
	}

	public function _on($event, $callback) {
		return $this->_push('static.on', [
			'event_name' => $event,
			'callback' => $callback
		]);
	}

	public function _parse($output) {
		if($output->isString) {
			$m = '_gg_'.$output->type.'_string';
		} else {
			$m = '_gg_'.$output->type;
		}
		return $this->$m($output);
	}

	public function _path_is(string $name, int $num = 0) {
		return $this->_isset('static.url_paths.'.$num) && $this->_get('static.url_paths.'.$num, $name);
	}

	public function _posted() {
		$this->_set('tag.defineds.posted', 1);
        return $this;
	}

	public function _process_text_to_ascii($string, &$offset) {
		$code = ord($this->_substr($string, $offset, 1)); 
	    if($code >= 128) {
	        if($code < 224) {
	        	$bytesnumber = 2;
	        } else {
	        	if($code < 240) {
	        		$bytesnumber = 3;
	        	} else {
	        		if($code < 248) {
	        			$bytesnumber = 4;
	        		}
	        	}
	        }
	        $codetemp = $code - 192 - ($bytesnumber > 2 ? 32 : 0) - ($bytesnumber > 3 ? 16 : 0);
	        for($i = 2; $i <= $bytesnumber; $i++) {
	            $offset ++;
	            $code2 = ord(substr($string, $offset, 1)) - 128;
	            $codetemp = $codetemp * 64 + $code2;
	        }
	        $code = $codetemp;
	    }
	    $offset += 1;
	    if($offset >= strlen($string)) {
	    	$offset = -1;
	    }
	    return $code;
	}

	public function _text_to_ascii($text) {
		$results = "";
		$offset = 0;
		while($offset >= 0) {
			if($results) {
				$results.=" ";
			}
		    $results.=$this->_process_text_to_ascii($text, $offset);
		}
		return $results;
	}

	public function _set_path($path) {
		return $this->_set('static.path', $path);
	}

	public function _get_path() {
		return $this->_get('static.path');
	}

	public function _static($key) {
		return $this->_get('static.'.$key);
	}

	public function _disk($str = '') {
		$path = $this->_rtrim($this->_get_path(), $this->_back_slash());
		if($str) {
			$path = $path.$this->_back_slash().$this->_to_back_slash($str);
		}
		return $path;
	}

	public function _project($str = '') {
		return $this->_public_path('Brosta/'.$str);
	}

	public function _public_path($str = '') {
		return $this->_disk('public/'.$str);
	}

	public function _database_path($str = '') {
		return $this->_storage('Interframework/Database/'.$str);
	}

	public function _common_path($str = '') {
		return _template_path('_common/'.$str);
	}

	public function _template_path($str = '') {
		if($this->_is_admin()) {
			return $this->_disk('views/admin/'.$str);
		}
		return $this->_disk('views/apartements/'.$str);
	}

	public function _assets_path($str = '') {
		return $this->_disk('public/assets/'.$str);
	}

	public function _vendor_path($str = '') {
		return $this->_disk('vendor/'.$str);
	}

	public function _views_path($str = '') {
		return $this->_disk('views/'.$str);
	}

	public function _request_all() {
		return $this->_get('request.all');
	}

	public function _require_text_script($script) {
		$this->_push('static.resources.'.$this->_get('after_or_before').'.scripts', $script);
		return $this;
	}

	public function _reset() {
		$this->_delete('this');
		$this->_delete('keep');

		$this->_set('this', [
				'items'						=> [],
				'count'						=> 0,
				'index'						=> 0,
				'level'						=> 0,
				'waiting'					=> 0,
				'start_code_space_level'	=> $this->_get('static.start_code_space_level'),
			]
		);

		$this->_set('keep', []);
		$this->_set('tag', $this->_new_tag());
	}

	public function _resources() {
		if($this->_get('view')) {
			$css = 'views/'.$this->_get('view').'.css';
			$js = 'views/'.$this->_get('view').'.js';
			if(!$this->_file_exists($this->_assets_path($css))) {
				if($this->_mkfileforce($this->_assets_path($css))) {
					$this->_require($css, 'auto_view');
				}
			} else {
				$this->_require($css, 'auto_view');
			}
			if(!$this->_file_exists($this->_assets_path($js))) {
				if($this->_mkfileforce($this->_assets_path($js))) {
					$this->_require($js, 'auto_view');
				}
			} else {
				$this->_require($js, 'auto_view');
			}
		}
	}

	public function _results($output) {
		if($output->input) {
			$output->decimal = $output->input[$output->current];
			unset($output->input[$output->current]);
			$output = $this->_character($output);
			$output = $this->_parse($output);
			$output->current++;
			$output = $this->_results($output);
		}
		return $output;
	}

	public function _send() {
		$this->_echo($this->_get('text'));
	}

	public function _set($key, $value) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($this->_collection_type_is('object')) {
			
		} else {
			if(!$this->_key_in_array($base, $this->_memory)) {
				$this->_memory[$base] = [];
			}
			if($count > 0) {
				$keys[$count] = $value;
				$data = $this->_array_sm($keys);
				$this->_memory[$base] = $this->_array_replace($this->_memory[$base], $data);
			} else {
				$this->_memory[$base] = $value;
			}
		}
		return $value;
	}

	public function _set_after_or_before($switch) {
		$this->_set('after_or_before', $switch);
	}

	public function _set_start_code_space_level($level) {
		return $this->_set('this.start_code_space_level', $level);
	}

	public function _get_start_code_space_level() {
		return $this->_get('this.start_code_space_level');
	}

	public function _cspace($exp) {
		$this->_set('tag.cspace', $exp);
		return $this;
	}

	public function _cspace_lock($exp) {
		$this->_set('static.cspace_lock', $exp);
		return $this;
	}

	public function _set_text($text) {
		$this->_set('text', $text);
	}

	public function _set_text_lined($set) {
		return $this->_set('static.tag.text_lined', $set);
	}

	public function _set_type($type, $value) {
		switch($type) {
			case'int':
				$value = (int)$value;
			break;
			case'string':
				$value = (string)$value;
			break;
		}
		return $value;
	}

	public function _settings($setting) {
		return $this->_get('settings.'.$setting);
	}

	public function _slash_and_dot_to_back_slash($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_back_slash(), $str), $this->_back_slash());
	}

	public function _slash_and_dot_to_dash($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], '_', $str), $this->_url_s());
	}

	public function _slash_and_dot_to_space($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], ' ', $str), $this->_url_s());
	}

	public function _slash_and_dot_to_url_s($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_url_s(), $str), $this->_url_s());
	}

	public function _slash_to_dot($str) {
		return $this->_trim($this->_replace(['/', '\\'], '.', $str), '.');
	}

	public function _space($number) {
		return $this->_get_spaces_by_level($number, " ");
	}

	public function _space_like_tab($number) {
		return $this->_get_spaces_by_level($number, "	");
	}

	public function _space_to_dash($str) {
		return $this->_replace(' ', '-', $str);
	}

	public function _storage($str = '') {
		return $this->_disk('storage/'.$str);
	}

	public function _storage_copy($source, $destination) {
		return $this->_copy_dir(
			$this->_storage($source), $destination
		);
	}

	public function _str_trans($one, $two) {
		return strtr($one, $two);
	}

	public function _string_length($str) {
		return strlen($str);
	}

	public function _style($data) {
		$this->_set('tag.attr.style', $data);
		return $this;
	}

	public function _style_to_file($style, $class) {
		$data = $style;
    	$contents = '';
		$class = $this->_trim($class);
		if($this->_substr($class, 0, 2) == 'cm') {
			$file_name = 'app.css';
		}
		elseif($this->_substr($class, 0, 2) == 'sf') {
			$file_name = 'views/'.$this->_get('view').'.css';
			$start.= $this->_space_to_dash($this->_slash_and_dot_to_space($this->_get('view')));
		} else {
			return '';
		}

		$style = $this->_trim($data);
		$style = $this->_replace_spaces_with_one($this->_replace($this->_new_line(), ' ', $style));
		$style = $this->_trim($style);
		if(!$this->_last_in($style, '}') && !$this->_last_in($style, ';')) {
			$style.=';';
		}
		if(!$this->_contains_in($style, '{') && !$this->_contains_in($style, '}')) {
			$style = $this->_explode(';', $style);
			foreach($style as $value) {
				if($value !== '') {
					$contents.=$this->_new_line().$this->_space(1).$this->_trim($value).';';
				} else {
					$contents.=$this->_new_line();
				}
			}
			$start = '.';
			$contents = $start.$this->_implode(" .", $this->_explode(" ", $this->_replace_spaces_with_one($class)))." {".$contents."}".$this->_new_line();
			if(!$this->_contains_in($this->_get_file_contents($this->_assets_path($file_name)), $contents)) {
				$this->_file_append_to_top($this->_assets_path($file_name), $contents);
			}
		}
		return $contents;
	}

	public function _syntax_error($error, $line = 0) {
		$this->_fail('SYNTAX ERROR: '.$error.' on line '.$line);
	}

	public function _system_all() {
		return $this->_memory;
	}

	public function _tab_space($number) {
		return $this->_get_spaces_by_level($number, "\t");
	}

	public function _table($table) {
		return new Database($table);
	}

	public function _tag($tag = null) {
		$this->_document();
		if(!$tag) {
			$this->_set('static.unclosed_tags', $this->_get('static.unclosed_tags') - 1);
			$this->_chkeep();
			if($this->_get('this.level') > 0) {
				$this->_set('this.level', $this->_get('this.level') - 1);
			}
		} else {
			$this->_set('static.unclosed_tags', $this->_get('static.unclosed_tags') + 1);
			if($this->_get('this.level') >= 0) {
				$this->_set('this.level', $this->_get('this.level') + 1);
			}

			$this->_set('this.waiting', 1);
			$this->_set('tag.tag', $this->_remove_spaces($tag));
		}
		return $this;
	}

	public function _text($text = null, $line = 0) {
		if($this->_is_object($text)) {
			return $this;
		}
		if($text !== null) {
			if($this->_get('tag.tag') && !$line && $this->_get('static.tag.text_lined', 0)) {
				$this->_set('tag.text', $this->_get('tag.text').$text);
			} else {
				$this->_tag('untaged');
					if($line || $this->_get('static.tag.text_lined', 1)) {
						$this->_set('tag.fake_line', 1);
					}
					$this->_set('tag.text', $text);
				$this->_tag();
			}
		}
		return $this;
	}

	public function _title(string $string) {
		return $this->_set('title', $string);
	}

	public function _to_base($key) {
		if($this->_first_in($key, '.') || $this->_last_in($key, '.') || $this->_contains_in($key, '..')) {
	    	$this->_fail('FATAL ERROR: WRONG COLLECTION KEY SKELETON FOR [ '.$key.' ]');
	   	}
    	$key = $this->_lower($key);
    	$key = $this->_explode_dot($key);
		$data_key = $key[0]; unset($key[0]);
		$key =  $this->_array_zero($key);
    	return ['keys' => $key, 'base' => $data_key];
	}

	public function _to_back_slash($str) {
		return $this->_trim($this->_replace(['/', '\\'], $this->_back_slash(), $str), $this->_back_slash());
	}

	public function _to_url_s($str = '') {
		return $this->_trim($this->_replace(['/', '\\'], '/', $str), '/');
	}

	public function _ucfirst($str) {
		return ucfirst($str);
	}

	public function _underscore_to_upercase($name) {
		$names = $this->_trim($name, '_');
	   	$names = $this->_explode('_', $name);
	   	$newName = '';
	   	foreach($names as $name) {
	   		$newName.=$this->_ucfirst($name);
	   	}
	   	return $newName;
	}

	public function _update_memory($data) {
		$this->_memory = $this->_array_replace($this->_memory, $data);
	}

	public function _upper_to_underscore($string) {
		return $this->_lower(
	    	$this->_preg_replace('/(.)([A-Z])/', '$1_$2', $string)
	    );
	}

	public function _url($extend = '', $args = [], $replace = []) {
		return $this->_request->url($extend, $args, $replace);
	}

	public function _url_s() {
		return '/';
	}

	public function _fix_prefix_app(string $str) {
		return '_'.$this->_ltrim($str, '_');
	}

	public function _growth() {
		return new Growth();
	}

	public function __call($method, $arguments) {
		return App::call_function($method, $arguments);
	}

}
