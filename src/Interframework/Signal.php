<?php

define('LICENSE_ID', 'UZ3I-J2MB-9WXC-IXRT-KDL2');
define('NOTHING', '60371014510810961621243511712512340101105');

class Signal {

	private $_is_init;
	public $_id = 'app';
	private $_ram = [
		'app' => [],
	];

	public function _open(string $path = '', $options = []) {

		if(!$this->_is_opened()) {

			// Set the first load ram
			$this->_reset_ram($path);
			// -----------------

			// Set the first load client configs
			$options = $this->_set_open_configs($options);
			// -----------------

			// check if local
			$this->_load_config($options);
			// -------------------

			$this->_procedural('boot');

			// classes management
			if($this->_config('with_vendor')) {
				$this->_vendor_ready() ? $this->_action('plugins.vendor.composer') : $this->_fail('with_vendor', 'can not be load');
			}

			$this->_action('plugins.request.guzzle', [
				'method' => 'GET',
				'url' => 'https://api.github.com/repos/guzzle/guzzle'
			]);

			if($this->_config('request_from_globals')) {
				// make new request from global vars
				$this->_request_from_globals();
				// ----------------------------
			} else {
				// make new request from local vars
				$this->_request_from_local();
				// ----------------------------
			}

			if($this->_config('auto_route')) {
				$this->_action('plugins.route.auto');
			}

			// -----------------
			if($this->_route(0, $this->_get('static.route.grower'))) {
				if($this->_route(1, 'install')) {
					if($this->_include_exists('install')) {
						$this->_include('provider/install');
					} else {
						$this->_install();
					}
				}
			} else {
				if($this->_route_exists($this->_get('route.controller'))) {
					
				}
			}
		}

	}

	public function _procedural($ichnos) {
		$ichnos = $this->_get('this.procedural.'.$this->_underscore_to_dot($ichnos));
		for($i=0;$i<$this->_count($ichnos);$i++) {
			$ichnos[$i]();
		}
	}

	public function _ready($what) {
		return $this->_get('ready.'.$what);
	}

	public function _action(string $action, array $arguments = []) {
		return $this->_call_class_method('actions.'.$action, $arguments);
	}

	public function _flocal($what) {
		return $this->_get('flocal.'.$what);
	}

	public function _fsymban($what) {
		return $this->_get('fsymban.'.$what);
	}

	public function _vendor_ready() {
		if($this->_ready('vendor')) {
			return 1;
		}

		if($this->_flocal('vendor')) {
			if(!$this->_is_dir($this->_disk('language'))) {
				$this->_mkdir($this->_disk('language'));
			}

			if($this->_file($this->_provider_path('composer.json'))) {
				$this->_require_once($this->_provider_path('vendor/autoload.php'));
				$this->_set('ready.vendor', 1);
			} else {
				if($this->_make_file_force($this->_provider_path('composer.json'), $this->_settings('vendor'))) {
					if($handle = $this->_operating('-command cd '.$this->_provider_path().'; composer install;')) {
						return $this->_redirect($this->_url());
					} else {
						$this->_fail('vendor', 'Provider FILE NOT FOUND : " vendor/autoload.php "');
					}
				}
			}
		} else {
			if($this->_fsymban('vendor')) {
				$this->_procedural('vendor');
			}
		}

		return 1;
	}

	public function _algorithms() {
		return $this->_explode(',', $this->_remove_spaces('

			static,
			settings,
			actions,
			routes,
			config,
			title,
			display,
			this,
			flocal,
			fsymban'

		));
	}

	private function _reset_ram($path = '') {
		if($this->_ready('ram')) {
			return 1;
		}

		// set local path
		$this->_root($path);
		// -----------------

		$static = $this->_require_local($this->_provider_path('config/client.php'));

		foreach($this->_algorithms() as $algorithm) {
			$this->_set('this' == $algorithm ? 'static.'.$algorithm : $algorithm, $static[$algorithm]);
		}

		// set local path
		$this->_root($path);
		// -----------------

		$this->_reset_this();

		$this->_set('ready.ram', 1);

	}

	public function _set_open_configs(array $array) {
		foreach($array as $key => $value) {
			if($this->_is_numeric($value)) {
				if((int)$this->_config($key) !== 1) {
					$this->_fail('open_configs', 'logic " '.$key.' " not found.');
				}
			} else {
				$this->_fail('configuration', 'Configuration must be a numeric 0 or 1');
			}
		}
		return $array;
	}

	private function _config($key, $value = null) {
		if($value === 0 || $value === 1 || $value === null) {
			if($value === null) {
				return $this->_get('config.'.$key, 1);
			} else {
				return $this->_set('config.'.$key, $value);
			}
		} else {
			$this->_fail('argument', 'Unexpected config SET from key. Reason: Argument 2 must be an integer 0 or 1');
		}
	}

	public function _is_opened() {
		return $this->_get('static.is_opened', 1);
	}

	private function _route_exists($controller) {
		return $this->_in_array($controller, $this->_get('routes'));
	}

	private function _transfer($key) {
		$data = $this->_get($key);
		$this->_delete($key);
		return $data;
	}

	private function _load_config($config = []) {
		
	}

	private function _brosta_encode($data) {
		return $this->_json_encode($data);
	}

	private function _brosta_decode($data) {
		return $this->_json_decode($data);
	}

	private function _get_root_document($ichnos = null) {

		$root_document = $this->_get('static.root_document');

		if($ichnos === null) {
			return $this->_get('static.root_document');
		}

		return $ichnos;
	}

	private function _set_root_document($external) {
		if($this->_is_string($external)) {
			$external = $this->_brosta_encode($external);
			$external = $this->_brosta_decode($external);
		} else {
			$external = $external;
		}

		if($external) {
			if($this->_set('root.root_document', $external)) {
				return 1;
			}
		}
		return 0;
	}

	private function _is_function($data) {
		return is_callable($data);
	}

	private function _get_object_info($type, $point, $trace) {
		//
	}

	public function _is_class_method($class, $name) {
		//
	}

	private function _install() {

		if(!$this->_gp('type')) {
			return $this->_redirect($this->_url('/install', ['type' => 'PHP']));
		}

		$file_name = 'code_'.$this->_gp('type').'.php';

		if($this->_file($this->_disk('language/'.$file_name))) {
			$data = $this->_require_local($this->_disk('language/'.$file_name));
		} else {
			if($this->_config('with_local')) {
				if($this->_copy_file($this->_provider_path('language/'.$file_name), $this->_disk('language/'.$file_name))) {
					return $this->_redirect($this->_url('/install', ['type' => $this->_gp('type')]));
				} else {
					$this->_delete_path($this->_disk('language'));
				}
				if($this->_file($this->_disk('provider/language/'.$file_name))) {
					$data = $this->_require_local($this->_disk('provider/language/'.$file_name));
				}
			}
		}

		if($this->_is_function($data)) {
			$callback = $data;
			if($this->_config('on_construct')) {

				$page = $this->_new([
					'title' => $this->_get_title(),
					'standard_style_css' => $this->_get_standard_style_css(),
					'type' => $this->_gp('type'),
					'types' => [
						'javascript',
						'c++',
						'Android',
						'jQuery',
						'PHP',
					],
					'fields' => [
						[
							'label' => 'Application mode',
							'caption' => 'app_mode',
							'type' => 'select',
							'select_type' => '',
							'value' => [
								[
									'name' => 'client',
								],
								[
									'name' => 'server',
								],
							],
							'default_value' => '',
						],
						[
							'label' => 'OS Version',
							'caption' => 'os_version',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => '1.0',
						],
						[
							'label' => 'Namespace name',
							'caption' => 'with_namespace',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => '',
						],
						[
							'label' => 'Class name',
							'caption' => 'with_class',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => 'Signal',
						],
						[
							'label' => 'Methods prefix',
							'caption' => 'methods_prefix',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => '_',
						],
						[
							'label' => 'Helpers prefix',
							'caption' => 'helpers_prefix',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => 'os_',
						],
						[
							'label' => 'Standard style css',
							'caption' => 'standard_style_css',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_standard_style_css()
						],
						[
							'label' => 'Standard js',
							'caption' => 'standard_js',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_standard_js()
						],
						[
							'label' => 'App file',
							'caption' => 'app_file',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_file_contents($this->_disk('provider/external.php'))
						],
					],
				]);

				$this->_on('construct', $this->_bind($callback, $this->_signal([])));

				$this->_head_style($page->standard_style_css);

				$this->_add_header();

				$this->_tag('div')->_attr('id', 'container')->_attr('class', 'cm-container');
					$this->_tag('div')->_attr('id', 'contents')->_attr('class', 'cm-contents');

						if($page->type) {
							$this->_tag('form')->_attr('name', 'generator')->_attr('method', 'post')->_attr('action', $this->_url('/my_cookie_id/install', ['type' => $page->type]));
						}

						$this->_tag('div')->_attr('class', 'cm-contents-top');
							$this->_tag('div')->_attr('class', 'cm-box');
								$this->_tag('div')->_attr('class', 'cm-page-nav');
									$this->_tag('div')->_attr('class', 'cm-inline');
										$this->_tag('div')->_attr('class', 'cm-link cm-title')->_text('Export main app as:')->_tag();
										if($page->count('types')) {
											foreach($page->get('types') as $type) {
												$this->_tag('a')->_attr('href', $this->_url('/', ['type' => $type]))->_attr('class', 'cm-link'.($page->type == $type ? ' cm-active' : ''))->_text($type)->_tag();
											}
										}
									$this->_tag();
								$this->_tag();
							$this->_tag();
						$this->_tag();

						$this->_tag('div')->_attr('class', 'cm-contents-middle');
							$this->_tag('div')->_attr('class', 'cm-row');
								$this->_tag('div')->_attr('class', 'cm-box');
									if($page->type && $page->count('fields')) {
										foreach($page->fields as $field) {
											$this->_field($field);
										}
										$this->_button_submit('Build');
									}
								$this->_tag();
							$this->_tag();
						$this->_tag();

						if($page->type) {
							$this->_tag();
						}

					$this->_tag();
				$this->_tag();
			}

			$this->_close();

		}

	}

	private function _unmin($data, $type = 'css') {
		$contents = "";

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
			$contents = $start.$this->_implode(" .", $this->_explode(" ", $this->_replace_spaces_with_one($type)))." {".$contents."}".$this->_new_line();
		} else {
			
		}

		return $contents;
	}

	private function _mode(string $mode_name, $data = null) {
		
	}

	private function _token($id = null) {
		if($id) {
			$this->_set('static.token', $id);
		} else {
			return $this->_get('static.token');
		}
	}

	private function _token_ok($id) {
		return $this->_token() === $id;
	}

	private function get_client($key) {
		return $this->_get('static.client.'.$key);
	}

	private function _client_is_browser($array) {
		if($this->_isset('client.is_browser')) {
			return 0;
		}
	}

	private function _www($url = null) {
		return $url === null ? $this->_get('static.www') : $this->_set('static.www', $url);
	}

	private function _reset_this() {

		$resources = $this->_get('this.resources');
		$this->_delete('this.items');
		$this->_set('this', $this->_get('static.this'));
		if($resources) {
			$this->_set('this.resources', $resources);
		}
		$this->_set('tag', $this->_new_tag());
	}

	private function _on($event, $callback) {
		$this->_push('this.on.'.$event, $callback);
	}


	private function _get_template() {
		if(!$this->_isset('static.template.name')) {
			return $this->_set('static.template.name', 'default');
		}
		return $this->_get('static.template.name');
	}

	private function _set_template($name) {
		return $this->_set('static.template.name', $name);
	}

	private function _request_from_globals() {
		$this->_request([
			'get' => $_GET,
			'post' => $_POST,
			'files' => $_FILES,
			'cookie' => $_COOKIE,
			'server' => $_SERVER,
		]);
	}

	private function _request_from_local() {
		$this->_request([
			'get' => $this->_get('request.get'),
			'post' => $this->_get('request.post'),
			'files' => $this->_get('request.files'),
			'cookie' => $this->_get('request.cookie'),
			'server' => $this->_get('request.server'),
		]);
	}

	private function _add_header() {
		$this->_tag('div')->_attr('id', 'header')->_attr('class', 'cm-header');
			$this->_tag('div')->_attr('class', 'cm-header-container');
				$this->_tag('div')->_attr('class', 'cm-header-logo-container');
					$this->_tag('a')->_attr('href', $this->_url());
						$this->_tag('img')->_attr('src', '/assets/img/logow.png')->_class('cm-header-logo')->_tag();
					$this->_tag();
				$this->_tag();
			$this->_tag();
		$this->_tag();
	}

	private function _unset($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {
		foreach($replaces as $key => $value) {
			if(!$stop && !$this->_is_array($unset_key) && $key == $unset_key) {
				$stop = 1;
			} else {
				if(!$this->_array_key($key, $results)) {
					$results[$key] = [];
				}
				if($this->_is_array($value)) {
					if($lock == $level) {
						$unlock = 1;
					}
					if(!$stop) {
						if($unlock) {
							if($this->_array_key($key, $unset_key)) {
								$unset_key = $unset_key[$key];
								if(!$this->_is_array($unset_key)) {
									unset($value[$unset_key]);
									$stop = 1;
								}
							} else {
								$lock = $level;
								$unlock = 0;
							}
						}
					}
					$level++;
					$value = $this->_unset($unset_key, $value, $results[$key], $level, $lock, $stop, $unlock);
					$level--;
				}
				$results[$key] = $value;
			}
		}
		return $results;
	}

	private function _path_to_array($path, $sep = '/') {
		return $this->_explode($sep, $path);
	}

	private function _button_submit($text) {
		$this->_tag('div')->_attr('class', 'cm-field cm-clearfix');
			$this->_tag('button')->_attr('type', 'submit')->_class('cm-btn cm-form-btn cm-right')->_text($text)->_tag();
		$this->_tag();
	}

	private function build_class($class) {
		$editor = $this->face('php');
		foreach($class->get('properties') as $property) {
			$editor->tag('property');
					foreach($property as $key => $value) {
						$editor->attr($key, $value);
					}
				$editor->tag();
				$editor->enter();
			}
			foreach($class->get('methods') as $row) {
				$editor->tag('function');
					foreach($row as $key => $value) {
						if($key == 'body') {
							$value = $this->ascii_to_text($value);
						}
						$editor->attr($key, $value);
					}
				$editor->tag();
				$editor->enter();
			}
		$editor->tag();
		return '<?php'.$this->new_line().$editor->get_document().$this->new_line().'?>';
	}

	private function _field($field) {
		if($field['type'] == 'input') {
			$this->_tag('div')->_attr('class', 'cm-field');
				$this->_tag('div')->_class('cm-field-container cm-label-prefixed');
					$this->_tag('div')->_class('cm-label')->_text($field['label'])->_tag();

					$this->_tag('input');
						$this->_attr('type', $field['input_type']);
						$this->_attr('name', $field['caption']);
						if($this->_array_key('value', $field)) {
							$this->_attr('value', $field['value']);
						}
						$this->_class('cm-input');
						if($field['input_type'] == 'checkbox') {
							$this->_style('width:auto');
						}
						$this->_default_value($field['default_value']);
						if(isset($field['read_only']) && $field['read_only']) {
							$this->_attr('readonly');
						}
						$this->_posted();
					$this->_tag();

				$this->_tag();
			$this->_tag();
		}
		elseif($field['type'] == 'textarea') {
			$this->_tag('div')->_attr('class', 'cm-field');
				$this->_tag('div')->_class('cm-field-container cm-label-prefixed');
					$this->_tag('div')->_class('cm-label')->_text($field['label'])->_tag();
					$this->_tag('textarea')->_attr('name', $field['caption'])->_class('cm-textarea')->_default_text($field['default_value'])->_posted()->_tag();
				$this->_tag();
			$this->_tag();
		}
		elseif($field['type'] == 'select') {
			$this->_tag('div')->_attr('class', 'cm-field');
				$this->_tag('div')->_class('cm-field-container cm-label-prefixed');
					$this->_tag('div')->_class('cm-label')->_text($field['label'])->_tag();
					$this->_tag('select')->_attr('name', $field['caption'])->_class('cm-select')->_posted();
						if($field['select_type'] == 'multiple') {
							$this->_attr('multiple');
							$this->_add_class('cm-multiple');
						}
						foreach($field['value'] as $row) {
							$this->_tag('option')->_attr('value', $row['name'])->_text($row['name'])->_tag();
						}
					$this->_tag();
				$this->_tag();
			$this->_tag();
		}
	}

	private function _new_provider($name, array $constructor = null) {
		$class = $this->_class_separator_fix($name);
		if($this->_config('with_autoloader')) {
			if(!$this->_class_loaded($class, 'provider')) {
				$this->_push('static.autoloader.loaded.provider', $class);
				$this->_require_local($this->_provider_path($class.'.php'));
			}
		}
		if($constructor) {
			$instance = new $class(...$constructor);
		} else {
			$instance = new $class;
		}
		return $instance;
	}

	private function _new_controller($name) {
		$class = $this->_class_separator_fix($name);
		if(!$this->_class_loaded($class, 'controller')) {
			$this->_push('static.autoloader.loaded.controllers', $class);
			$this->_require_local($this->_controllers_path($class.'.php'));
		}
		return new $class;
	}

	private function _add_to_autoloader($name, $prefix) {
		$name = $this->_class_separator_fix($name);
		if(!$this->_class_loaded($name, $prefix)) {
			$this->_push('static.autoloader.loaded.'.$prefix, $name);
			$this->_require_local('classes/'.$prefix.'/'.$name.'.php');
		}
		return new $name;
	}

	private function _class_loaded($name, $prefix) {
		return $this->_in_array($name, $this->_get('static.autoloader.loaded.'.$prefix));
	}

	private function _face($type, $callback = null) {
		$face = $this->_signal();
		$face->_doctype($type);
		$face->_reset_this();
		if($callback) {
			$bound = $this->_bind($callback, $face);
	    	return $bound();
    	}
		return $face;
	}

	private function _assets_url(string $url = '') {
		return $this->_url('assets/'.$this->_to_url_s($url));
	}

	private function _contains_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($needle[$i] !== '' && $this->_pos($haystack, $needle[$i]) !== false) {
				return true;
			}
		}
        return false;
	}

	private function _delete_file($files) {
		$success = true;
        foreach($this->_is_array($files) ? $files : [$files] as $file) {
            if(!$this->_unlink($file)) {
				$success = false;
			}
        }
        return $success;
	}

	private function _explode_dot(string $data) {
		return $this->_explode('.', $data);
	}

	private function _explode_lines(string $data) {
		return $this->_explode($this->_new_line(), $data);
	}

	private function _array_key_value($array, $something = null, $multiple = false) {
    	if($this->_is_empty($array)) {
    		return $something;
    	}
		for($i=0;$i<$this->_count($array);$i++) {
			$key = $array[$i]; unset($array[$i]);
			$array = $this->_array_zero($array);
			if($this->_array_key($key, $something)) {
				if($multiple) {
					return $this->_array_key_value($array, $something[$key], $multiple);
				} else {
					return $something[$key];
				}
			}
			return NOTHING;
		}
	}

	private function _get_where($where, $item) {
		foreach($where as $key => $value) {
			if(!$this->_array_key($key, $item)) {
				return false;
			}
			if($item[$key] == $value) {
				
			} else {
				return false;
			}
		}
		return $item;
	}

	private function _array_where($where, $array = []) {
		$results = [];
		for($i=0;$i<$this->_count($array);$i++) {
			if($item = $this->_get_where($where, $array[$i])) {
				$results[] = $item;
			}
			if(!$this->_is_empty($array[$i]['items'])) {
				$results = array_merge($results, $this->_find_item($where, $array[$i]['items']));
			}
		}
		
		return $results;
	}

	private function _first_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($needle[$i] !== '' && $this->_pos($haystack, 0, $this->_length($needle[$i])) === $this->_string($needle[$i], false)) {
				return true;
			}
		}
        return false;
	}

	private function _all() {
        return $this->_ram[$this->_id];
	}

	private function _new($data = []) {
		$key = $this->_unique_nums(1);
		$init = $this->_signal($key);
		$this->_ram[$key] = $data;
        return $init;
	}

	private function _include($file, $data = null) {
		$file = $this->_template_path($file.'.php');

		$vars = [];

		if($data) {
			if($data instanceof App) {
				$vars['data'] = $data;
			} else {
				$vars['data'] = $this->_new($data);
			}
		}

		$vars['html'] = $this->_signal();

		return $this->_require_local($file, $vars);
	}

	private function _isset($key) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		if($this->_array_key($base, $this->_ram[$this->_id])) {
			$count = $this->_count($keys);
			if($count > 0) {
				$results = $this->_array_key_value($keys, $this->_ram[$this->_id][$base], true);
				if($results === NOTHING) {
					return false;
				}
				return true;
			} else {
		   		if($this->_array_key($base, $this->_ram[$this->_id])) {
		   			return true;
		   		}
		   		return false;
			}
		}
		return false;
	}

	private function _last_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($this->_substr($haystack, -$this->_length($needle[$i])) === $this->_string($needle[$i], false)) {
				return true;
			}
		}
        return false;
	}

	private function _set($key, $value) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if(!$this->_array_key($base, $this->_ram[$this->_id])) {
			$this->_ram[$this->_id][$base] = [];
		}
		if($count > 0) {
			$keys[$count] = $value;
			$data = $this->_array_sm($keys);
			$this->_ram[$this->_id][$base] = $this->_array_replace($this->_ram[$this->_id][$base], $data);
		} else {
			$this->_ram[$this->_id][$base] = $value;
		}
		return $value;
	}

	private function _get($key, $default = null) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		if($this->_array_key($base, $this->_ram[$this->_id])) {
			if($this->_count($keys) > 0) {
				$results = $this->_array_key_value($keys, $this->_ram[$this->_id][$base], true);
			} else {
		   		$results = $this->_ram[$this->_id][$base];
			}
		} else {
			return '';
		}
		if($results === NOTHING) {
			return null;
		}
		if($default !== null) {
			return $default === $results;
		}
		return $results;
	}

	private function _delete($key) {

		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($count > 0) {
			if($count == 1) {
				unset($this->_ram[$this->_id][$base][$keys[0]]);
			} else {
				$this->_ram[$this->_id][$base] = $this->_unset($this->_array_sm($keys), $this->_ram[$this->_id][$base]);
			}
		} else {
		   	unset($this->_ram[$this->_id][$base]);
		}
	}

	private function _push($key, $value) {
		$key = $this->_to_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($count > 0) {
			$data = $this->_array_key_value($keys, $this->_ram[$this->_id][$base], true);
			$data[] = $value;
			$value = $data;
			$keys[$count] = $value;
			$data = $this->_array_sm($keys);
			$this->_ram[$this->_id][$base] = $this->_array_replace($this->_ram[$this->_id][$base], $data);
		} else {
			$this->_ram[$this->_id][$base][] = $value;
		}
		return $value;
	}

	private function _redirect($url) {
		$this->_header("Location: ".$url);
		$this->_exit();
	}

	private function _require($file, $position = 'require') {
		$url = $this->_assets_url($file);
		if($this->_file($this->_get_public_path_from_host($this->_assets_path($file)))) {
			$ext = $this->_lower($this->_file_extention($url));
			if(!$this->_isset('settings.'.$ext.'_'.$position)) {
				if(!$this->_in_array($url, $this->_get('this.resources.after.'.$ext.'_'.$position)) && !$this->_in_array($url, $this->_get('this.resources.before.'.$ext.'_'.$position))) {
					$this->_push('this.resources.'.$this->_get('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		}
	}

	private function _http_request(string $url, array $options = []) {
		
	}

	private function _snippet($file, $data = []) {
		if($this->_count($this->_path_to_array($file)) == 1) {
			$file.='/default';
		}
		$this->_require('snippets/'.$file.'.css');
		$this->_require('snippets/'.$file.'.js');
		$this->_include('_common/snippets/'.$file, $data);
	}

	private function _args_to_string_vars($array) {
		$res = '';
		for($i=0;$i<$this->_count($array);$i++) {
			if($res) {
				$res.=', ';
			}
			$res.='$'.$array[$i]['name'];
		}
		return $res;
	}

	private function _unique_nums($length) {
		$options['length'] = $length;
		$options['crypt'] = [
			1,2,3,4,5,6,7,8,9
		];
		return $this->_unique_id($options);
	}

	private function _token_can_used($id) {
		if(!$this->_file($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'))) {
			$this->_make_file_force($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'), '');
			return 1;
		} else {
			$tokens = $this->_get_file_contents($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'));
			$tokens = $this->_explode_lines($tokens);
			return $this->_in_array($id, $tokens) ? 0 : 1;
		}
	}

	private function _get_token() {

		if($this->_isset('this.token')) {
			return $this->_get('this.token');
		}

		$options['by'] = [
			'num' => 4,
			'val' => '-',
		];

		$options['length'] = 24;
		$options['crypt'] = [
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','0','1','2','3','4','5','6','7','8','9'
		];

		$token = $this->_string($this->_unique_id($options), false);

		if($this->_token_can_used($token)) {
			if($this->_file($token)) {
				
			}
			$contents = $token.$this->_new_line().$this->_get_file_contents($this->_storage('interframework/tokens/'.$token.'.txt'));
			$this->_fwrite($this->_storage('interframework/tokens/'.$token.'.txt'), $contents);
			$this->_set('static.token', $token);
		} else {
			return $this->_get_token();
		}

		return $token;
	}

	private function _unique_permanent($length) {
		$options['length'] = $length;
		$options['crypt'] = [
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'!','"','#','$','%','&',"'",'(',')','*','+',',','-','.','/',':',';','<','=','>','?','@','[','\\',']','^','_','`','{','|','}','~'
		];
		return $this->_replace(' ', '', $this->_text_to_ascii($this->_unique_id($options)));
	}

	private function _unique_alpha_lower($length) {
		$options['length'] = $length;
		$options['crypt'] = [
			'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
		];
		return $this->_unique_id($options);
	}

	private function _unique_id($options, $ci = 0) {
		if(!$this->_is_array($options)) {
			$length = $options;
			$options = [
				'length' => $length,
			];
		}

		if(!$this->_isset('static.unique_ids')) {
			$this->_set('static.unique_ids', []);
		}

		if(!$this->_array_key('crypt', $options)) {
			$options['crypt'] = ['A','a','B','b','C','c','D','d','E','e','F','f','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z'];
		}

		$rand = [];
		while($this->_count($options['crypt'])) {
			$el = array_rand($options['crypt']);
			$rand[$el] = $options['crypt'][$el];
			unset($options['crypt'][$el]);
		}
		$options['crypt'] = $this->_array_zero($rand);
		for($i=0;$i<$this->_count($options['crypt']);$i++) {
			if(!$this->_array_key('id', $options)) {
				$options['id'] = '';
			}
			if($this->_array_key('by', $options)) {
				if($options['by']['num'] == $ci) {
					$options['id'].=$options['by']['val'];
					$ci = 0;
				}
			}
			$options['id'].=$options['crypt'][$i];
			$ci = $ci + 1;
			if(strlen($options['id']) < $options['length']) {
				return $this->_unique_id($options, $ci);
			}
			if($this->_in_array($options['id'], $this->_get('static.unique_ids'))) {
				return $this->_unique_id($options, 0);
			}
			// FINISH
			$this->_push('static.unique_ids', $options['id']);
			if($this->_array_key('str', $options)) {
				$options['id'] = $options['str'].$options['id'];
			}
			return $options['id'];
		}
	}

	private function _acceptable($value) {
		return $this->_in_array($value, ['yes', 'on', '1', 1, true, 'true'], true) ? true : false;
	}

	private function _add_class($data = null) {
		if(!$data) {
			return $this;
		}
		$this->_set('tag.attr.class', $this->_get('tag.attr.class') ? $this->_set('tag.attr.class', $this->_get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	private function _string($str = null, $init = true) {
		if($this->_is_init && $init) {
			if($str === null) {
				$str = $this->_all();
			} else {
				$str = $this->_get($str);
			}
		}
		if($str === null) {
			return '';
		}
		if($this->_is_array($str)) {
			return $this->_export([
				'value' => $str,
				'quote' => "'",
				'type' => 'array',
				'escape_key' => 'body'
			]);
		}
		return (string)$str;
	}

	private function _array_to_string($object) {
		return $this->_export([
			'value' => $object,
			'quote' => "'",
			'type' => 'array',
			'escape_key' => 'body'
		]);
	}

	private function _append($data) {
		return $this->_append_after_tag($data);
	}

	private function _append_after_tag($data = null) {
		$this->_push('tag.append_after_tag', $data);
		return $this;
	}

	private function _append_after_text($data = null) {
		$this->_push('tag.append_after_text', $data);
		return $this;
	}

	private function _append_before_tag($data = null) {
		$this->_push('tag.append_before_tag', $data);
		return $this;
	}

	private function _append_before_text($data = null) {
		$this->_push('tag.append_before_text', $data);
		return $this;
	}

	private function _array_replace($defaults, $replaces) {
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

	private function _array_merge($defaults, $replaces) {
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

	private function _array_sm($array, $count = 0, $current = 0, $first = 0) {
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

	private function _assets_images_url($url = '') {
		return $this->_url('assets/img/'.$this->_to_url_s($url));
	}

	private function _assets_img($img) {
		return '';
	}

	private function _attr($attr, $data = null) {
		$this->_set('tag.attr.'.$attr, $data);
		return $this;
	}

	private function _auto_route() {

		$path = $this->_path();
		$path = $this->_explode($this->_url_s(), $path);

		if(isset($path[0])) {
			if($path[0] == 'admin') {
				$this->_set('static.is_admin', 1);
				unset($path[0]);
				$path = $this->_array_zero($path);
			}
		}

		$path = $this->_implode($this->_url_s(), $path);

		$path = $path ? $path : $this->_url_s();
		$this->_set('route', [
			'args' => [],
			'method' => 'index',
			'controller' => 'desktop',
		]);

		if($path && $path != $this->_url_s()) {
			$path = $this->_explode($this->_url_s(), $path);
			if($this->_in_array('index', $path)) {
				$this->_set('response.status', 404);
			} else {
				if(isset($path[0])) {
					$this->_set('route.controller', $path[0]);
					unset($path[0]);
					if(isset($path[1])) {
						$this->_set('route.method', $path[1]);
						unset($path[1]);
						if(isset($path[2])) {
							$this->_set('route.args', $this->_is_empty($path) ? false : $this->_array_zero($path));
						}
					}
				}
			}
		}
	}

	private function _body_class($classes = '') {
		$classes = $this->_replace_spaces_with_one($classes);
		foreach($this->_explode(' ', $classes) as $class) {
			if(!$this->_in_array($class, $this->_get('this.bodyclass'))) {
				$this->_push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	private function _dirsep() {
		return DIRECTORY_SEPARATOR;
	}

	private function _cache($file, $contents = null) {
		$file = $this->_storage('interframework/cache/'.$this->_to_dirsep($file.'.'.$this->_get('static.cache.save_type')));
    	if(!$this->_is_null($contents)) {
    		if(!$this->_is_dir($dir = $this->_get_dir_file($file))) {
    			$this->_mkdir($dir);
    		}
    		if($this->_file($file)) {
    			$this->_delete_file($file);
    		}
			return $this->_make_file($file, $contents);
		} else {
			if($this->_file($file)) {
				return $this->_get_file_contents($file);
			}
		}
		return '';
	}

	private function _checked() {
		$this->_set('tag.attr.checked', 'checked');
		return $this;
	}

	private function _selected() {
		$this->_set('tag.attr.selected', 'selected');
		return $this;
	}

	private function _chkeep() {
		if(!$this->_is_empty($this->_get('this.keep'))) {
			if($this->_isset('this.keep.attr')) {
				if($this->_get('this.keep.attr.name') !== '') {
					if($this->_is_same('this.keep.attr.level', 'this.level')) {
						$this->_delete('this.keep.attr');
					}
				}
			}
			if($this->_isset('this.keep.form')) {
				if($this->_is_same('this.keep.form.level', 'this.level')) {
					$this->_delete('this.keep.form');
				}
			}
		}
	}

	private function _class($data = '') {
		$this->_set('tag.attr.class', $data);
		return $this;
	}

	private function _class_separator_fix($class) {
		return $this->_trim($this->_replace(['/', '.'], '\\', $class), '\\');
	}

	private function _component_exists($path) {
		return $this->_file($this->_assets_path($path));
	}

	private function _replace_between($start, $str, $end) {
		return $this->_get($start, $str, $end);
	}

	private function _call_controller($controller) {
		$controller = $this->_new_controller($this->_ucfirst($controller));
		$method = $this->_get('route.method');
		if(method_exists($controller, $method)) {
			$this->_set('response.data', $controller->{$method}($this->_signal()));
		} else {
			$this->_set('response.status', 404);
		}
	}

	private function _get_public_path_from_host($str) {
		return $this->_remove_prefix($this->_url(), $str);
	}

	private function _conclude() {

		if($this->_config('with_controller')) {
			$this->_call_controller($this->_get('route.controller'));
		}

		if($this->_get('response.status', 404)) {
			$this->_set('route', [
				'args' => [],
				'method' => '404',
				'controller' => 'errors',
			]);
		}

		if($this->_config('with_view')) {
			$this->_set('view', $this->_to_dirsep($this->_get('route.controller').'/'.$this->_get('route.method')));
		}

		if($this->_config('with_view') && $this->_get('static.cache.from_cache') && $this->_is_cached($this->_get('view'))) {
			$this->_set_text($this->_cache($this->_get('view')));
		} else {

			$this->_set_start_code_space_level(2);

			if($this->_config('with_view')) {
				$this->_include('_common/configs');
			}

			$this->_set_after_or_before('after');

			if($this->_config('with_view')) {
				if($this->_is_manual('root_view')) {
					$this->_include($this->_get('manual.root_view'), $this->_get('response.data'));
				} else {
					$this->_include($this->_get('view'), $this->_get('response.data'));
				}
			}

			$this->_set_text($this->_finalize(1));

			// ------------------------------------------

			$this->_set_after_or_before('before');
			$this->_set_start_code_space_level(0);

			$this->_require('app.css');
			$this->_require('app.js');

			if($this->_config('with_view')) {
				$this->_include('_common/resources');
			}

			$this->_set_after_or_before('after');

			if($this->_config('with_view')) {
				$this->_resources();
			}

			$this->_set_after_or_before('before');

			if($this->_monitor()) {
				$contents = $this->_finalize();
				if($this->_is_string($contents)) {
					if($this->_get('static.response')) {
						$this->_set('static.response', 0);
						$this->_set('response', [
							'status' => 200,
							'text' => $contents,
						]);
					} else {
						$this->_set('text', $contents);
					}
				} else {
					$this->_fail('argument', 'Server info: Response must be a type of string. You are given a type [ '.$this->_get_type($contents).' ] this is not supported from your system copyrights.');
				}
			} else {
				$this->_fail('signal', 'NO SIGNAL');
			}
		}
		return 1;
	}

	private function _copy_dir($directory, $destination) {

		if(!$this->_is_dir($directory)) {
            return false;
        }

		$this->_mkdir($destination, 0777, true);

	    $dir = @opendir($directory);

	    while(false !== ($file = readdir($dir))) {
	        if(($file != '.') && ($file != '..')) {
	            if($this->_is_dir($directory.$this->_dirsep().$file)) {
	                if(!$this->_copy_dir($directory.$this->_dirsep().$file, $destination.$this->_dirsep().$file)) {
	                	return false;
	                }
	            }
	            else {
	                if(!$this->_copy_file($directory.$this->_dirsep().$file, $destination.$this->_dirsep().$file)) {
	                	return false;
	                }
	            }
	        }
	    }

	    @closedir($dir);

	    return true;
	}

	private function _certificate() {
		if($this->_get('tag.tag') !== 'untaged') {
			if($this->_get('tag.tag', 'form')) {
				$this->_set('this.keep.form', [
					'name' => $this->_get('tag.attr.name'),
					'index' => $this->_get('this.index'),
					'level' => $this->_get('this.level')
				]);
			}
			if($this->_isset('tag.attr.name')) {
				$this->_set('this.keep.attr', [
					'level' => $this->_get('this.level'),
					'name' => $this->_replace('[]', '', $this->_get('tag.attr.name')),
					'type' => $this->_isset('tag.attr.type') ? $this->_get('tag.attr.type') : false,
					'defineds' => $this->_get('tag.defineds')
				]);
			}
			if($this->_isset('this.keep.attr.name')) {
				$posted = $this->_isset('this.keep.attr.defineds.posted');
				$type = $this->_lower($this->_get('this.keep.attr.type'));
				$default = [];

				if($this->_isset('static.request.old') && $this->_get('this.keep.attr.name') && $this->_isset('static.request.old.'.$this->_get('this.keep.attr.name'))) {
					$default[$this->_get('this.keep.attr.name')] = $this->_get('static.request.old.'.$this->_get('this.keep.attr.name'));
				} else {
					if($this->_array_key('default_checked', $this->_get('this.keep.attr.defineds'))) {
						$default[$this->_get('this.keep.attr.name')] = $this->_get('this.keep.attr.defineds.default_checked');
					}
					elseif($this->_array_key('default_selected', $this->_get('this.keep.attr.defineds'))) {
						$default[$this->_get('this.keep.attr.name')] = $this->_get('this.keep.attr.defineds.default_selected');
					}
					elseif($this->_array_key('default_value', $this->_get('this.keep.attr.defineds'))) {
						$default[$this->_get('this.keep.attr.name')] = $this->_get('this.keep.attr.defineds.default_value');
					}
					elseif($this->_array_key('default_text', $this->_get('this.keep.attr.defineds'))) {
						$default[$this->_get('this.keep.attr.name')] = $this->_get('this.keep.attr.defineds.default_text');
					}
				}
				if(!$posted && !$this->_get('this.keep.attr.name')) {
					if($this->_array_key($this->_get('this.keep.attr.name'), $default)) {
						unset($default[$this->_get('this.keep.attr.name')]);
					}
				}
				if($this->_array_key($this->_get('this.keep.attr.name'), $default)) {
					if($this->_array_key('value', $this->_get('tag.attr'))) {
						if($this->_is_array($default[$this->_get('this.keep.attr.name')])) {
							if($this->_in_array($this->_get('tag.attr.value'), $default[$this->_get('this.keep.attr.name')])) {
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
									if($this->_get('tag.attr.value', $default[$this->_get('this.keep.attr.name')])) {
										$this->_checked();
									}
								} else {
									$this->_set('tag.attr.value', $default[$this->_get('this.keep.attr.name')]);
								}
							}
							if($this->_get('tag.tag') == 'option') {
								if($this->_get('tag.attr.value', $default[$this->_get('this.keep.attr.name')])) {
									$this->_selected();
								}
							}
						}
					} else {
						if($this->_get('tag.tag') == 'input') {
							if($type == 'checkbox' || $type == 'radio') {
								if($this->_acceptable($default[$this->_get('this.keep.attr.name')])) {
									$this->_checked();
								}
							}
							elseif($type == 'text') {
								$this->_set('tag.attr.value', $default[$this->_get('this.keep.attr.name')]);
							} else {
								
							} 
						} else {
							if($this->_get('tag.tag') == 'option') {
								if($this->_acceptable($default[$this->_get('this.keep.attr.name')])) {
									$this->_selected();
								}
							} else {
								if($this->_get('tag.tag') == 'textarea') {
									$this->_set('tag.text', $default[$this->_get('this.keep.attr.name')]);
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	private function _current_type_is(string $type) {
		return $this->_get('static.doctype', $type);
	}

	private function _doctype(string $type = null) {
		if($type === null && !$this->_isset('static.doctype')) {
			return $this->_set('static.doctype', 'html');
		}
		if($type === null) {
			return $this->_get('static.doctype');
		}
		$this->_set('static.doctype', $type);
		return $this;
	}

	private function _default_checked($data = '') {
		$this->_set('tag.defineds.default_checked', $data);
        return $this;
	}

	private function _default_selected($data = '') {
		$this->_set('tag.defineds.default_selected', $data);
        return $this;
	}

	private function _default_text($data = '') {
		$this->_set('tag.defineds.default_text', $data);
        return $this;
	}

	private function _default_value($data = '') {
		$this->_set('tag.defineds.default_value', $data);
        return $this;
	}

	private function _delete_path($directory, $preserve = false) {
		if(!$this->_is_dir($directory)) {
            return false;
        }
        $items = new FilesystemIterator($directory);
        foreach ($items as $item) {
            if ($item->isdir() && ! $item->isLink()) {
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

	private function _dot_to_underscore($str) {
		return $this->_trim($this->_replace('.', '_', $str), '_');
	}

	private function _underscore_to_dot($str) {
		return $this->_trim($this->_replace('_', '.', $str), '.');
	}

	private function _dot_to_dirsep($str) {
		return $this->_trim($this->_replace('.', $this->_dirsep(), $str), $this->_dirsep());
	}

	private function _dot_to_url_s($str) {
		return $this->_trim($this->_replace('.', $this->_url_s(), $str), $this->_url_s());
	}

	private function _echo($string) {
		echo($string);
	}

	private function _escape($str) {
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

	private function _unescape($str) {
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

	private function _export(array $options = []) {
		$results = "";
		if($options['type'] == 'json') {
			$results = $this->_get_exported_string($this->_array_merge([
				'quote' => '"',
				'value' => [],
				'prefix_key' => "o_",
				'opentagsymbol' => "{",
				'closetagsymbol' => "}",
				'key_separator_value' => " : ",
			], $options));
		}
		elseif($options['type'] == 'array') {

			if(!$this->_is_array($options['value'])) {
				$this->_fail('export_type', 'unexpected export: array to string');
			}

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
			], $options));
			return trim($results);
		} else {
			$this->_fail('file_type', 'unknown file type for export');
		}
	}

	private function _fail($place, $msg = null) {

		if($msg) {
			$msg = $place.' : '.$msg;
		}

		$this->_echo($msg);
		$this->_exit();
	}

	private function _file_append_to_top($file, $contents) {
		return $this->_fwrite($file, $contents.$this->_get_file_contents($file));
	}

	private function _finalize(int $reset = null) {
		$document = $this->_get_document($reset);
		if($this->_trim($document)) {
			if($this->_config('with_view') && $this->_get('static.cache.recache')) {
				$this->_cache($this->_get('view'), $document);
			}
		}
		return $document;
	}

	private function _fix_type($value, $level = 0) {
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
					$value = "[\n".$this->_space_like_tab($level + 1).$this->_export([
						'value' => $value,
						'quote' => "'",
						'level' => $level + 1,
						'type' => 'array',
					])."\n".$this->_space_like_tab($level).']';
				}
			break;
			default:
				
			break;
		}
		return $value;
	}

	private function _items_length() {
		return $this->_get('this.count');
	}

	private function _class_get_reflection($instance) {
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

	private function _class_get_properties($instance) {

		$class = new ReflectionClass($instance);
		$class_name = get_class($instance);

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

			$value = $real->isDefault() ? $real->getValue($instance) : NOTHING;

			$results[$name] = [
				'name' => $name,
				'is_static' => $is_static,
				'visibility' => $visibility,
				'value' => $value,
			];
		}
		return $results;
	}

	private function _class_get_methods($instance) {
		$class = new ReflectionClass($instance);
		$methods = $class->getMethods();
		//sort($methods);
		$results = [];
		foreach($methods as $method) {
			$rm = $method;
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
				'name' => $this->_lower($method->name),
				'arguments' => $args,
				'body' => $body,
				'is_static' => $is_static,
				'visibility' => $visibility
			];
		}
		return $results;
	}

	private function _class_get_as_array($instance) {
		if($this->_is_string($instance)) {
			$instance = new $instance;
		}
		$app = $this->_class_get_reflection($instance);
		$app['properties'] = $this->_class_get_properties($instance);
		$app['methods'] = $this->_class_get_methods($instance);
		return $app;
	}

	private function _replace_string_multiple(array $array, string $key, string $from, string $to, $target) {
		foreach($array as $row) {
			$target = str_replace($from, $to, $target);
		}
		return $target;
	}

	private function _get_control_chars($code = null, $type = 'symbol') {
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

	private function _get_database_names(array $op = null) {
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

	private function _get_database_names_with_tables($keyed = 0) {
		return $this->_get_database_names([
			'with_tables' => 1,
			'keyed' => $keyed
		]);
	}

	private function _get_database_names_with_tables_and_data($keyed = 0) {
		return $this->_get_database_names([
			'with_tables' => 1,
			'with_tables_data' => 1,
			'keyed' => $keyed
		]);
	}

	private function _get_database_structure(string $env) {
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

	private function _get_database_table_columns($database, $table) {
		$results = [];
		$data = $this->_get_database_table_config_array($database, $table);
		for($i=0;$i<$this->_count($data);$i++) {
			$results[] = $data[$i]['name'];
		}
		return $results;
	}

	private function _get_database_table_config_array(string $database, string $table, array $opts = []) {
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

	private function _get_database_table_config_string($database, $table) {
		return $this->_get_file_contents($this->_database_path($database.'/'.$table.'/structure.txt'));
	}

	private function _get_database_table_data($database, $tables) {
		$results = [];
		if(!$this->_is_array($tables)) {
			$tables = [$tables];
		}
		$allow = $this->_get_database_tables($database);
		for($i=0;$i<$this->_count($tables);$i++) {
			if($this->_in_array($tables[$i], $allow)) {
				$dbts = $this->_get_database_table_config_array($database, $tables[$i]);
				$dbtc = $this->_explode_lines($this->_get_database_table_data_string($database, $tables[$i]));
				for($b=0;$b<$this->_count($dbtc);$b++) {
					$tmp = [];
					$item = $this->_explode('|', $dbtc[$b]);
					for($j=0;$j<$this->_count($dbts);$j++) {
						$tmp[$dbts[$j]['name']] = $this->_set_type($dbts[$j]['type'], $item[$j]);
					}
					$results[] = $tmp;
				}
			} else {
				$this->_fail('table', 'Table [ '.$table.' ] not exists in database [ '.$database.' ]');
			}
		}
		return $results;
	}

	private function _get_database_table_data_all($database) {
		return $this->_get_database_table_data($database, $this->_get_database_tables($database));
	}

	private function _get_database_table_data_string($database, $table) {
		return $this->_get_file_contents($this->_database_path($database.'/'.$table.'/data.txt'));
	}

	private function _get_database_tables(string $database) {
		return $this->_get_database_structure($database)['tables'];
	}

	private function _get_date_time_zone($zone) {
		return new DateTimeZone($zone);
	}

	private function _get_dir_file($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

	private function _get_exported_string($list = []) {
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

	private function _get_items() {
		return $this->_get('this.items');
	}

	private function _get_this() {
		return $this->_get('this');
	}

	private function _get_local_array($where) {
		$key = $this->_to_base($key);
		$path = $key['base'];
		$rest = $key['keys'];
		return $this->_get_returned_array_file('arrays/'.$path, $rest);
	}

	private function _get_form_fields_local($key, $default = null) {
		$key = $this->_to_base($key);
		$path = $key['base'];
		$rest = $key['keys'];
		return $this->_get_returned_array_file('forms/'.$path, $rest);
	}

	private function _get_month($month) {
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

	private function _get_name_last($str) {
		return basename($str);
	}

	private function _get_nested_items() {
		return $this->_nested($this->_get_items());
	}

	private function _get_non_alpha_numeric_characters() {
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

	private function _get_paths($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	private function _get_paths_only($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	private function _get_returned_array_file($file, $where) {
		$fileok = 0;
		for($i=0;$i<$this->_count($where);$i++) {
			if(!$fileok) {
				if($this->_is_file($this->_common_path($file.'.php'))) {
					$fileok = 1;
					$data = $this->_require_local($this->_common_path($file.'.php'));
				}
				$file.=$this->_dirsep().$where[$i];
			} 
			if($fileok) {
				$data = $data[$where[$i]];
			}
		}
		return $data;
	}

	private function _get_time_in_milliseconds() {
		return time();
	}

	private function _get_type($element) {
		return gettype($element);
	}

	private function _has_more_opened_tags() {
		return $this->_get('static.unclosed_tags') > 0;
	}

	private function _has_more_closed_tags() {
		return $this->_get('static.unclosed_tags') < 0;
	}

	private function _get_unknown_chars($code = null, $type = 'symbol') {
		$codes =  [
			940 => [
				'hex' => '3ac',
				'symbol' => '',
				'chars' => [],
			],
		];
		return $code == null ? $codes : $codes[$code][$type];
	}

	private function _get_body_class() {
		return $this->_implode(' ', $this->_get('this.bodyclass'));
	}

	private function _get_include_contents($file) {
		return $this->_get_file_contents($this->_template_path($file.'.php'));
	}

	private function _get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	private function _header($str) {
		return header($str);
	}

	private function _http_build_query($data, $separator = '&', $prefix = '') {
		return http_build_query($data, $prefix, $separator);
	}

	private function _include_exists($file) {
		return $this->_file($this->_template_path($file.'.php'));
	}

	private function _is_double($element) {
		return is_double($element);
	}

	private function _is_file($file) {
		return is_file($file);
	}

	private function _is_float($element) {
		return is_float($element);
	}

	private function _is_integer($element) {
		return is_integer($element);
	}

	private function _is_numeric($element) {
		return is_numeric($element);
	}

	private function _is_admin() {
		return $this->_isset('static.is_admin') && $this->_get('static.is_admin');
	}

	private function _is_cached($file) {
		return $this->_file($this->_storage('interframework/cache/'.$this->_to_dirsep($file.'.'.$this->_get('static.cache.save_type'))));
	}

	private function _is_closure($callback) {
		return is_object($callback) && ($callback instanceof Closure);
	}

	private function _is_int($element) {
		return $this->_is_integer($element);
	}

	private function _is_manual($key) {
		return $this->_isset('manual.'.$key) && $this->_get('manual.'.$key);
	}

	private function _is_same($a, $b) {
		return $this->_get($a, $this->_get($b));
	}

	private function _load_components($types) {
		if(!$this->_is_array($types)) {
			$types = [$types];
		}
		foreach($types as $type) {
			foreach([$type.'_require', $type.'_dynamic', $type.'_auto_view'] as $setch) {
				if(!$this->_isset('settings.'.$setch)) {
					foreach(['before', 'after'] as $place) {
						foreach($this->_get('this.resources.'.$place.'.'.$setch) as $comp) {
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

	private function _head_style(string $style) {
		$this->_set_after_or_before('before');
		$this->_push('this.resources.'.$this->_get('after_or_before').'.style', $style);
		$this->_set_after_or_before('after');
	}

	private function _load_styles() {
		foreach(['before', 'after'] as $place) {
			foreach($this->_get('this.resources.'.$place.'.style') as $style) {
				$this->_tag('style')->_text($style)->_tag();
			}
		}
	}

	private function _load_the_scripts_components() {
		if(!$this->_isset('settings.scripts')) {
			foreach($this->_get('this.resources.after.scripts') as $ajavascript) {
				$this->_tag('script')->_attr('preload', true)->_text($ajavascript)->_tag();
			}
			foreach($this->_get('this.resources.before.scripts') as $bjavascript) {
				$this->_tag('script')->_attr('preload', true)->_text($bjavascript)->_tag();
			}
		}
		return $this;
	}

	private function _log($msg) {
		$logs = $this->_mytime()." : ".$msg."\n";
		$logs.= $this->_get_file_contents($this->_storage('interframework/logs/log.txt'));
		$this->_fwrite($this->_storage('interframework/logs/log.txt'), $logs);
	}

	private function _make_include($file, $contents = '') {
		return $this->_make_file($this->_template_path($file), $contents);
	}

	private function _manual($key, $value = null) {
		return $this->_set('manual.'.$key, $value === null ? 1 : $value);
	}

	private function _mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->_in_array($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

	private function _mkdir($dir, $mode = 493, $recursive = true) {
		if(!$this->_is_dir($dir)) {
			if(!mkdir($dir, $mode, $recursive)) {
				return false;
			}
		}
		return true;
	}

	private function _mkdirs(array $array, $force = false, $path = '') {
		$path = $path ? $path.$this->_dirsep() : '';
		foreach($array as $row) {
			if($this->_mkdir($path.$row['name'], isset($row['mode']) ? $row['mode'] : 0777)) {
				if(isset($row['files']) && !empty($row['files'])) {
					foreach($row['files'] as $file) {
						$ready_file = $path.$row['name'].$this->_dirsep().$file['name'];
						$ready_contents = array_key_exists('contents', $file) ? $file['contents'] : '';
						if($force) {
							$this->_make_file_force($ready_file, $ready_contents);
						} else {
							$this->_fwrite($ready_file, $ready_contents);
						}
					}
				}
				if(isset($row['subfolders']) && !empty($row['subfolders'])) {
					$this->_mkdirs($row['subfolders'], $force, $path.$row['name']);
				}
			} else {
				return false;
			}
        }
        return 1;
	}

	private function _make_file($file, $contents = '', $lock = false) {
		if(!$this->_file($file)) {
			return $this->_fwrite($file, $contents, $lock);
		} else {
			return 0;
		}
	}

	private function _make_file_force($file, $contents = '') {
		if(!$this->_file($file)) {
			$dir = $this->_get_dir_file($file);
	   		if(!$this->_is_dir($dir)) {
	   			$this->_mkdir($dir);
	   		}
	   		if($this->_make_file($file, $contents)) {
		   		return 1;
		   	}
	   	} else {
		   	$this->_delete_file($file);
		   	if($this->_make_file($file, $contents)) {
		   		return 1;
		   	}
	   	}
	   	return 0;
	}

	private function _get_fprefix() {
		return '_';
	}

	private function _monitor() {

		$this->_text($this->_get('static.monitor.top_comment'));
		$this->_tag('doctype')->_attr('html')->_tag();
		$this->_tag('html')->_attr('class', 'cm-html')->_attr('lang', $this->_get('static.monitor.doctype_lang'));
			$this->_tag('head');
				$this->_tag('meta')->_attr('charset', $this->_get('static.monitor.meta.charset'))->_tag();
				$this->_tag('meta')->_attr('name', 'viewport')->_attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->_tag();
				$this->_tag('meta')->_attr('httpequiv', 'Content-Type')->_attr('content', 'text/html; charset=UTF-8')->_tag();
				$this->_tag('meta')->_attr('id', 'domain')->_attr('content', 'My domain')->_tag();
				$this->_tag('title')->_text($this->_get('title'))->_tag();
				$this->_tag('link')->_attr('rel', 'icon')->_attr('type', 'image/png')->_attr('href', $this->_assets_url('img/favicon.png'))->_tag();
				$this->_load_components('css');
				$this->_load_styles();
			$this->_tag();
			$this->_tag('body')->_attr('class', 'cm-body');
				$this->_text($this->_get('text'), 1);
				$this->_load_components('js');
				$this->_load_the_scripts_components();
			$this->_tag();
		$this->_tag();

		return 1;
	}

	private function _mytime() {
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

	private function _nested(array $data = []) {
	    $prev = 0;
		$waiting = [];
	    for($i=0;$i<count($data);$i++) {
			if($i > 0 && $data[$i]['level'] > $data[$prev]['level']) {
				$waiting[$prev]['items'][] = $data[$i];
			} else {
				$prev = $i;
				$waiting[$i] = $data[$i];
			}
	    }

		$i = 0;
		$results = [];
		foreach($waiting as $item) {
			$results[$i] = $item;
	        if(!$this->_is_empty($results[$i]['items'])) {
	            $results[$i]['items'] = $this->_nested($results[$i]['items']);
	        }
	        $i++;
	    }

	    return $results;
	}

	private function _get_document(int $reset = null) {

		if($reset == null) {
			$reset = 0;
		}

		if($this->_has_more_opened_tags()) {
			$this->_fail('syntax', 'You have more opened tags than you have closed.');
		}
		if($this->_has_more_closed_tags()) {
			$this->_fail('syntax', 'You have more closed tags than you have opened.');
		}

		$items = $this->_get_nested_items();

		if($reset) {
			$this->_reset_this();
		}

		return $this->_build_document($items);
	}

	private function _new_line() {
		return "\n";
	}

	private function _enter($nums = 1) {
		for($i=0;$i<$nums;$i++) {
			$this->_tag('untaged')->_attr('lined', 1)->_tag();
		}
		return $this;
	}

	private function _new_tag() {
		return [
			'doctype' 					=> '',
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
		    'my_space'					=> 0,
			'start_code_space_level'	=> 0,
		];
	}

	private function _get_uri_paths() {
		return $this->_get('static.url_paths');
	}

	private function _rewrite_rule($file) {
		return $this->_set('static.htacces.rewrite_rule', $file);
	}

	private function _route(int $num = 0, string $name = null) {
		if($name === null) {
			return $this->_isset('static.url_paths.'.$num);
		}

		return $this->_get('static.url_paths.'.$num, $name);
	}

	private function _posted() {
		$this->_set('tag.defineds.posted', 1);
        return $this;
	}

	private function _process_text_to_ascii($string, &$offset) {
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

	private function _root(string $path = '') {
		if($path === '') {
			if($this->_isset('static.root')) {
				$path = $this->_get('static.root');
			} else {
				$path = $this->_set('static.root', $this->_rtrim($path, $this->_dirsep()));
			}
		} else {
			if($this->_isset('static.root')) {
				$path = $this->_get('static.root');
			} else {
				$path = $this->_set('static.root', $this->_rtrim($path, $this->_dirsep()));
			}
		}

		return $path;
	}

	public function _disk(string $path = '') {
		$root = $this->_root();
		if($path) {
			$root = $root.$this->_dirsep().$this->_to_dirsep($path);
		}
		return $this->_lower($root);
	}

	private function _public_path($str = '') {
		return $this->_disk('public/'.$str);
	}

	private function _database_path($str = '') {
		return $this->_storage('Interframework/Database/'.$str);
	}

	private function _common_path($str = '') {
		return $this->_template_path('_common/'.$str);
	}

	private function _template_path($str = '') {
		return $this->_disk('views/'.$this->_get_template().'/'.$str);
	}

	private function _assets_path($str = '') {
		return $this->_disk('public/assets/'.$str);
	}

	private function _app_path($str = '') {
		return $this->_disk('app/'.$str);
	}

	private function _provider_path($str = '') {
		return $this->_disk('provider/'.$str);
	}

	private function _controllers_path($str = '') {
		return $this->_app_path('controllers/'.$str);
	}

	private function _views_path($str = '') {
		return $this->_disk('views/'.$str);
	}

	private function _require_text_script($script) {
		$this->_push('this.resources.'.$this->_get('after_or_before').'.scripts', $script);
		return $this;
	}

	private function _resources() {
		if($this->_get('view')) {
			$css = 'views/'.$this->_get('view').'.css';
			$js = 'views/'.$this->_get('view').'.js';
			if(!$this->_file($this->_assets_path($css))) {
				if($this->_make_file_force($this->_assets_path($css))) {
					$this->_require($css, 'auto_view');
				}
			} else {
				$this->_require($css, 'auto_view');
			}
			if(!$this->_file($this->_assets_path($js))) {
				if($this->_make_file_force($this->_assets_path($js))) {
					$this->_require($js, 'auto_view');
				}
			} else {
				$this->_require($js, 'auto_view');
			}
		}
	}

	private function _replace_identifier($from, $to, $contents) {
		return $contents;
	}

	private function _ascii_to_text($contents, $callback = null) {

		if(!$callback) {
			$callback = function($output) {
				$output->contents.=$output->a_o;
				return $output;
			};
		}

		$contents = $this->_explode(' ', $this->_trim($contents));
		$results = $this->_ascii_process($this->_get_ascii_options($contents, $callback));
		return $results->contents;
	}

	private function _character($output) {
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

	private function _get_ascii_options($input, $callback) {
		$output = new stdClass;
		$output->callback										= $callback;
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

	private function _ascii_process($output) {
		$i=0;
		if($output->input) {

		if($output->callback) {
			//print_r($output->callback); exit;
		}

			$output->decimal = $output->input[$output->current];
			unset($output->input[$output->current]);
			$output = $this->_character($output);
			$output = $this->_parse($output);
			$output->current++;
			$i++;
			$output = $this->_ascii_process($output);
		}
		return $output;
	}

	private function _parse($output) {
		if($output->isString) {
			$m = '_gg_'.$output->type.'_string';
		} else {
			$m = '_gg_'.$output->type;
		}
		return $this->$m($output);
	}

	private function _gg_alpha($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_alpha_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_control($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_control_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_number($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_number_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_symbol($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _gg_symbol_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	private function _bind($callback, $obj) {
		return Closure::bind($callback, $obj);
	}

	private function _signal($key = 'app') {
		return $this->_new_provider('App', [$key]);
	}

	private function _close() {
		$this->_set('static.response', 1);

		foreach($this->_get('this.on.construct') as $callback) {
			$callback();
		}

		if($this->_conclude()) {
			$this->_echo($this->_get('response.text'));
		}
	}

	private function _set_after_or_before($switch) {
		$this->_set('after_or_before', $switch);
	}

	private function _set_start_code_space_level($level) {
		return $this->_set('this.start_code_space_level', $level);
	}

	private function _get_start_code_space_level() {
		return $this->_get('this.start_code_space_level');
	}

	private function _my_space($num) {
		$this->_set('tag.my_space', $num);
		return $this;
	}

	private function _fixed_space($num) {
		$this->_set('static.fixed_space', $num);
		return $this;
	}

	private function _set_text($text) {
		$this->_set('text', $text);
	}

	private function _set_type($type, $value) {
		switch($type) {
			case'int':
				$value = (int)$value;
			break;
			case'string':
				$value = $this->_string($value, false);
			break;
		}
		return $value;
	}

	private function _settings($key) {
		return $this->_get('settings.'.$key);
	}

	private function _slash_and_dot_to_dirsep($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_dirsep(), $str), $this->_dirsep());
	}

	private function _slash_and_dot_to_dash($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], '_', $str), $this->_url_s());
	}

	private function _slash_and_dot_to_space($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], ' ', $str), $this->_url_s());
	}

	private function _slash_and_dot_to_url_s($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_url_s(), $str), $this->_url_s());
	}

	private function _slash_to_dot($str) {
		return $this->_trim($this->_replace(['/', '\\'], '.', $str), '.');
	}

	private function _space($number) {
		return $this->_get_spaces_by_level($number, " ");
	}

	private function _space_like_tab($number) {
		return $this->_get_spaces_by_level($number, "	");
	}

	private function _space_to_dash($str) {
		return $this->_replace(' ', '-', $str);
	}

	private function _storage($str = '') {
		return $this->_disk('storage/'.$str);
	}

	private function _storage_copy($source, $destination) {
		return $this->_copy_dir(
			$this->_storage($source), $destination
		);
	}

	private function _str_trans($one, $two) {
		return strtr($one, $two);
	}

	private function _string_length($str) {
		return strlen($str);
	}

	private function _style($data) {
		$this->_set('tag.attr.style', $data);
		return $this;
	}

	private function _style_to_file($style, $class = null) {
		$data = $style;
    	$contents = '';

    	if($class) {
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

	private function _syntax_error($error, $line = 0) {
		$this->_fail('syntax', $error.' on line '.$line);
	}

	private function _tab_space($number) {
		return $this->_get_spaces_by_level($number, "\t");
	}

	private function _table($table) {
		return new Database($table);
	}

	private function _tag($tag = null) {

		// -----------------------------------------------------------------------------

		if($this->_get('this.waiting')) {
			if($this->_certificate()) {

				if($this->_isset('static.fixed_space')) {
					if($this->_get('tag.my_space', 0)) {
						$this->_set('tag.my_space', $this->_get('static.fixed_space'));
					}
				}

				$item = [
					'doctype' 					=> $this->_get('tag.doctype'),
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
					'my_space'					=> $this->_get('tag.my_space'),
				    'index'						=> $this->_get('this.index'),
				    'level'						=> $this->_get('this.level'),
					'start_code_space_level'	=> $this->_get('this.start_code_space_level'),
				];

				$this->_set('this.items.'.$this->_get('this.index'), $item);

				$this->_set('tag', $this->_new_tag());

				$this->_set('this.count', $this->_get('this.count') + 1);
				$this->_set('this.index', $this->_get('this.index') + 1);
				$this->_set('this.waiting', 0);
			} else {
				$this->_fail('security', 'Security error: Certificate has fail to process your request.');
			}
		}

		// -----------------------------------------------------------------------------

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
			$this->_set('tag.doctype', $this->_doctype());
		}
		return $this;
	}

	private function _text($text = null, $lined = 0) {
		if($this->_is_object($text)) {
			return $this;
		}
		if($text !== null) {
			if($this->_get('tag.tag') && !$lined) {
				$this->_set('tag.text', $this->_get('tag.text').$text);
			} else {
				$this->_tag('untaged');
					if($lined) {
						$this->_attr('lined', 1);
					}
					$this->_set('tag.text', $text);
				$this->_tag();
			}
		}
		return $this;
	}

	private function _title(string $title) {
		return $this->_set('title', $title);
	}

	private function _get_title() {
		return $this->_get('title');
	}

	private function _get_standard_style_css() {
		return $this->_get('display.standard_style_css');
	}

	private function _get_standard_js() {
		return $this->_get('display.standard_js');
	}

	private function _to_base($key) {
		if($this->_first_in($key, '.') || $this->_last_in($key, '.') || $this->_contains_in($key, '..')) {
	    	$this->_fail('collection', 'FATAL ERROR: WRONG COLLECTION KEY SKELETON FOR [ '.$key.' ]');
	   	}
    	$key = $this->_lower($key);
    	$key = $this->_explode_dot($key);
		$data_key = $key[0]; unset($key[0]);
		$key =  $this->_array_zero($key);
    	return ['keys' => $key, 'base' => $data_key];
	}

	private function _to_dirsep($str) {
		return $this->_trim($this->_replace(['/', '\\'], $this->_dirsep(), $str), $this->_dirsep());
	}

	private function _to_url_s($str = '') {
		return $this->_trim($this->_replace(['/', '\\'], '/', $str), '/');
	}

	private function _ucfirst($str) {
		return ucfirst($str);
	}

	private function _underscore_to_upercase($name) {
		$names = $this->_trim($name, '_');
	   	$names = $this->_explode('_', $name);
	   	$newName = '';
	   	foreach($names as $name) {
	   		$newName.=$this->_ucfirst($name);
	   	}
	   	return $newName;
	}

	private function _upper_to_underscore($string) {
		return $this->_lower($this->_preg_replace('/(.)([A-Z])/', '$1_$2', $string));
	}

	private function _array_keys_lower(array $array) {
		$results = [];
		foreach($array as $key => $value) {
			$results[$this->_lower($key)] = $value;
		};
		return $results;
	}

    private function _snake($str) {
		$str = $this->_preg_replace('/\s+/u', '', $this->_ucwords($str));
		$str = $this->_lower($this->_preg_replace('/(.)(?=[A-Z])/u', '$1_', $str));
        return $str;
    }

    private function _normilize_request($array) {
    	$methods = $this->_get('static.allowed.request_methods');
		$request = [];
    	for($i=0;$i<$this->_count($methods);$i++) {
    		$request[$methods[$i]] = $array[$methods[$i]];
			foreach($request[$methods[$i]] as $key => $value) {
				$request[$methods[$i]][$this->_lower($key)] = $value;
			};
    	}
		return $request;
    }

	private function _request($request) {

		$request = $this->_normilize_request($request);

		$uri = $this->_urldecode(
		    parse_url($request['server']['request_uri'], PHP_URL_PATH)
		);

		//$uri = $this->_substr($uri, 13);

		if(!$uri) {
			$uri = '/';
		}

		$_SERVER['REQUEST_URI'] = $uri;


		if(!$this->_is_empty($request['server'])) {

			if($this->_array_key('request_uri', $request['server'])) {
				$request['server']['request_uri'] = $this->_trim($request['server']['request_uri']);

				if($this->_substr($request['server']['request_uri'], 0, 1) == $this->_url_s()) {
					if($request['server']['request_uri'] !== $this->_url_s()) {
						$parsed = $this->_parse_url($request['server']['request_uri']);
					} else {
						$parsed['path'] = '';
						$parsed['query'] = '';
					}

					$query_string = '';
		        	if(isset($parsed['query'])) {
			            parse_str(html_entity_decode($parsed['query']), $query);
			            if(!empty($get)) {
			                $query_string = http_build_query(array_replace($query, $get), '', '&');
			            } else {
			                $query_string = $parsed['query'];
			            }
			        } else {
			        	if(!empty($get)) {
			        		$query_string = http_build_query($get, '', '&');
			        	}
			        }

					$request['server']['request_path'] = $this->_to_url_s($parsed['path']);
			        $request['server']['query_string'] = $query_string;
			        $request['server']['request_uri'] = $parsed['path'];
					if($query_string) {
						$request['server']['request_uri'].='?'.$query_string;
					}
					$request['server']['token'] = $this->_get('static.route.grower');
				} else {
					$this->fail('Fattal error: Incorrect [ REQUEST_URI ] must begin with [ '.$this->_url_s().' ]');
				}
			} else {
				$this->fail('Fattal error: [ REQUEST_URI ] is missing.');
			}
		} else {
			$this->fail('Fattal error: Your request does not have the necessary information');
		}

		$this->_set('static.request', $request);

		if($this->_method('post')) {
			$this->_set('static.request.old', $request['post']);
		}

		$this->_delete('static.url_paths');
		$this->_set('static.url_paths', $this->_path_to_array($this->_path(), $this->_url_s()));

	}

	private function _path() {
		return $this->_get('static.request.server.request_path');
	}

	private function _method($m = null) {
		$method = $this->_lower($this->_get('static.request.server.request_method'));
		return $m === null ? $method : $method == $m;
	}

	private function _isajax() {
		return $this->_get('static.request.server.http_x_requested_with', 'XMLHttpRequest');
	}

	private function _is_secure() {
		return $this->_get('static.request.server.https', 'on');
	}

	private function _scheme() {
		return $this->_is_secure() ? 'https' : 'http';
	}

	private function _download($link) {
		
	}

	public function _host() {
		return $this->_get('static.request.server.http_host');
	}

	private function _url($extend = '', $args = [], $replace = []) {
		$args = $this->_array_merge($args, $replace);
		$url = $this->_scheme();
		$url.= '://'.$this->_host();
		$url.= $extend ? $this->_url_s().$this->_to_url_s($extend) : '';
    	$url.= !$this->_is_empty($args) ? '?'.$this->_http_build_query($args) : '';
		return $url;
	}

	private function _is_gp($key) {
		if($this->_isset('static.request.post.'.$key)) {
			return 1;
		}
		return $this->_isset('static.request.get.'.$key);
    }

	private function _gp($key) {
		if($this->_isset('static.request.post.'.$key)) {
			return $this->_get('static.request.post.'.$key);
		}
		return $this->_get('static.request.get.'.$key);
    }

	private function _is_g($key) {
		return $this->_isset('static.request.get.'.$key);
    }

	private function _g($key) {
		return $this->_get('static.request.get.'.$key);
    }

	private function _is_p($key) {
		return $this->_isset('static.request.post.'.$key);
    }

	private function _p($key) {
		return $this->_get('static.request.post.'.$key);
    }

	private function _url_s() {
		return '/';
	}

	private function _array_from_string($case, string $str) {
		$results = [];
		if($case == 'lines') {
			$str = $this->_explode_lines($str);
			$j = 0;
			for($i=0;$i<$this->_count($str);$i++) {
				$str[$i] = $this->_trim($str[$i]);
				if($str[$i] !== '') {
					$results[$j] = $str[$i];
					$j++;
				}
			}
		}
		return $results;
	}

	private function _change_prefix(string $from, string $to, string $str) {
		$len = $this->_length($from);
		if($this->_substr($str, 0, $len) == $from) {
			$str = $this->_substr($str, $len);
		}
		return $to.$str;
	}

	private function _remove_prefix(string $prefix, string $str) {
		$len = $this->_length($prefix);
		if($this->_substr($str, 0, $len) == $prefix) {
			$str = $this->_substr($str, $len);
		}
		return $str;
	}

	private function _implode_lines($array) {
		return $this->_implode($this->_new_line(), $array);
	}

	private function return($return) {
		return $return;
	}

    private function _ucwords($str) {
        return ucwords($str);
    }

	private function _require_once($file) {
		$document = $this->_signal();
		return require_once($file);
	}

	private function _require_local($brosta_file, $brosta_data = []) {
		if(!$this->_is_empty($brosta_data)) {
			extract($brosta_data, EXTR_SKIP);
		}
		return require($brosta_file);
	}

	private function _pos($haystack, $needle) {
		return mb_strpos($haystack, $needle);
	}

	private function _preg_replace($regex, $expresion, $string) {
		return preg_replace($regex, $expresion, $string);
	}

	private function _remove_spaces(string $str) {
		return preg_replace('/\s+/', '', $str);
	}

	private function _replace_spaces_with_one($str) {
		return preg_replace('/\s+/', ' ', $str);
	}

	private function _replace($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}

	private function _fwrite($file, $contents, $lock = false) {
		return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
	}

	private function _substr(string $string, $start = 0, $length = null) {
		if(is_null($length)) {
			return mb_substr($string, $start);
		} else {
			return mb_substr($string, $start, $length);
		}
	}

	private function _is_array($obj) {
		return is_array($obj) ? 1 : 0;
	}

	private function _parse_url($str) {
		return parse_url($str);
	}

	private function _urldecode($str) {
		return urldecode($str);
	}

	private function _is_dir($obj) {
		return is_dir($obj);
	}

	private function _is_empty($obj) {
		return empty($obj);
	}

	private function _array_key($key, $array) {
		return array_key_exists($key, $array);
	}

	private function _get_file_contents($file, $lock = false) {
		return file_get_contents($file, $lock);
	}

	private function _exit() {
		exit;
	}

	private function _unlink($file) {
		return @unlink($file);
	}

	private function _count($array = null) {
		if($this->_is_init) {
			if($array === null) {
				return count($this->_all());
			} else {
				if($this->_is_string($array)) {
					return count($this->_get($array));
				}
			}
		}
		return count($array);
	}

	private function _basename($path) {
		return basename($path);
	}

	private function _operating($commands) {
		return shell_exec('powershell.exe '.$commands);
	}

	private function _array_zero($array) {
		return array_values($array);
	}

	private function _call_class_method() {

		$ichnos = func_get_args();

		if(isset($ichnos[2])) {
			return call_user_func_array([$ichnos[0], $ichnos[1]], $ichnos[2]);
		}
		elseif(isset($ichnos[1])) {
			return call_user_func_array([$ichnos[0], $ichnos[1]]);
		}
		elseif(isset($ichnos[0])) {
			return $this->_get($ichnos[0])();
		} else {
			return function() {
					
			};
		}

	}

	private function _implode($sep, $array) {
		return implode($sep, $array);
	}

	private function _explode($sep, $string) {
		return explode($sep, $string);
	}

	private function _in_array($key, $array) {
		return in_array($key, $array);
	}

	private function _file_append($file, $contents) {
		return file_put_contents($file, $contents, FILE_APPEND);
	}

	private function _make_dir_force($file, $mode = 493, $recursive = true) {
		return @mkdir($file, $mode, $recursive);
	}

	private function _print($str) {
		return print_r($str);
	}

	private function _json_encode($data) {
		return json_encode($data);
	}

	private function _json_decode($data) {
		return json_decode($data);
	}

	private function _copy_file($path, $target) {
		return copy($path, $target);
	}

	private function _length($str) {
		return mb_strlen($str);
	}

	private function _is_object($element) {
		return is_object($element);
	}

	private function _is_null($element) {
		return is_null($element);
	}

	private function _is_string($element) {
		return is_string($element);
	}

	private function _lower($str) {
		return strtolower($str);
	}

	private function _upper($str) {
		return strtoupper($str);
	}

	private function _file_extention($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

	private function _file($file) {
		return file_exists($file);
	}

	private function _trim($str, $mask = null) {
		if(is_null($mask)) {
			return trim($str);
		} else {
			return trim($str, $mask);
		}
	}

	private function _rtrim($source, $sym = null) {
		if(is_null($sym)) {
			return rtrim($source);
		} else {
			return rtrim($source, $sym);
		}
	}

	private function _ltrim($source, $sym = null) {
		if(is_null($sym)) {
			return ltrim($source);
		} else {
			return ltrim($source, $sym);
		}
	}

	private function _build_attributes($attrs, $tag) {
		$results = '';
		foreach($attrs as $attr => $value) {
			if($results) {
				$results.=' ';
			}
			if($this->_is_null($value) || $this->_trim($value) == '') {
				$value = '';
			}
			if($value) {
				if($attr == 'style') {
					if(!$this->_last_in($this->_trim($value), ';')) {
						$value.=';';
					}
				}
			}
			if($this->_is_numeric($attr)) {
				$results.=$value;
			} else {
				if($tag == 'doctype') {
					if($this->_trim($value) == '') {
						if($attr == 'html') {
							$results.=$attr;
						}
					} else {
						if($attr == 'html') {
							$results.=$attr;
						} else {
							$results.=$attr.'="'.$value.'"';
						}
					}
				} else {
					$results.=$attr.'="'.$value.'"';
				}
			}
		}

		$results = $this->_trim($results);
		$results = $results ? ' '.$results : '';

		return $results;
	}

	private function _build_document($data, $level = 0) {
		$contents = '';
		for($i=0;$i<count($data);$i++) {
			$item = $data[$i];
			$item['tag_lower'] = $this->_lower($item['tag']);
			$item['new_line_after'] = $this->_new_line();
			$item['new_line_before'] = $this->_new_line();
			$spaces_num = ($level + $item['my_space']) + $item['start_code_space_level'];
			if($this->_isset('tmp.spaces.'.$spaces_num)) {
				$item['space'] = $this->_get('tmp.spaces.'.$spaces_num);
			} else {
				$item['space'] = $this->_set('tmp.spaces.'.$spaces_num, $this->_space_like_tab($spaces_num));
			}
			$item = $this->{$this->_get_fprefix().'code_'.$item['doctype']}($item, $level);
			if(!$this->_is_empty($item['items'])) {
				$item['nested'] = $this->_build_document($item['items'], $item['level']);
			}
			$contents.=$this->_get_builded_text($item);
		}
		return $contents;
	}

	private function _code_html($item, $level = 0) {

			$item['attrs'] = "";

			if(!$this->_is_empty($item['attr'])) {
				$item['attrs'] = $this->_build_attributes($item['attr'], $item['tag_lower']);
			}

			switch($item['tag_lower'])
			{
				case'untaged':
					
				break;
				case'doctype':
					$item['tag_before'] = $item['open_tag'].'!'.$this->_upper($item['tag']).$item['attrs'].$item['close_tag'];
				break;
				case'title':
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attrs'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
				break;
				case'input':
				case'meta':
				case'link':
				case'source':
				case'track':
				case'param':
				case'img':
				case'keygen':
				case'hr':
				case'br':
				case'embed':
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attrs'].' /'.$item['close_tag'];
					if($item['nested']) {
						$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attrs'].$item['close_tag'];
						$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
					}
				break;
				default:
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attrs'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
				break;
			}

		return $item;
	}

	private function _code_javascript($item, $level = 0) {

			switch($item['tag_lower'])
			{
				case'untaged':
					if(isset($item['fake_line'])) {
						$item['tag_lower'] = '';
						$item['tag_before'] = '';
						$item['space'] = '';
						$item['tag_after'] = '';
					}
				break;
				case'function':
					if(isset($item['attr']['assigned']) && $item['attr']['assigned']) {
						if(isset($item['attr']['instance'])) {
							$item['tag_before'].=$item['attr']['instance'].'.'.($this->_is_array($item['attr']['name']) ? $this->_implode('.', $item['attr']['name']) : $item['attr']['name']);
						} else {
							$item['tag_before'].='var '.$item['attr']['name'];
						}
						$item['tag_before'].=' = function';
					} else {
						$item['tag_before'] = 'function '.$item['attr']['name'];
					}
					$args ='';
					if(isset($item['attr']['arguments'])) {
						foreach($item['attr']['arguments'] as $arg) {
							if($args) {
								$args.=', ';
							}
							$args.=$arg['name'];
						}
					}
					$item['tag_before'].='('.$args.') {';
					if(isset($item['attr']['body'])) {
						$item['tag_before'].=$item['attr']['body'];
					}
					$item['tag_after'] = '}';

					$item['tag_after'] = '}';
				break;
				default:
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].$item['tag'].$item['close_tag'];
				break;
			}

		return $item;
	}

	private function _code_php($item, $level = 0) {

			switch($item['tag_lower'])
			{
				case'untaged':
					$item['tag_lower'] = '';
					if(isset($item['attr']['lined']) && $item['attr']['lined']) {
						$item['space'] = '';
						$item['tag_before'] = '';
						$item['tag_after'] = '';
					}
				break;
				case'echo':
					$item['tag_before'].= 'echo(';
					$item['tag_after'].= ');';
				break;
				case'foreach':
					$item['tag_before'].= 'foreach( $'.$item['attr']['array'].' as '.($this->_array_key('key', $item['attr']) ? '$'.$item['attr']['key'].' => $'.$item['attr']['value'] : '$'.$item['attr']['value']).' ) {';
					$item['tag_after'].= '}';
				break;
				case'namespace':
					$item['tag_before'].= 'namespace '.$this->_class_separator_fix($item['attr']['name']).';';
					$item['tag_after'].= '';
				break;
				case'define':
					if($this->_count($item['attr']) == 1) {
						foreach($item['attr'] as $key => $value) {
							$item['tag_before'].= 'define(\''.$this->_upper($key).'\', '.$this->_fix_type($value, $level).');';
						}
						$item['tag_after'].= '';
					}
				break;
				case'use':
					$item['tag_before'].= 'use '.$this->_class_separator_fix($item['attr']['name']).';';
					$item['tag_after'].= '';
				break;
				case'property':
					if($this->_array_key('visibility', $item['attr']) && $this->_trim($item['attr']['visibility'])) {
						$item['tag_before'].=$item['attr']['visibility'].' ';
					}
					if($this->_array_key('is_static', $item['attr']) && $item['attr']['is_static'] === 1) {
						$item['tag_before'].='static ';
					}
					$item['tag_before'].= '$'.$item['attr']['name'];
					if($this->_array_key('value', $item['attr'])) {
						$item['tag_before'].=' = '.$this->_fix_type($item['attr']['value'], $level);
					}
					$item['tag_before'].=';';
				break;
				case'class':
					$item['tag_before'].= 'class '.$item['attr']['name'].' {';
					$item['tag_after'] = '}';
				break;
				case'function':
					if($this->_array_key('visibility', $item['attr']) && $this->_trim($item['attr']['visibility']) !== '') {
						$item['tag_before'].=$item['attr']['visibility'].' ';
					}
					if($this->_array_key('is_static', $item['attr']) && $item['attr']['is_static'] === 1) {
						$item['tag_before'].='static ';
					}
					$item['tag_before'].= 'function '.$item['attr']['name'];
					$args ='';
					if($this->_array_key('arguments', $item['attr']) && !$this->_is_empty($item['attr']['arguments'])) {
						foreach($item['attr']['arguments'] as $arg) {
							if($args) {
								$args.=', ';
							}
							if($arg['type']) {
								$args.=$arg['type'].' ';
							}
							if($this->_array_key('bref', $arg) && $arg['bref'] === 1) {
								$args.='&';
							}
							$args.='$'.$arg['name'];
							if($this->_array_key('value', $arg)) {
								$args.=' = '.$this->_fix_type($arg['value'], $level);
							}
						}
					}
					$item['tag_before'].='('.$args.') {';
					if($this->_array_key('body', $item['attr'])) {
						$item['attr']['body']=rtrim($item['attr']['body'], "\n");
						$item['tag_before'].=$item['attr']['body'];
					}
					$item['tag_after'] = '}';
				break;
			}

		return $item;
	}

	private function _get_builded_text($item) {
		$contents = "";
		if($item['tag_before'] !== null && $item['tag_lower'] != 'untaged') {
			$contents.=$item['new_line_before'];
		}
		if($item['nested'] !== null && $item['tag_after'] !== null && $item['tag_lower'] != 'untaged') {
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

	private function _get_alpha_from_lower_to_upper($code = null, $type = 'symbol') {
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

	private function _get_alpha_from_upper_to_lower($code = null, $type = 'symbol') {
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

	private function _get_alpha_lower($code = null, $type = 'symbol') {
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

	private function _get_alpha_upper($code = null, $type = 'symbol') {
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

	private function _get_numbers($code = null, $type = 'symbol') {
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

	private function _get_symbol_chars($code = null, $type = 'symbol') {
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

	public function _get_arguments() {
		return $this->_get('static.function.arguments');
	}

	public function _load_functions() {
		if($this->_isset('signal.functions')) {
			if($this->_set('static.functions', $this->_get('signal.functions'))) {
				$this->_delete('signal.functions');
				return 1;
			}
		} else {
			if($this->_function_exists('_class_get_as_array')) {
				if($this->_set('signal.functions', $this->_class_get_as_array(__CLASS__)['methods'])) {
					if($this->_load_functions()) {
						return 1;
					}
				}
			}
		}
		return 0;
	}

	public function _is_loaded_functions() {
		return $this->_isset('static.functions');
	}

	public function _function_exists($name) {
		return 1;
		return $this->_isset('static.functions.'.$name);
	}

	public function _first_app_clng($type) {
		return $this->_get('static.first_export_doctype', $type);
	}

	public function bridge($object, $method, $arguments) {

		$object = $this->_get_root_document($object);

		if(!$object) {
			return $this->_call_class_method($object, $method, $arguments);
		} else {

			$prefix = $this->_get_fprefix();

			$method = $prefix.$this->_remove_prefix($prefix, $method);

			$ready = 0;

			if($this->_function_exists($method)) {

				$this->_is_init = 1;
				$this->_id = $object->key ? $object->key : 'app';

				$results = $this->_call_class_method($this, $method, $arguments);
				$this->_id = 'app';
				$this->_is_init = 0;
				if(is_object($results) && $results instanceof $this) {
					return $object;
				}
				return $results;
			} else {
				if($this->_is_loaded_functions()) {

					if($this->_load_functions()) {
						$ready = 1;
					}

					// -------------------------------------------------------------------
					while($ready === 1) {
						$ready = 0;
						return $this->bridge($client, $method, $arguments);
					}

				} else {
					$this->_fail('system', 'Core functions cannot loaded.');
				}
			}
		}
	}

}

// R5KA41SC

?>
