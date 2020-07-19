<?php

namespace Brosta\Interframework;

use Closure;
use stdClass;
use DateTime;
use DateTimeZone;
use ReflectionClass;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Cookie\CookieJar;
use Brosta\Interframework\Manager;

define('LICENSE_ID', 'UZ3I-J2MB-9WXC-IXRT-KDL2');
define('NOTHING', '60371014510810961621243511712512340101105');

class Signal {

	public $key = 'public';

	protected function _start(string $location, $default) {
		
	}

	protected function _app_body($data, $attrs, $type) {
		
	}

	protected function _build_application($data, $attrs, $type) {
		$ram = $this->_ram;

		$this->_doctype($this->_get('app.code'));
		$this->_set_start_code_space_level($this->_get('app.start_code_space_level'));

		$as_helper = isset($data['as_helper']) && $data['as_helper'] == 1;

		if($data['in_namespace']) {
			if(!$as_helper) {
				$this->_tag('namespace')->_attr('name', $this->_get('app.namespace'));
				$this->_fixed_space(-1);
				$this->_enter();
			}
		}

		if($data['in_class']) {
			if(!$as_helper) {
				$this->_tag('class')->_attr('name', $this->_get('app.name'));
			}
		}

		foreach($data['methods'] as $m) {
			if($as_helper && $m['visibility'] !== 'public') {
				continue;
			}

			if($m['visibility'] == 'public') {
				$this->_tag('function');
				foreach($attrs as $name) {
					if($name == 'body') {
						$m[$name] = $this->_ascii_to_text($m[$name]);
						if($type == 'javascript') {
							$m[$name] = $this->_replace('$this->_', '', $m[$name]);
						}
						elseif($type == 'php') {
							$m[$name] = $this->_replace('$this->_', '$this->', $m[$name]);
						}
					}
					elseif($name == 'arguments') {
						if($type == 'javascript') {
							//
						}
						elseif($type == 'php') {
							//
						}
					}
					elseif($name == 'name') {
						
					}
					$this->_attr($name, $m[$name]);
				}
				$this->_tag();
				$this->_enter();
			}
		}

		if($data['in_namespace']) {
			if(!$as_helper) {
				$this->_tag();
			}
		}

		if($data['in_class']) {
			if(!$as_helper) {
				$this->_tag();
			}
		}

		if($results = $this->_final()) {
			$this->_ram = $ram;

			if($type == 'php') {
				$results = '<?php'.$this->_new_line().$results.$this->_new_line().'?>';
			}

			return $results;
		}
		return $this->_fatal('build', 'Can not create application');
	}

	protected function _stop() {

		if(!$this->_include_exists('_common/versions/'.$this->_doctype().'/aplication_'.$this->_dot_to_underscore($this->_get('app.version')))) {
			$this->_make_include('_common/versions/'.$this->_doctype().'/aplication_'.$this->_dot_to_underscore($this->_get('app.version')), $this->_build_application($this->_class_to_array('Brosta\Interframework\Signal'), ['name', 'arguments', 'body'], 'php'));
		}

		if($this->_set('static.system.ready', 1)) {
			if($this->_ini('helpers')) {
				if(!$this->_file($this->_boot_path('helpers.php'))) {
					$app = $this->_class_to_array('Brosta\Interframework\Signal');
					sort($app['methods']);
					$app['as_helper'] = 1;
					$app['in_class'] = 0;
					$app['in_namespace'] = 0;
					$this->_mkdirs([
						[
							'name' => $this->_boot_path(),
							'files' => [
								[
									'name' => 'helpers.php',
									'contents' => '<?php'.$this->_new_line().$this->_new_line().'use Brosta\Interframework\Manager;'.$this->_new_line().$this->_build_application($app, ['name', 'arguments'], 'php').$this->_new_line().'?>'
								]
							]
						]
					]);
				} else {
					$this->_require_local($this->_boot_path('helpers.php'), null);
				}
			}
			if(!$this->_ini('safe_mode')) {
				if(!$this->_file($this->_assets_path('app.js'))) {
					$app = $this->_class_to_array('Brosta\Interframework\Signal');
					$app['as_helper'] = 0;
					$app['in_class'] = 0;
					$app['in_namespace'] = 0;
					$this->_mkdirs([
						[
							'name' => $this->_public_path('assets'),
							'files' => [
								[
									'name' => 'app.js',
									'contents' => 'var '.$app['name'].' = {};'.$this->_new_line().$this->_new_line().$this->_build_application($app, ['name', 'arguments', 'body'], 'javascript').$this->_new_line().'};'
								]
							]
						]
					]);
					return $this->_redirect($this->_url('admin/update?type=PHP'));
				}
			}
			$this->_conclude();
		}

	}

	protected function _monitor($contents = '') {
		if($this->_isset('display.top_comment')) {
			$this->_text($this->_get('display.top_comment'));
		}
		$this->_tag('doctype')->_attr('html')->_tag();
		$this->_tag('html')->_attr('class', 'cm-html');
			if($this->_isset('display.doctype_lang')) {
				$this->_attr('lang', $this->_get('display.doctype_lang'));
			}
			$this->_tag('head');
				if($this->_isset('display.meta.charset')) {
					$this->_tag('meta')->_attr('charset', $this->_get('display.meta.charset'))->_tag();
				}
				$this->_tag('meta')->_attr('name', 'viewport')->_attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->_tag();
				$this->_tag('meta')->_attr('httpequiv', 'Content-Type')->_attr('content', 'text/html; charset=UTF-8')->_tag();
				$this->_tag('meta')->_attr('id', 'domain')->_attr('content', 'My domain')->_tag();
				$this->_tag('title')->_text($this->_get('title'))->_tag();
				$this->_tag('link')->_attr('rel', 'icon')->_attr('type', 'image/png')->_attr('href', $this->_assets_url('img/favicon.png'))->_tag();
				$this->_load_components('css');
				$this->_load_styles();
			$this->_tag();
			$this->_tag('body')->_attr('id', $this->_get('with.body.id'))->_attr('class', 'cm-body');
				$this->_text($contents, 1);
				$this->_load_components('js');
				$this->_load_the_scripts_components();
			$this->_tag();
		$this->_tag();

		return 1;
	}

	protected function _startup($closures) {
		$closures = $this->_get('this.startup.'.$this->_underscore_to_dot($closures));
		if($this->_is_array($closures)) {
			for($i=0;$i<$this->_count($closures);$i++) {
				$closures[$i]();
			}
		} else {
			$closures();
		}
	}

	protected function _ready($ready, $value = null) {
		if($value === null) {
			return $this->_get('ready.'.$what);
		} else {
			return $this->_set('ready.'.$what, $value);
		}
	}

	protected function _module(string $action, array $arguments = null) {
		return $this->_get('modules.'.$action);
	}

	protected function _os_is($os) {
		$os = $this->_explode_colon($os);
		$static_os = $this->_explode_colon($this->_get('os.operating_system'));
		for($i=0;$i<$this->_count($os);$i++) {
			if($static_os[$i] !== $os[$i]) {
				return false;
			}
		}
		return true;
	}

	protected function _vendor_ready() {
		if($this->_os_is('windows:10')) {
			if($this->_file($this->_provider_path('composer.json'))) {
				$this->_require_once($this->_provider_path('vendor/autoload.'.$this->_get('flammable.code_lng_ext')));
			} else {
				if($this->_make_file_force($this->_provider_path('composer.json'), $this->_settings('vendor'))) {
					if($handle = $this->_operating('-command cd '.$this->_provider_path().'; composer install;')) {
						return $this->_redirect($this->_url());
					} else {
						$this->_fatal('vendor', 'Provider FILE NOT FOUND : " vendor/autoload.'.$this->_get('flammable.code_lng_ext').'"');
					}
				}
			}
		}
		return 1;
	}

	protected function _get_default_static($default = []) {
		return $this->_array_replace([
			'this' => [
				'items'						=> [],
				'keep'						=> [],
				'bodyclass'					=> [],
				'form_tag_name'				=> 'form',
				'count'						=> 0,
				'index'						=> 0,
				'level'						=> 0,
				'tag_is_opened'				=> 0,
				'start_code_space_level'	=> 0,
				'on' => [
					'load' => [],
					'click' => [],
					'submit' => [],
					'blur' => [],
					'change' => [],
					'drag' => [],
					'focus' => [],
					'construct' => [],
				],
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
						'style' => [],
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
						'style' => [],
					]
				]
			]
		], $default);
	}

	protected function _clear_ends($algorithms, $separator) {
		return $this->_explode($separator, $this->_trim($this->_remove_spaces($algorithms), $separator));
	}


	protected function _server($data) {
		if($this->_is_array($data)) {
			
		}
	}


	protected function _get_application() {
		return $this->_set('flammable.application', $this->_brosta_decode($this->_file_get_contents($this->_boot_path('application.'.$this->_get('flammable.code_lng_ext'))), true));
	}

	protected function _get_ready() {
		if(!$this->_isset('static.root')) {
			return 1;
		}
		if(!$this->_file($this->_boot_path('application.'.$this->_get('flammable.code_lng_ext')))) {
			$data = $this->_class_to_array('Brosta\Interframework\Signal');
			if($this->_make_file($this->_boot_path('application.'.$this->_get('flammable.code_lng_ext')), $this->_brosta_encode($data))) {
				return $this->_get_ready();
			}
		} else {
			return $this->_get_application();
		}
	}

	protected function _dataset(array $data, $value) {
		$fixed = [];
		for($j=0;$j<$this->_count($data);$j++) {
			$fixed[$data[$j]] = $value;
		}
		return $fixed;
	}

	protected function _set_algorithms($count, $algorithms, $location) {
		$configs = [];
		for($i=0;$i<$count;$i++) {
			$data = $this->_require_local($location.$this->_dirsep().$algorithms[$i].'.'.$this->_get('flammable.code_lng_ext'), null, null);
			if($algorithms[$i] == 'ini') {
				$this->_set('ini', $data);
				$this->_set('ini.presets', $this->_dataset($this->_get('ini.presets'), 1));
			} else {
				$this->_set($algorithms[$i], $data);
			}
		}
	}

	protected function _security($level = 0, $setch) {
		return $this->_get('static.security.'.$level)($this);
	}

	protected function _reset_ram($location, $default) {

		$location = $this->_rtrim($location, $this->_dirsep());

		$this->_set('static', $this->_get_default_static($default));

		if($this->_security(0, 'passed')) {
			$this->_set_algorithms($this->_count($algorithms), $algorithms, $location.$this->_dirsep().$this->_config_path());
		}

		$domain = $this->_public_path();

		if($this->_substr($domain, 0, 1) == $this->_dirsep()) {
			$domain = $this->_urlsep().$this->_to_urlsep($domain);
		}

		$dna_file = $location.$this->_dirsep().$this->_storage('tmp/dna.bro');

		if($dna = $this->_file($dna_file)) {
			$dna = $this->_file_get_contents($dna_file);
			$this->_delete_file($dna_file);
		} else {
			$this->_delete('static.root');
		}

		// tiktoktiktoktiktok

		$this->_root($this->_get('app.root')); 
		//AFTER a url, local url etc. is seted (ROM)

		if($ready = $this->_get_ready()) {

			$this->_boot_autoloaders();
			$this->_set_ini_presets([
				'request_from_globals' => 1,
				'local' => 1,
				'router' => 1,
				'vendor' => 1,
				'view' => 1,
				'bridge' => 1,
				'safe_mode' => 0,
				'helpers' => 1,
				'auto_make_controller' => 1,
			]);

			// Check if your license id is correct
			if(LICENSE_ID !== $this->_get('app.license_id')) {
				$this->_fatal('your_license_id_is_not_correct');
			}
			// Load the providers management
			if($this->_ini('vendor')) {
				$this->_vendor_ready() ? $this->_module('vendor.composer')() : $this->_fatal('vendor', 'can not be load');
			}
			if($this->_ini('request_from_globals')) {
				// Create request from the global vars
				$this->_request_from_globals();
				// ----------------------------
			} else {
				// Create request from the system vars
				$this->_request_from_local();
				// ----------------------------
			}
		}

		$this->_reset_this();
		$this->_set('static.is_started', 1);

		return 1;

	}

	protected function _var($phrase) {
		if(!$this->_isset('flammable.vars.'.$phrase)) {
			return $this->_set('flammable.vars.'.$phrase, $this->_replace('_', '.', $phrase));
		}
		return $this->_get('flammable.vars.'.$phrase);
	}

	protected function _set_ini_presets(array $array) {
		foreach($array as $key => $value) {
			if($this->_is_numeric($value)) {
				if($this->_get($this->_var('ini_presets').'.'.$key, 1)) {
					$this->_ini($key, $value);
				} else {
					if($value === 1) {
						$this->_fatal($this->_var('ini_presets'), '( '.$key.' ) Not allowed');
						return;
					}
				}
			} else {
				$this->_fatal('ini_presets', 'ini_presets must be a numeric 0 or 1');
			}
		}
	}

	public function _ini($key, $value = null) {
		if($value === 0 || $value === 1 || $value === null) {
			if(!$this->_isset('ini.presets.'.$key)) {
				$this->_fatal('ini', 'Broken '.$this->_config_path('ini.'.$this->_get('flammable.code_lng_ext')).' file ( '.$key.' )');
			}
			if($value === null) {
				return $this->_get('ini.'.$key, 1);
			} else {
				if($value === 1) {
					if(!$this->_get('ini.presets.'.$key, 1)) {
						$this->_fatal('ini', '( '.$key.' ) Not allowed');
						return;
					}
				}
				$this->_set('ini.'.$key, $value);
			}
		} else {
			$this->_fatal('argument', 'Unexpected ini SET from key. Reason: Argument 2 must be an integer 0 or 1');
		}
	}

	protected function _is_started() {
		return $this->_get('static.is_started', 1);
	}

	protected function _transfer($key) {
		$data = $this->_get($key);
		$this->_delete($key);
		return $data;
	}

	protected function _brosta_encode($data) {
		return $this->_json_encode($data);
	}

	protected function _brosta_decode($data, $as_array) {
		return $this->_json_decode($data, $as_array);
	}

	protected function _get_root_document($ichnos = null) {
		$root_document = $this->_get('static.root_document');
		if($ichnos === null) {
			return $this->_get('static.root_document');
		}
		return $ichnos;
	}

	protected function _set_root_document($manager) {
		if($this->_is_string($manager)) {
			//
		} else {
			//
		}

		if($manager) {
			if($this->_set('root.root_document', $manager)) {
				return 1;
			}
		}
		return 0;
	}

	protected function _is_function($data) {
		return is_callable($data);
	}

	protected function _cookie($cookie) {
		$this->_set('cookie', $cookie);
		return $this;
	}

	protected function _token($id = null) {
		if($id) {
			$this->_set('static.token', $id);
		} else {
			return $this->_get('static.token');
		}
	}

	protected function _token_ok($id) {
		return $this->_token() === $id;
	}

	protected function _client_is_browser($array) {
		if($this->_isset('client.is_browser')) {
			return 0;
		}
	}

	protected function _reset_this() {

		$resources = $this->_get('this.resources');
		$this->_delete('this.items');
		$this->_set('this', $this->_get('static.this'));
		if($resources) {
			$this->_set('this.resources', $resources);
		}
		$this->_set('tag', $this->_new_tag());
	}

	protected function _on($event, $callback) {
		$this->_push('on.'.$event, $callback);
	}


	protected function _get_template() {
		if(!$this->_isset('static.template.name')) {
			return $this->_set('static.template.name', 'default');
		}
		return $this->_get('static.template.name');
	}

	protected function _set_template($name) {
		return $this->_set('static.template.name', $name);
	}

	protected function _request_from_globals() {
		$this->_request([
			'get' => $_GET,
			'post' => $_POST,
			'files' => $_FILES,
			'cookie' => $_COOKIE,
			'server' => $_SERVER,
		]);
	}

	protected function _request_from_local() {
		$this->_request([
			'get' => $this->_get('request.get'),
			'post' => $this->_get('request.post'),
			'files' => $this->_get('request.files'),
			'cookie' => $this->_get('request.cookie'),
			'server' => $this->_get('request.server'),
		]);
	}

	public function _add_header() {
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

	public function _add_sidebar($pos, $data) {
		$this->_tag('div')->_attr('id', 'sidebar')->_attr('class', 'cm-sidebar cm-sidebar-'.$pos);
			$this->_text('fewfwf');
		$this->_tag();
	}

	protected function _unset($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {
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

	protected function _path_to_array($path, $sep = '/') {
		return $this->_explode($sep, $path);
	}

	public function _button_submit($text) {
		$this->_tag('div')->_attr('class', 'cm-field cm-clearfix');
			$this->_tag('button')->_attr('type', 'submit')->_class('cm-btn cm-form-btn cm-right')->_text($text)->_tag();
		$this->_tag();
	}

	protected function build_class($class) {
		$editor = $this->editor('php');
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
		return '<?php'.$this->new_line().$editor->final().$this->new_line().'?>';
	}

	public function _add_field($field) {
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

	protected function _autoload_register_namespace_prefix($name, $path) {
		if($this->_isset('autoloader.prefix.'.$name)) {
			$this->_fatal('_autoload_register_namespace_prefix', 'Namespace: ( '.$name.' ) has already in use');
		}

		$path = $this->_to_dirsep($path);
		if($path == '') {
			$path = $this->_dirsep();
		}

		$this->_set('autoloader.prefix.'.$name, $path);
		$this->_set('autoloader.loaded.'.$name, []);
	}

	protected function _autoload_namespace_prefix_registered($name) {
		return $this->_isset('autoloader.prefix.'.$name);
	}

	protected function _autoload_get_name_stracture($name) {
		$structure = $this->_base_array_file($this->_class_separator_fix($name), '\\');

		if(!$this->_autoload_namespace_prefix_registered($structure['dirname'])) {
			$this->_fatal('autoloader', 'The namespace name ( '.$structure['dirname'].' ) does not registered. Use: autoload_register_namespace_prefix("PREFIX_NAME", "PATH_LOCATION") to register your namespace name.');
		} else {
			$structure['location'] = $this->_get('autoloader.prefix.'.$structure['dirname']);
			$namespace = $structure['dirname'];
			if($structure['subdirectory']) {
				$rest = $this->_implode($structure['subdirectory'], '/');
				$namespace.='\\'.$this->_class_separator_fix($rest);
				$structure['location'].='/'.$rest;
			}
			$structure['location'] = $this->_to_dirsep($structure['location'].'/'.$structure['filename']).'.'.$this->_get('flammable.code_lng_ext');
			$structure['classname'] = $namespace.'\\'.$structure['filename'];
			return $structure;
		}
	}

	protected function _make_class($data) {
		//
	}

	protected function _make_controller($path) {
		$editor = $this->_editor('php');
		//Open namespace
		$editor->tag('namespace')->attr('name', $this->_ucfirst_bysep('\\', $this->_class_separator_fix('App\\'.$path)))->enter();
		$editor->fixedSpace(-1);
		$editor->enter();
		//Open class
		$editor->tag('class')->attr('name', $this->_get('route.controller').'_controller')->enter();
			$editor->tag('function')->attr('name', $this->_get('route.method'));
				$editor->attr('visibility', 'public');
				$editor->attr('is_static', 0);
				$editor->attr('arguments', []);
				$editor->attr('body', 'return null;');
			$editor->tag();
		$editor->enter();
		//close class
		$editor->tag();
		//close namespace
		$editor->tag();

		if($this->_ini('local')) {
			if($this->_make_file($this->_app_path($path.$this->_get('route.controller').'_controller.'.$this->_get('flammable.code_lng_ext')), '<?php'.$this->_new_line().$editor->final().$this->_new_line().'?>')) {
				return 1;
			}
		}
		return 0;
	}

	protected function _global($key) {
		return $this->_get('static.globals.'.$key);
	}

	protected function _call_user_func_array($object, string $method, array $arguments = null) {
		return $object;
	}

	protected function _get_controller() {
		if(!$this->_isset('route.controller')) {
			$this->_fatal('route.controller.name.is.missing');
		}

		$app_namespace = $this->_no_disk($this->_app_path()).'\\';

 		$classname = $this->_get('route.controller');
 		$method = $this->_get('route.method');
 		$args = $this->_get('route.args');

		$path = 'controllers/';
		if($this->_is_admin()) {
			$path.='admin/';
		}

		$finalname = $app_namespace.$path.$classname.'_controller';

		$as_key = $this->_slash_to_dot('app.'.$path);

		if($this->_isset('static.controllers.'.$as_key)) {
			$instance = $this->_get('static.controllers.'.$as_key);
		} else {
			if(!$this->_file($this->_app_path($path.$classname.'_controller.'.$this->_get('flammable.code_lng_ext')))) {
				if($this->_ini('auto_make_controller')) {
					$instance = $this->_make_controller($path);
				} else {
					$this->_fatal('controllers', 'Controller name ( '.$classname.' ) not exists');
				}
			}
			$this->_set('static.controllers.'.$as_key, 1);
		}

		$instance = $this->_new($finalname);

		if(method_exists($instance, $method)) {
			$results = call_user_func_array([$instance, $method], [_interface([
				'view' => 'update/create',
				'title' => 'Brosta development',
				'department' => 'development',
				'sidebar' => [
					'top' => [],
					'left' => [],
					'right' => [],
					'bottom' => [],
				],
				'header' => [],
				'body' => [
					'id' => $this->_unique_wordslower(3),
					'class' => 'cm-body'
				],
				'container' => [
					'id' => $this->_unique_wordslower(3),
					'class' => 'cm-container'
				],
				'contents' => [
					'id' => $this->_unique_wordslower(3),
					'class' => 'cm-contents'
				],
				'standard_style_css' => $this->_get_standard_css(),
				'type' => _gp('type'),
				'form' => [
					'name' => 'update',
					'method' => 'post',
					'action' => _url('/admin/update', ['type' => $this->_gp('type')]),
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
							'default_value' => 'x_',
						],
						[
							'label' => 'Helpers prefix',
							'caption' => 'helpers_prefix',
							'type' => 'input',
							'input_type' => 'text',
							'value' => '',
							'default_value' => 'x_',
						],
						[
							'label' => 'Standard style css',
							'caption' => 'standard_style_css',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_standard_css()
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
							'label' => 'Standard js',
							'caption' => 'standard_js',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_standard_js()
						],
						[
							'label' => 'Standard js',
							'caption' => 'standard_js',
							'type' => 'textarea',
							'textarea_type' => '',
							'value' => '',
							'default_value' => $this->_get_standard_js()
						]
					]
				]
			])]);
			$this->_set('response.data', $results);
			$this->_set('response.status', 200);
		} else {
			$this->_set('response.status', 404);
		}
	}

	protected function _new($name, array $constructor = null) {
		$data = $this->_autoload_get_name_stracture($name);

		if(!$this->_class_loaded($data)) {
			$this->_push('autoloader.loaded.'.$data['dirname'], $data['location']);
			$this->_require_local($data['location'], null, null);
		}

		$name = $data['classname'];

		if($constructor) {
			$class = new $name(...$constructor);
		} else {
			$class = new $name;
		}

		return $class;
	}

	protected function _provider($name, array $constructor = null) {
		$data = $this->_autoload_get_name_stracture($name);

		if(!$this->_class_loaded($data)) {
			$this->_push('autoloader.loaded.'.$data['dirname'], $data['location']);
			$this->_require_local($data['location'], null, null);
		}

		$name = $data['classname'];

		if($constructor) {
			$class = new $name(...$constructor);
		} else {
			$class = new $name;
		}

		return $class;
	}

	protected function _class_loaded($data) {
		return $this->_in_array($data['location'], $this->_get('autoloader.loaded.'.$data['dirname'])) ? 1 : 0;
	}

	protected function _editor($type, $callback = null) {
		$editor = $this->_interface($this->_ram['public']);
		$editor->_doctype($type);
		if($callback) {
			$bound = $this->_bind($callback, $editor);
	    	return $bound();
    	}
		return $editor;
	}

	protected function _assets_url(string $url = '') {
		return $this->_url('assets/'.$this->_to_urlsep($url));
	}

	protected function _contains_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($needle[$i] !== '' && $this->_pos($haystack, $needle[$i]) !== false) {
				return true;
			}
		}
        return false;
	}

	protected function _delete_file($files) {
		$success = true;
        foreach($this->_is_array($files) ? $files : [$files] as $file) {
            if(!$this->_unlink($file)) {
				$success = false;
			}
        }
        return $success;
	}

	protected function _explode_dot(string $data) {
		return $this->_explode('.', $data);
	}

	protected function _explode_lines(string $data) {
		return $this->_explode($this->_new_line(), $data);
	}

	protected function _explode_colon(string $data) {
		return $this->_explode(':', $data);
	}

	protected function _array_key_value($array, $something = null, $multiple = false) {
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

	protected function _get_where($where, $item) {
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

	protected function _array_where($where, $array = []) {
		$results = [];
		for($i=0;$i<$this->_count($array);$i++) {
			if($item = $this->_get_where($where, $array[$i])) {
				$results[] = $item;
			}
			if(!$this->_is_empty($array[$i]['items'])) {
				$results = $this->_array_merge($results, $this->_find_item($where, $array[$i]['items']));
			}
		}
		
		return $results;
	}

	protected function _first_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($needle[$i] !== '' && $this->_pos($haystack, 0, $this->_length($needle[$i])) === $this->_string($needle[$i])) {
				return true;
			}
		}
        return false;
	}

	protected function _all() {
        return $this->_ram[$this->_valve->key];
	}

	protected function _part($bisectrix, $content, $before = true) {
        return strstr($content, $bisectrix, $before);
	}

	protected function _interface_decode($interface) {
		$interface = $this->_explode('_', $interface);

		return [
			'year'			=> $interface[0],
			'month'			=> $interface[1],
			'day'			=> $interface[2],
			'id'			=> $interface[3],
		];

	}

	protected function _get_interface_standards($encoded, $contents, $callback = null) {

		if($encoded === null && $contents === null && $callback === null) {
			$editor = $this->_editor('php');
			//Open namespace
			$editor->tag('namespace')->attr('name', $this->_class_separator_fix('App\\'.$this->_get('app.name')))->enter();
			$editor->fixedSpace(-1);
			$editor->enter();
			//Open class
			$editor->tag('class')->attr('name', $this->_get('route.controller').'_controller')->enter();
				$editor->tag('function')->attr('name', $this->_get('route.method'));
					$editor->attr('visibility', 'public');
					$editor->attr('is_static', 0);
					$editor->attr('arguments', []);
					$editor->attr('body', 'return null;');
				$editor->tag();
			$editor->enter();
			//close class
			$editor->tag();
			//close namespace
			$editor->tag();
		}

		$time = $this->_get_time();

		if($contents !== null) {
			if(!$this->_is_string($contents) && $callback) {

				if($this->_is_array($contents)) {
					$contents = $this->_interface($contents);
				}

				if($this->_is_object($contents)) {
					if($contents instanceof Manager) {
						// your security code here Manager->key
						if($encoded == null) {
							return $this->_bind($callback, $contents)($contents->status, $contents->headers, $contents->body, $contents->protocol);
						}
					} else {
						$this->_fatal('interface', 'Unknown interface provider');
					}
				} else {
					$this->_fatal('argument', '2 must be a type of object ( '.$this->_get_type($contents).' ) given');
				}
			}
		}

		if($encoded !== null || $contents !== null) {
			if($contents !== null) {
				if(!$this->_is_string($encoded)) {
					$this->_fatal('argument', '1 must be a type of string ( '.$this->_get_type($encoded).' ) given');
				}
				if(!$this->_is_array($contents)) {
					$this->_fatal('argument', '2 must be a type of array ( '.$this->_get_type($contents).' ) given');
				}
			} else {
				if(!$this->_is_array($encoded)) {
					$this->_fatal('argument', '1 must be a type of array ( '.$this->_get_type($encoded).' ) given');
				} else {
					$contents = $encoded;
					$encoded = $time['year'].':'.$time['month'].':'.$time['day'];
				}
			}
		} else {
			$encoded = $time['year'].':'.$time['month'].':'.$time['day'];
			$contents = [];
		}

		return [
			'time' => $time,
			'encoded' => $encoded,
			'contents' => $contents
		];
	}

	public function _interface($decoded = null, $contents = null, $callback = null) {
		$interface = $this->_get_interface_standards($decoded, $contents, $callback);

		if($this->_is_string($interface)) {
			
		}

		$encoded = $this->_replace(':', '_', $interface['encoded']);

		// Bisectrix ---------------------------------------------------------------

		$data = $this->_explode('_', $encoded);

		$valve = 0;
		$action = 0;
		$actions_interface_allowed = ['get', 'save', 'delete', 'update', 'where', 'find', 'select', 'all'];

		if($this->_array_key($data[0], $actions_interface_allowed)) {
			$valve++;
			if($data[0] == 'save') {
				$action = 1;
			}
			elseif($data[0] == 'delete') {
				$action = 2;
			}
			elseif($data[0] == 'update') {
				$action = 3;
			}
			elseif($data[0] == 'where') {
				$action = 4;
			}
			elseif($data[0] == 'find') {
				$action = 5;
			}
		}

		$unique_display_id = $this->_unique_numbers(1);

		// Brosta decoded as key string
		$brosta_decoded_as_key_string = $encoded.'_'.$unique_display_id;

		// Brosta decoded as array list
		$brosta_decoded_as_array_list = $this->_explode('_', $brosta_decoded_as_key_string);

		$init = $this->_instance($brosta_decoded_as_key_string);

		$this->_ram[$brosta_decoded_as_key_string] = $interface['contents'];

        return $init;
	}

	protected function _documented($data = null) {
		$vars = [];
		if($data) {
			if($data instanceof Manager) {
				$vars['document'] = $data;
			} else {
				$vars['document'] = $this->_interface($data);
			}
		} else {
			$vars['document'] = $this->_interface($this->_get_default_static());
		}
		return $vars;
	}

	protected function _include($file, $data = null) {
		$file = $this->_template_path($file.'.'.$this->_get('flammable.code_lng_ext'));

		$vars = $this->_documented($data);

		$mix = [];
		if($this->_ini('local')) {
			$mix = $this->_require_local($file, $vars, null);
			if($this->_is_function($mix)) {
				$mix = $mix;
			}
			if($this->_is_array($mix)) {
				$mix = $this->_interface($mix);
			}
		}
		return $mix;
	}

	public function _isset($key, $logic = null) {
		$key = $this->_base($key);
		$keys = $key['keys'];
		$base = $key['base'];

		if($logic) {
			return $logic->get($key, $value);
		}

		if($this->_array_key($base, $this->_ram[$this->_valve->key])) {
			$count = $this->_count($keys);
			if($count > 0) {
				$results = $this->_array_key_value($keys, $this->_ram[$this->_valve->key][$base], true);
				if($results === NOTHING) {
					return false;
				}
				return true;
			} else {
		   		if($this->_array_key($base, $this->_ram[$this->_valve->key])) {
		   			return true;
		   		}
		   		return false;
			}
		}
		return false;
	}

	protected function _last_in($haystack, $needle) {
		$needle = $this->_is_array($needle) ? $needle : [$needle];
		for($i=0;$i>$this->_count($needle);$i++) {
			if($this->_substr($haystack, -$this->_length($needle[$i])) === $this->_string($needle[$i])) {
				return true;
			}
		}
        return false;
	}

	protected function _config($key) {
		return $this->_get_returned_array_file($this->_config_path(), $key);
	}

	protected function _get_local_array($where) {
		$key = $this->_base($where);
		$path = $key['base'];
		$rest = $key['keys'];
		return $this->_get_returned_array_file($path, $rest);
	}

	protected function _get_returned_array_file($file, $where) {
		$fileok = 0;
		for($i=0;$i<$this->_count($where);$i++) {
			if(!$fileok) {
				if($this->_is_file($file.'.'.$this->_get('flammable.code_lng_ext'))) {
					$fileok = 1;
					$data = $this->_require_local($file.'.'.$this->_get('flammable.code_lng_ext'), null, null);
				}
				$file.=$this->_dirsep().$where[$i];
			} else {
				$data = $data[$where[$i]];
			}
		}
		return $data;
	}

	protected function _set($key, $value) {
		$key = $this->_base($key);
		$keys = $key['keys'];
		$base = $key['base'];

		$count = $this->_count($keys);
		if(!$this->_array_key($base, $this->_ram[$this->_valve->key])) {
			$this->_ram[$this->_valve->key][$base] = [];
		}
		if($count > 0) {
			$keys[$count] = $value;
			$data = $this->_array_dimensional($keys);
			$this->_ram[$this->_valve->key][$base] = $this->_array_replace($this->_ram[$this->_valve->key][$base], $data, true);
		} else {
			$this->_ram[$this->_valve->key][$base] = $value;
		}
		return $value;
	}

	protected function _get($key, $default = null) {

		$key = $this->_base($key);
		$keys = $key['keys'];
		$base = $key['base'];

		if($this->_array_key($base, $this->_ram[$this->_valve->key])) {
			if($this->_count($keys) > 0) {
				$results = $this->_array_key_value($keys, $this->_ram[$this->_valve->key][$base], true);
			} else {
		   		$results = $this->_ram[$this->_valve->key][$base];
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

	protected function _delete($key) {
		$key = $this->_base($key);
		$keys = $key['keys'];
		$base = $key['base'];

		$count = $this->_count($keys);
		if($count > 0) {
			if($count == 1) {
				unset($this->_ram[$this->_valve->key][$base][$keys[0]]);
			} else {
				$this->_ram[$this->_valve->key][$base] = $this->_unset($this->_array_dimensional($keys), $this->_ram[$this->_valve->key][$base]);
			}
		} else {
		   	unset($this->_ram[$this->_valve->key][$base]);
		}
	}

	protected function _push($key, $value) {
		$key = $this->_base($key);
		$keys = $key['keys'];
		$base = $key['base'];
		$count = $this->_count($keys);
		if($count > 0) {
			$data = $this->_array_key_value($keys, $this->_ram[$this->_valve->key][$base], true);
			$data[] = $value;
			$value = $data;
			$keys[$count] = $value;
			$data = $this->_array_dimensional($keys);
			$this->_ram[$this->_valve->key][$base] = $this->_array_replace($this->_ram[$this->_valve->key][$base], $data, true);
		} else {
			$this->_ram[$this->_valve->key][$base][] = $value;
		}
		return $value;
	}

	public function _redirect($url) {
		$this->_header("Location: ".$url);
		$this->_exit();
	}

	protected function _database($file) {
		$this->_make_file_force($this->_database_path('', $something->get('token')), $something->brosta_encode());
	}

	protected function _snippet($file, $data = []) {
		if($this->_count($this->_path_to_array($file)) == 1) {
			$file.='/default';
		}
		$this->_require('snippets/'.$file.'.css');
		$this->_require('snippets/'.$file.'.js');
		$this->_include($this->_common_path('snippets/'.$file), $data);
	}

	protected function _args_to_string_vars($array, $type) {
		$res = '';
		for($i=0;$i<$this->_count($array);$i++) {
			if($res) {
				$res.=', ';
			}
			if($type == 'javascript') {
				$res.=$array[$i]['name'];
			}
			elseif($type == 'php') {
				$res.='$'.$array[$i]['name'];
			}
		}
		return $res;
	}

	protected function _token_can_used($id) {
		if(!$this->_file($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'))) {
			$this->_make_file_force($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'), '');
			return 1;
		} else {
			$tokens = $this->_file_get_contents($this->_storage('interframework/tokens/'.$this->_get('static.tokens').'.txt'));
			$tokens = $this->_explode_lines($tokens);
			return $this->_in_array($id, $tokens) ? 0 : 1;
		}
	}

	protected function _cryptchr($chr) {
		$array = [
			'numbers' => [0,1,2,3,4,5,6,7,8,9],
			'wordsupper' => ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
			'wordslower' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
		];
		$chr = $this->_explode_colon($chr);
		$results = [];
		for($i=0;$i<$this->_count($chr);$i++) {
			$results = $this->_array_merge($results, $array[$chr[$i]]);
		}
		return $results;
	}

	protected function _get_token() {

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

		$token = $this->_string($this->_generate_unique_id($options));

		if($this->_token_can_used($token)) {
			if($this->_file($token)) {
				
			}
			$contents = $token.$this->_new_line().$this->_file_get_contents($this->_storage('interframework/tokens/'.$token.'.txt'));
			$this->_fwrite($this->_storage('interframework/tokens/'.$token.'.txt'), $contents);
			$this->_set('static.token', $token);
		} else {
			return $this->_get_token();
		}

		return $token;
	}

	public function _unique_wordslower($length) {
		return $this->_generate_unique_id([
			'crypt' => $this->_cryptchr('wordslower'),
			'length' => $length
		]);
	}

	protected function _unique_wordsupper($length) {
		return $this->_generate_unique_id([
			'crypt' => $this->_cryptchr('wordsupper'),
			'length' => $length
		]);
	}

	protected function _unique_numbers($length) {
		return $this->_generate_unique_id([
			'crypt' => $this->_cryptchr('numbers'),
			'length' => $length
		]);
	}

	protected function _generate_unique_id($options, $ci = 0) {

		if(!$this->_is_array($options)) {
			$length = $options;
			$options = [
				'length' => $length,
			];
		}

		if(!$this->_isset('static.unique_ids')) {
			$this->_set('static.unique_ids', []);
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
				return $this->_generate_unique_id($options, $ci);
			}
			if($this->_in_array($options['id'], $this->_get('static.unique_ids'))) {
				return $this->_generate_unique_id($options, 0);
			}
			// FINISH
			$this->_push('static.unique_ids', $options['id']);
			if($this->_array_key('str', $options)) {
				$options['id'] = $options['str'].$options['id'];
			}
			return $options['id'];
		}
	}

	protected function _acceptable($value) {
		return $this->_in_array($value, ['yes', 'on', '1', 1, true, 'true'], true) ? true : false;
	}

	protected function _add_class($data = null) {
		if(!$data) {
			return $this;
		}
		$this->_set('tag.attr.class', $this->_get('tag.attr.class') ? $this->_set('tag.attr.class', $this->_get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	protected function _string($str) {
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

	protected function _array_to_string($object) {
		return $this->_export([
			'value' => $object,
			'quote' => "'",
			'type' => 'array',
			'escape_key' => 'body'
		]);
	}

	protected function _append($data) {
		return $this->_append_after_tag($data);
	}

	protected function _append_after_tag($data = null) {
		$this->_push('tag.append_after_tag', $data);
		return $this;
	}

	protected function _append_after_text($data = null) {
		$this->_push('tag.append_after_text', $data);
		return $this;
	}

	protected function _append_before_tag($data = null) {
		$this->_push('tag.append_before_tag', $data);
		return $this;
	}

	protected function _append_before_text($data = null) {
		$this->_push('tag.append_before_text', $data);
		return $this;
	}

	protected function _array_replace($defaults, $replaces, $recursive = false) {
		foreach($replaces as $key => $value) {
			$key = $this->_lower($key);
			if(!$this->_array_key($key, $defaults)) {
				$defaults[$key] = [];
			}
			if(!$this->_is_array($defaults[$key])) {
				$defaults[$key] = [];
			}
			if($recursive) {
				if($this->_is_array($value)) {
					$value = $this->_array_replace($defaults[$key], $value, $recursive);
				}
			}
			$defaults[$key] = $value;
		}
		return $defaults;
	}

	protected function _array_merge($defaults, $replaces, $recursive = false) {
		$i = 0;
		$results = [];
		foreach($defaults as $key => $value) {
			if($this->_is_numeric($key)) {
				$key = $i;
				$i++;
			}
			$results[$key] = $value;
		}
		foreach($replaces as $key => $value) {
			if($this->_is_numeric($key)) {
				$results[$i] = $value;
				$i++;
			} else {
				if($recursive) {
					if($this->_array_key($key, $results)) {
						if($this->_is_array($results[$key]) || $this->_is_array($value)) {
							if(!$this->_is_array($results[$key])) {
								$results[$key] = [$results[$key]];
							}
							if(!$this->_is_array($value)) {
								$value = [$value];
							}
							$value = $this->_array_merge($results[$key], $value, $recursive);
						} else {
							$value = $this->_array_merge([$results[$key]], [$value], $recursive);
						}
					} else {
						if($this->_is_array($value)) {
							$value = $this->_array_merge([], $value, $recursive);
						}
					}
				}
				$results[$key] = $value;
			}
		}
		return $results;
	}

	protected function _array_dimensional($array, $count = 0, $current = 0, $first = 0) {
		if($first == 0) {
			$count = $this->_count($array);
		}
	    if($count - 1 === $current) {
	        $array = $array[$current];
	    } else {
	    	$array = [$array[$current] => $this->_array_dimensional($array, $count, $current + 1, 1)];
	    }
	    return $array;
	}

	protected function _assets_images_url($url = '') {
		return $this->_url('assets/img/'.$this->_to_urlsep($url));
	}

	protected function _attr($attr, $data = null) {
		$this->_set('tag.attr.'.$attr, $data);
		return $this;
	}

	protected function _missout($path) {
		if(isset($path[0])) {
			if($path[0] == 'admin') {
				$this->_set('static.is_'.$path[0], 1);
				unset($path[0]);
				$path = $this->_array_zero($path);
			}
		}
		return $path;
	}

	protected function _valve($key) {
		$data = $this->_base($key);
		$keys = $data['keys'];
		$base = $data['base'];
		$results = $this->_array_key_value($keys, $this->_ram[$base], true);
		if(NOTHING === $results) {
			$this->_fatal('undefined', 'index ( '.$key.' )');
		}
		return $results;
	}

	protected function _verify_url($url) {
		$url = $this->_explode($this->_urlsep(), $url);
		$url = $this->_missout($url);
		$url = $this->_implode($this->_urlsep(), $url);
		$url = $url ? $url : $this->_urlsep();
		return $url;

	}

	protected function _route($url, $controller = null, $callback = null) {
		return $this->_router($this->_verify_url($url), 'security_code_here');
	}

	protected function _router($url = null, $security = null) {

		if($url) {
			$this->_include('_common/routes/web', [
				'url' => $this->_verify_url($this->_path()),
			]);
		} else {

			$url = $this->_verify_url($this->_path());

			$this->_set('route', [
				'args' => [],
				'method' => 'index',
				'controller' => 'desktop',
			]);

			$url = $this->_explode($this->_urlsep(), $url);
			if($this->_in_array('index', $url)) {
				$this->_set('response.status', 404);
			} else {
				if(isset($url[0])) {
					$this->_set('route.controller', $url[0]);
					unset($url[0]);
					if(isset($url[1])) {
						$this->_set('route.method', $url[1]);
						unset($url[1]);
						if(isset($url[2])) {
							$this->_set('route.args', $this->_is_empty($url) ? false : $this->_array_zero($url));
						}
					}
				}
			}
		}
	}

	protected function _body_class($classes = '') {
		$classes = $this->_replace_spaces_with_one($classes);
		foreach($this->_explode(' ', $classes) as $class) {
			if(!$this->_in_array($class, $this->_get('this.bodyclass'))) {
				$this->_push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	protected function _dirsep() {
		return DIRECTORY_SEPARATOR;
	}

	protected function _cache($file, $contents = null) {
		$file = $this->_storage('interframework/cache/'.$this->_to_dirsep($file.'.html'));
    	if(!$this->_is_null($contents)) {
    		if(!$this->_is_dir($dir = $this->_dir_file($file))) {
    			$this->_mkdir($dir);
    		}
    		if($this->_file($file)) {
    			$this->_delete_file($file);
    		}

    		//print_r(debug_backtrace()[0]);
			return $this->_make_file($file, $contents);
		} else {
			if($this->_file($file)) {
				return $this->_file_get_contents($file);
			}
		}
		return '';
	}

	protected function _checked() {
		$this->_set('tag.attr.checked', 'checked');
		return $this;
	}

	protected function _selected() {
		$this->_set('tag.attr.selected', 'selected');
		return $this;
	}

	protected function _chkeep() {
		if($this->_isset('this.keep.live')) {
			return 1;
		}
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

	protected function _class($data = '') {
		$this->_set('tag.attr.class', $data);
		return $this;
	}

	protected function _class_separator_fix($class) {
		return $this->_trim($this->_replace(['/', '.'], '\\', $class), '\\');
	}

	protected function _component_exists($path) {
		return $this->_file($this->_assets_path($path));
	}

	protected function _ucfirst_bysep($sep, $string) {
		$results = '';
		$array = $this->_explode($sep, $string);
		for($i=0;$i<$this->_count($array);$i++) {
			if($results) {
				$results.=$sep;
			}
			$results.=$this->_ucfirst($array[$i]);
		}
		return $results;
	}

	protected function _get_public_path_from_host($str) {
		return $this->_remove_prefix($this->_url(), $str);
	}

	public function _view($view) {
		$this->_set('view', $view);
		return $this;
	}

	protected function _with(array $with) {
		$this->_set('with', $with);
		return $this;
	}

	protected function _conclude() {

		$this->_get_controller();

		if($this->_get('response.status', 404)) {
			$this->_set('route', [
				'args' => [],
				'method' => '404',
				'controller' => 'errors',
			]);
		}

		if($this->_ini('view')) {
			if(!$this->_isset('view')) {
				$this->_set('view', $this->_to_dirsep($this->_get('route.controller').'/'.$this->_get('route.method')));
			}
		}

		if($this->_ini('view') && $this->_get('cache.from_cache') && $this->_is_cached($this->_get('view'))) {
			$this->_set_text($this->_cache($this->_get('view')));
		} else {

			$this->_set_start_code_space_level(2);
			$this->_set_after_or_before('after');

			if($this->_ini('view')) {
				if($this->_is_manual('root_view')) {
					$this->_include($this->_get('manual.root_view'), $this->_get('with'));
				} else {
					if($this->_include_exists($this->_get('view'))) {
						$this->_include($this->_get('view'), $this->_get('with'));
					} else {
						$this->_fatal('include', 'View file ( '.$this->_get('view').' ) not exists in ( '.$this->_template_path().' )');
					}
				}
			}

			$contents = $this->_final(1);

			// ------------------------------------------

			$this->_set_after_or_before('before');
			$this->_set_start_code_space_level(0);

			$this->_require('app.css');
			$this->_require('app.js');

			if($this->_ini('view')) {
				if($this->_include_exists('_common/resources')) {
					$this->_include('_common/resources');
				} else {
					$this->_fatal('file_not_exists', $this->_common_path('resources.'.$this->_get('flammable.code_lng_ext')));
				}
			}

			$this->_set_after_or_before('after');
			$this->_body_class('cm-body with-header');

			if($this->_ini('view')) {
				$this->_resources();
			}

			$this->_set_after_or_before('before');

			if($this->_monitor($contents)) {
				$contents = $this->_final();
				if($this->_is_string($contents)) {
					$this->_set('response.text', $contents);
				} else {
					$this->_fatal('argument', 'Server info: Response must be a type of string. You are given a type [ '.$this->_get_type($contents).' ] this is not supported from your system copyrights.');
				}
			} else {
				$this->_fatal('signal', 'NO SIGNAL');
			}
		}
	}

	protected function _copy_dir($directory, $destination) {

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

	protected function _certificate() {
		if($this->_get('tag.tag') == 'untaged') {
			return 1;
		}

			if($this->_get('tag.tag', $this->_get('static.this.form_tag_name'))) {
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
		return 1;
	}

	protected function _current_type_is(string $type) {
		return $this->_get('static.doctype', $type);
	}

	protected function _doctype(string $type = null) {
		if($type === null) {
			if($this->_isset('static.doctype')) {
				return $this->_get('static.doctype');
			} else {
				return $this->_set('static.doctype', 'html');
			}
		} else {
			$this->_set('static.doctype', $type);
		}
		return $this;
	}

	protected function _default_checked($data = '') {
		$this->_set('tag.defineds.default_checked', $data);
        return $this;
	}

	protected function _default_selected($data = '') {
		$this->_set('tag.defineds.default_selected', $data);
        return $this;
	}

	protected function _default_text($data = '') {
		$this->_set('tag.defineds.default_text', $data);
        return $this;
	}

	protected function _default_value($data = '') {
		$this->_set('tag.defineds.default_value', $data);
        return $this;
	}

	protected function _delete_path($directory, $preserve = false) {
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

	protected function _dot_to_underscore($str) {
		return $this->_trim($this->_replace('.', '_', $str), '_');
	}

	protected function _underscore_to_dot($str) {
		return $this->_trim($this->_replace('_', '.', $str), '.');
	}

	protected function _dot_to_dirsep($str) {
		return $this->_trim($this->_replace('.', $this->_dirsep(), $str), $this->_dirsep());
	}

	protected function _dot_to_urlsep($str) {
		return $this->_trim($this->_replace('.', $this->_urlsep(), $str), $this->_urlsep());
	}

	protected function _echo(string $text) {
		echo($text);
	}

	protected function _escape($str) {
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

	protected function _unescape($str) {
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

	protected function _export(array $options = []) {
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
				$this->_fatal('export_type', 'unexpected export: array to string');
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
			$this->_fatal('file_type', 'unknown file type for export');
		}
	}

	public function _fatal($key, $text = null) {
		$this->_echo($this->_lng('fatal.'.$key, $text));
		$this->_exit();
	}

	public function _lng($key, $text = null) {
		$text = $text ? $text : $key;
		if(!$this->_isset('static.lng.'.$key)) {
			$this->_set('static.lng.'.$key, $this->_replace('_', ' ', $text));
		}
		return $this->_get('static.lng.'.$key);
	}


	protected function _file_append_to_top($file, $contents) {
		return $this->_fwrite($file, $contents.$this->_file_get_contents($file));
	}

	protected function _final(int $reset = null) {

		if($reset == null) {
			$reset = 0;
		}

		if($this->_has_more_opened_tags()) {
			$this->_fatal('syntax', 'You have more opened tags than you have closed.');
		}
		if($this->_has_more_closed_tags()) {
			$this->_fatal('syntax', 'You have more closed tags than you have opened.');
		}
					
		//	print_r($this->_get('this')); exit;

		$items = $this->_get_nested_items();

		if($reset) {
			$this->_reset_this();
		}

		$document = $this->_build_document($items);

		if($this->_trim($document)) {
			if($this->_ini('view')) {
				if($this->_ini('local')) {
					if($this->_get('cache.recache')) {
						$this->_cache($this->_get('view'), $document);
					}
				}
			}
		}
		return $document;
	}

	protected function _fix_type($value, $level = 0) {
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

	protected function _items_length() {
		return $this->_get('this.count');
	}

	protected function _class_get_reflection($instance) {
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
			'in_class' => 1,
			'in_namespace' => $in_namespace,
			'namespace_name' => $namespace_name,
			'namespace_name_with_class' => $namespace_name_with_class,
		];
	}

	protected function _class_get_properties($instance) {

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
			if($value instanceof Signal || $value instanceof Manager) {
				$value = NOTHING;
			}

			$results[$name] = [
				'name' => $name,
				'is_static' => $is_static,
				'visibility' => $visibility,
				'value' => $value,
			];
		}
		return $results;
	}

	protected function _class_get_methods($instance) {
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

	protected function _class_to_array($instance) {
		if($this->_is_string($instance)) {
			$instance = new $instance;
		}
		$app = $this->_class_get_reflection($instance);
		$app['properties'] = $this->_class_get_properties($instance);
		$app['methods'] = $this->_class_get_methods($instance);
		return $app;
	}

	protected function _get_control_chars($code = null, $type = 'symbol') {
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

	protected function _get_database_names(array $op = null) {
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

	protected function _get_database_names_with_tables($keyed = 0) {
		return $this->_interface($this->_get_database_names([
			'with_tables' => 1,
			'keyed' => $keyed
		]));
	}

	protected function _get_database_names_with_tables_and_data($keyed = 0) {
		return $this->_get_database_names([
			'with_tables' => 1,
			'with_tables_data' => 1,
			'keyed' => $keyed
		]);
	}

	protected function _get_database_structure(string $name) {
		$results = [];
		$array = $this->_explode_lines($this->_file_get_contents($this->_database_path($name.'/structure.txt')));
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

	protected function _get_database_table_columns($database, $table) {
		$results = [];
		$data = $this->_get_database_table_config($database, $table);
		for($i=0;$i<$this->_count($data);$i++) {
			$results[] = $data[$i]['name'];
		}
		return $results;
	}

	protected function _get_database_table_config(string $database, string $table, array $opts = []) {
		$results = [];
		$data = $this->_explode_lines($this->_get_database_table_config($database, $table));
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

	protected function _get_database_table_config_string($database, $table) {
		return $this->_file_get_contents($this->_database_path($database.'/'.$table.'/structure.txt'));
	}

	protected function _get_database_table_data($database, $tables) {
		$results = [];
		if(!$this->_is_array($tables)) {
			$tables = [$tables];
		}
		$allow = $this->_get_database_tables($database);
		for($i=0;$i<$this->_count($tables);$i++) {
			if($this->_in_array($tables[$i], $allow)) {
				$dbts = $this->_get_database_table_config($database, $tables[$i]);
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
				$this->_fatal('table', 'Table [ '.$table.' ] not exists in database [ '.$database.' ]');
			}
		}
		return $results;
	}

	protected function _get_database_table_data_all($database) {
		return $this->_get_database_table_data($database, $this->_get_database_tables($database));
	}

	protected function _get_database_table_data_string($database, $table) {
		return $this->_file_get_contents($this->_database_path($database.'/'.$table.'/data.txt'));
	}

	protected function _get_database_tables(string $database) {
		return $this->_get_database_structure($database)['tables'];
	}

	protected function _get_date_time_zone($zone) {
		return new DateTimeZone($zone);
	}

	protected function _dir_file($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

	protected function _get_exported_string($list = []) {
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

	protected function _get_items() {
		return $this->_get('this.items');
	}

	protected function _get_month($month) {
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

	protected function _get_name_last($str) {
		return basename($str);
	}

	protected function _get_nested_items() {
		return $this->_nested($this->_get_items());
	}

	protected function _get_non_alpha_numeric_characters() {
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

	protected function _get_paths($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	protected function _get_paths_only($path) {
		return glob($path.'\*', GLOB_ONLYDIR);
	}

	protected function _get_time_in_milliseconds() {
		return time();
	}

	protected function _get_type($element) {
		return gettype($element);
	}

	protected function _has_more_opened_tags() {
		return $this->_get('static.unclosed_tags') > 0;
	}

	protected function _has_more_closed_tags() {
		return $this->_get('static.unclosed_tags') < 0;
	}

	protected function _get_unknown_chars($code = null, $type = 'symbol') {
		$codes =  [
			940 => [
				'hex' => '3ac',
				'symbol' => '',
				'chars' => [],
			],
		];
		return $code == null ? $codes : $codes[$code][$type];
	}

	protected function _get_body_class() {
		return $this->_implode(' ', $this->_get('this.bodyclass'));
	}

	protected function _get_include_contents($file) {
		return $this->_file_get_contents($this->_template_path($file.'.'.$this->_get('flammable.code_lng_ext')));
	}

	protected function _get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	protected function _header($str) {
		return header($str);
	}

	protected function _http_build_query($data, $separator = '&', $prefix = '') {
		return http_build_query($data, $prefix, $separator);
	}

	protected function _include_exists($file) {
		return $this->_file($this->_template_path($file.'.'.$this->_get('flammable.code_lng_ext')));
	}

	protected function _is_double($element) {
		return is_double($element);
	}

	protected function _is_file($file) {
		return is_file($file);
	}

	protected function _is_float($element) {
		return is_float($element);
	}

	protected function _is_integer($element) {
		return is_integer($element);
	}

	protected function _is_numeric($element) {
		return is_numeric($element);
	}

	protected function _is_admin() {
		return $this->_get('static.is_admin', 1);
	}

	protected function _is_cached($file) {
		return $this->_file($this->_storage('interframework/cache/'.$this->_to_dirsep($file.'.'.$this->_get('cache.save_type'))));
	}

	protected function _is_closure($callback) {
		return is_object($callback) && ($callback instanceof Closure);
	}

	protected function _is_int($element) {
		return $this->_is_integer($element);
	}

	protected function _is_same($a, $b) {
		return $this->_get($a, $this->_get($b));
	}

	protected function _load_components($types) {
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

	public function _head_style(string $style) {
		$this->_set_after_or_before('before');
		$this->_push('this.resources.'.$this->_get('after_or_before').'.style', $style);
		$this->_set_after_or_before('after');
	}

	protected function _load_styles() {
		foreach(['before', 'after'] as $place) {
			foreach($this->_get('this.resources.'.$place.'.style') as $style) {
				$this->_tag('style')->_text($style)->_tag();
			}
		}
	}

	protected function _load_the_scripts_components() {
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

	protected function _log($msg) {
		$logs = $this->_get_time()." : ".$msg."\n";
		$logs.= $this->_file_get_contents($this->_storage('interframework/logs/log.txt'));
		$this->_fwrite($this->_storage('interframework/logs/log.txt'), $logs);
	}

	protected function _make_include($file, $contents = '') {
		if(!$this->_make_file_force($this->_template_path($file).'.'.$this->_get('flammable.code_lng_ext'), $contents)) {
			$this->_fatal('connection', 'can\'t connect.');
		}
	}

	protected function _manual($key, $value = null) {
		return $this->_set('manual.'.$key, $value === null ? 1 : $value);
	}

	protected function _is_manual($key) {
		return $this->_isset('manual.'.$key) && $this->_get('manual.'.$key);
	}

	protected function _mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->_in_array($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

	protected function _mkdir($dir, $mode = 493, $recursive = true) {
		if(!$this->_is_dir($dir)) {
			if(!mkdir($dir, $mode, $recursive)) {
				return false;
			}
		}
		return true;
	}

	protected function _mkdirs(array $array, $force = false, $path = '') {
		$path = $path ? $path.$this->_dirsep() : '';
		foreach($array as $row) {
			if($this->_mkdir($path.$row['name'], isset($row['mode']) ? $row['mode'] : 0777)) {
				if(isset($row['files']) && !empty($row['files'])) {
					foreach($row['files'] as $file) {
						$ready_file = $path.$row['name'].$this->_dirsep().$file['name'];
						$ready_contents = '';
						if(array_key_exists('contents', $file)) {
							$ready_contents = $this->_is_string($file['contents']) ? $file['contents'] : $file['contents']();
						}
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
				$this->_fatal('make_directory', 'Cannot make a directory: '.$path.$row['name']);
			}
        }
        return 1;
	}

	protected function globals($name) {
		if(!$this->_isset('globals')) {
			foreach($this->_globals('globals.'.$name) as $key => $value) {
				$this->_set('globals.'.$name, $GLOBALS[$name]);
			}
		}
		return $this->_isset('globals') ? $this->_globals('globals.'.$name) : $this->_set('globals.'.$name, $GLOBALS[$name]);
	}

	protected function _make_file($file, $contents = '', $lock = false) {
		if($this->_file($file)) {
			$this->_fatal('files', 'File ( '.$file.' ) already exists');
		}
		return $this->_fwrite($file, $contents, $lock);
	}

	protected function _make_file_force($file, $contents = '') {
		if(!$this->_file($file)) {
			$dir = $this->_dir_file($file);
	   		if(!$this->_is_dir($dir)) {
	   			$this->_mkdir($dir);
	   		}
	   		if($this->_make_file($file, $contents)) {
		   		return 1;
		   	}
	   	} else {
		   	$this->_delete_file($file);
		   	return $this->_make_file($file, $contents);
	   	}
	   	return 0;
	}

	protected function _get_fprefix() {
		return '_';
	}

	protected function _countdown($time, $key) {
		$this->_set('countdown_'.$key, $time);
	}

	protected function _chronometer($time, $key) {
		$this->_set('chronometer_'.$key, $time);
	}

	protected function _get_time() {

		$datetime = new DateTime("now", $this->_get_date_time_zone('Europe/Athens'));
		$datetime->setTimestamp($this->_get_time_in_milliseconds());

		$time 		= $datetime->format('Y/d/m-H:i:s');
		$time 		= $this->_explode('-', $datetime->format('Y/d/m-H:i:s'));

		$date 		= $time[0];
		$date 		= $this->_explode('/', $date);

		$year		= (int)$date[0];
		$month		= (int)$date[2];
		$day		= (int)$date[1];

		$time		= $time[1];
		$time		= $this->_explode(':', $time);

		$our		= $time[0];
		$minutes	= $time[1];
		$seconds	= $time[2];

		// $year,$month,$day,$our,$minutes,$seconds

		return [
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'our' => $our,
			'minutes' => $minutes,
			'seconds' => $seconds,
		];
	}

	protected function _nested(array $data = []) {
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

	protected function _new_line() {
		return "\n";
	}

	protected function _enter($nums = 1) {
		for($i=0;$i<$nums;$i++) {
			$this->_tag('untaged')->_attr('lined', 1)->_tag();
		}
		return $this;
	}

	protected function _new_tag() {
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

	protected function _get_uri_paths() {
		return $this->_get('static.url_paths');
	}

	protected function _posted() {
		$this->_set('tag.defineds.posted', 1);
        return $this;
	}

	protected function _process_text_to_ascii($string, &$offset) {
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

	protected function _text_to_ascii($text) {
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

	public function _path() {
		return $this->_get('static.request.server.request_path');
	}

	protected function _public_path($str = '') {
		return $this->_disk('public/'.$str);
	}

	protected function _database_path($str = '') {
		return $this->_storage('Interframework/Database/'.$str);
	}

	protected function _common_path($str = '') {
		return $this->_template_path('_common/'.$str);
	}

	protected function _template_path($str = '') {
		if($this->_is_admin()) {
			$str ='admin/'.$str;
		}
		return $this->_views_path($this->_get_template().'/'.$str);
	}

	protected function _assets_path($str = '') {
		return $this->_public_path('assets/'.$str);
	}

	protected function _no_disk($str) {
		if($this->_isset('static.no_disk.'.$this->_slash_to_dot($this->_to_dirsep($str)))) {
			return $this->_get('static.no_disk.'.$this->_slash_to_dot($this->_to_dirsep($str)));
		}
		return $this->_set('static.no_disk.'.$this->_slash_to_dot($this->_to_dirsep($str)), $this->_trim($this->_replace($this->_disk(), '', $this->_to_dirsep($str)), $this->_dirsep()));
	}

	protected function _app_path($str = '') {
		return $this->_disk($this->_get('flammable.paths.app').'/'.$str);
	}

	protected function _provider_path($str = '') {
		return $this->_disk($this->_get('flammable.paths.provider').'/'.$str);
	}

	protected function _config_path($str = '') {
		return $this->_disk($this->_get('flammable.paths.config').'/'.$str);
	}

	protected function _controllers_path($str = '') {
		return $this->_app_path($this->_get('flammable.paths.controllers').'/'.$str);
	}

	protected function _boot_path($str = '') {
		return $this->_disk($this->_get('flammable.paths.boot').'/'.$str);
	}

	protected function _views_path($str = '') {
		return $this->_disk($this->_get('flammable.paths.views').'/'.$str);
	}

	protected function _require_text_script($script) {
		$this->_push('this.resources.'.$this->_get('after_or_before').'.scripts', $script);
		return $this;
	}

	protected function _resources() {
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

	protected function _ascii_to_text($contents, $callback = null) {

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

	protected function _character($output) {
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

	protected function _get_ascii_options($input, $callback) {
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

	protected function _ascii_process($output) {
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

	protected function _parse($output) {
		if($output->isString) {
			$m = '_gg_'.$output->type.'_string';
		} else {
			$m = '_gg_'.$output->type;
		}
		return $this->$m($output);
	}

	protected function _gg_alpha($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_alpha_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_control($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_control_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_number($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_number_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_symbol($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _gg_symbol_string($output) {
		$output->contents.=$output->a_o;
		return $output;
	}

	protected function _bind($callback, $obj) {
		return Closure::bind($callback, $obj);
	}

	protected function _set_after_or_before($switch) {
		$this->_set('after_or_before', $switch);
	}

	protected function _set_start_code_space_level($level) {
		return $this->_set('this.start_code_space_level', $level);
	}

	protected function _get_start_code_space_level() {
		return $this->_get('this.start_code_space_level');
	}

	protected function _my_space($num) {
		$this->_set('tag.my_space', $num);
		return $this;
	}

	protected function _fixed_space($num) {
		$this->_set('static.fixed_space', $num);
		return $this;
	}

	protected function _set_text($text) {
		$this->_set('text', $text);
	}

	protected function _set_type($type, $value) {
		switch($type) {
			case'int':
				$value = (int)$value;
			break;
			case'string':
				$value = $this->_string($value);
			break;
		}
		return $value;
	}

	protected function _settings($key) {
		return $this->_get('settings.'.$key);
	}

	protected function _slash_and_dot_to_dirsep($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_dirsep(), $str), $this->_dirsep());
	}

	protected function _slash_and_dot_to_dash($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], '_', $str), $this->_urlsep());
	}

	protected function _slash_and_dot_to_space($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], ' ', $str), $this->_urlsep());
	}

	protected function _slash_and_dot_to_urlsep($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_urlsep(), $str), $this->_urlsep());
	}

	protected function _slash_to_dot($str) {
		return $this->_trim($this->_replace(['/', '\\'], '.', $str), '.');
	}

	protected function _space($number) {
		return $this->_get_spaces_by_level($number, " ");
	}

	protected function _space_like_tab($number) {
		return $this->_get_spaces_by_level($number, "	");
	}

	protected function _space_to_dash($str) {
		return $this->_replace(' ', '-', $str);
	}

	protected function _root(string $path = '') {
		if($this->_isset('static.root')) {
			$root = $this->_get('static.root');
		} else {
			$root = $this->_set('static.root', $this->_rtrim($path, $this->_dirsep()));
		}
		return $root;
	}

	protected function _disk(string $path = '') {
		$root = $this->_root();
		if($path) {
			$root = $root.$this->_dirsep().$this->_to_dirsep($path);
		}
		return $this->_lower($root);
	}

	protected function _storage($str = '') {
		return $this->_disk('storage/'.$str);
	}

	protected function _storage_copy($source, $destination) {
		return $this->_copy_dir(
			$this->_storage($source), $destination
		);
	}

	protected function _str_trans($one, $two) {
		return strtr($one, $two);
	}

	protected function _string_length($str) {
		return strlen($str);
	}

	protected function _style($data) {
		$this->_set('tag.attr.style', $data);
		return $this;
	}

	protected function _style_to_file($style, $class = null) {
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
			if(!$this->_contains_in($this->_file_get_contents($this->_assets_path($file_name)), $contents)) {
				$this->_file_append_to_top($this->_assets_path($file_name), $contents);
			}
		}
		return $contents;
	}

	protected function _syntax_error($error, $line = 0) {
		$this->_fatal('syntax', $error.' on line '.$line);
	}

	protected function _tab_space($number) {
		return $this->_get_spaces_by_level($number, "\t");
	}

	public function _tag($tag = null) {

		// -----------------------------------------------------------------------------

		if($this->_get('this.tag_is_opened')) {
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
				$this->_set('this.tag_is_opened', 0);
			} else {
				$this->_fatal('security', 'Security error: Certificate has fail to process your request.');
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

			$this->_set('this.tag_is_opened', 1);
			$this->_set('tag.tag', $this->_remove_spaces($tag));
			$this->_set('tag.doctype', $this->_doctype());
		}
		return $this;
	}

	protected function _text($text = null, $lined = 0) {
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

	public function _title(string $title) {
		return $this->_set('title', $title);
	}

	protected function _subtitle(string $subtitle) {
		return $this->_set('subtitle', $subtitle);
	}

	protected function _get_title() {
		return $this->_get('title');
	}

	public function _allowed_execute_lng_codes() {
		return $this->_get('app.allowed_execute_lng_codes');
	}

	public function _get_standard_css() {
		return $this->_get('display.standard_style_css');
	}

	public function _get_standard_js() {
		return $this->_get('display.standard_js');
	}

	protected function _base($key, $way = '.') {
		if($this->_first_in($key, $way) || $this->_last_in($key, $way) || $this->_contains_in($key, $way.$way)) {
	    	$this->_fatal('collection', 'FATAL ERROR: WRONG COLLECTION KEY SKELETON FOR [ '.$key.' ]');
	   	}
    	$key = $this->_lower($key);
    	$key = $this->_explode($way, $key);
		$data_key = $key[0]; unset($key[0]);
		$key =  $this->_array_zero($key);
    	return ['keys' => $key, 'base' => $data_key];
	}

	protected function _base_array_file($key, $way = '.') {
		$structure = $this->_base($key, $way);
		if($this->_count($structure['keys']) == 1) {
			$structure['file'] = $structure['keys'][0];
			$structure['keys'] = null;
		} else {
			$target = $this->_count($structure['keys']) - 1;
			$structure['file'] = $structure['keys'][$target];
			unset($structure['keys'][$target]);
		}
    	return [
    		'dirname' => $structure['base'],
    		'subdirectory' => $structure['keys'],
    		'filename' => $structure['file']
    	];
	}

	protected function _to_dirsep($str) {
		return $this->_trim($this->_replace([$this->_urlsep(), $this->_dirsep()], $this->_dirsep(), $str), $this->_dirsep());
	}

	protected function _to_urlsep($str = '') {
		return $this->_trim($this->_replace([$this->_urlsep(), $this->_dirsep()], $this->_urlsep(), $str), $this->_urlsep());
	}

	protected function _underscore_to_upercase($name) {
		$names = $this->_trim($name, '_');
	   	$names = $this->_explode('_', $name);
	   	$newName = '';
	   	foreach($names as $name) {
	   		$newName.=$this->_ucfirst($name);
	   	}
	   	return $newName;
	}

	protected function _ucfirst($str) {
		return ucfirst($str);
	}

	protected function _upper_to_underscore($string) {
		return $this->_lower($this->_preg_replace('/(.)([A-Z])/', '$1_$2', $string));
	}

	protected function _array_keys_lower(array $array) {
		$results = [];
		foreach($array as $key => $value) {
			$results[$this->_lower($key)] = $value;
		};
		return $results;
	}

    protected function _snake($str) {
		$str = $this->_preg_replace('/\s+/u', '', $this->_ucwords($str));
		$str = $this->_lower($this->_preg_replace('/(.)(?=[A-Z])/u', '$1_', $str));
        return $str;
    }

    protected function _normilize_request($array) {
		$request = [];
    	$methods = ['get', 'post', 'files', 'cookie', 'server'];
    	for($i=0;$i<$this->_count($methods);$i++) {
    		$request[$methods[$i]] = [];
			foreach($array[$methods[$i]] as $key => $value) {
				$request[$methods[$i]][$this->_lower($key)] = $value;
			};
    	}
		return $request;
    }

	protected function _request($globals) {

		$globals = $this->_normilize_request($globals);

		$uri = $this->_urldecode(
		    parse_url($globals['server']['request_uri'], PHP_URL_PATH)
		);

		//$uri = $this->_substr($uri, 13);

		if(!$uri) {
			$uri = '/';
		}

		$_SERVER['REQUEST_URI'] = $uri;


		if(!$this->_is_empty($globals['server'])) {

			if($this->_array_key('request_uri', $globals['server'])) {
				$globals['server']['request_uri'] = $this->_trim($globals['server']['request_uri']);

				if($this->_substr($globals['server']['request_uri'], 0, 1) == $this->_urlsep()) {
					if($globals['server']['request_uri'] !== $this->_urlsep()) {
						$parsed = $this->_parse_url($globals['server']['request_uri']);
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

					$globals['server']['request_path'] = $this->_to_urlsep($parsed['path']);
			        $globals['server']['query_string'] = $query_string;
			        $globals['server']['request_uri'] = $parsed['path'];
					if($query_string) {
						$globals['server']['request_uri'].='?'.$query_string;
					}
				} else {
					$this->_fatal('request', 'Fattal error: Incorrect [ REQUEST_URI ] must begin with [ '.$this->_urlsep().' ]');
				}
			} else {
				$this->_fatal('request', 'Fattal error: [ REQUEST_URI ] is missing.');
			}
		} else {
			$this->_fatal('request', 'Fattal error: Your request does not have the necessary information');
		}

		$this->_set('static.request', $globals);
		$this->_delete('static.url_paths');
		$this->_set('static.url_paths', $this->_path_to_array($this->_path(), $this->_urlsep()));

	}

	public function _method($m = null) {
		$method = $this->_lower($this->_get('static.request.server.request_method'));
		return $m === null ? $method : $method == $m;
	}

	protected function _isajax() {
		return $this->_get('static.request.server.http_x_requested_with', 'XMLHttpRequest');
	}

	protected function _is_secure() {
		return $this->_get('static.request.server.https', 'on');
	}

	protected function _scheme() {
		return $this->_is_secure() ? 'https' : 'http';
	}

	protected function _download($link) {
		
	}

	protected function _host() {
		return $this->_get('static.request.server.http_host');
	}

	public function _url($extend = '', $args = [], $replace = []) {
		$args = $this->_array_replace($args, $replace);
		$url = $this->_scheme();
		$url.= '://'.$this->_host();
		$url.= $extend ? $this->_urlsep().$this->_to_urlsep($extend) : '';
    	$url.= !$this->_is_empty($args) ? '?'.$this->_http_build_query($args) : '';
		return $url;
	}

	protected function _is_gp($key) {
		if($this->_isset('static.request.post.'.$key)) {
			return 1;
		}
		return $this->_isset('static.request.get.'.$key);
    }

	public function _gp($key) {
		if($this->_isset('static.request.post.'.$key)) {
			return $this->_get('static.request.post.'.$key);
		}
		return $this->_get('static.request.get.'.$key);
    }

	protected function _is_g($key) {
		return $this->_isset('static.request.get.'.$key);
    }

	protected function _g($key) {
		return $this->_get('static.request.get.'.$key);
    }

	protected function _is_p($key) {
		return $this->_isset('static.request.post.'.$key);
    }

	protected function _p($key) {
		return $this->_get('static.request.post.'.$key);
    }

	protected function _urlsep() {
		return '/';
	}

	protected function _array_from_string($case, string $str) {
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

	protected function _change_prefix(string $from, string $to, string $str) {
		$len = $this->_length($from);
		if($this->_substr($str, 0, $len) == $from) {
			$str = $this->_substr($str, $len);
		}
		return $to.$str;
	}

	protected function _remove_prefix(string $prefix, string $str) {
		$len = $this->_length($prefix);
		if($this->_substr($str, 0, $len) == $prefix) {
			$str = $this->_substr($str, $len);
		}
		return $str;
	}

	protected function _implode_lines($array) {
		return $this->_implode($this->_new_line(), $array);
	}

	protected function _return($return) {
		return $return;
	}

    protected function _ucwords($str) {
        return ucwords($str);
    }

	protected function _require_once($file) {
		$document = $this->_instance();
		return require_once($file);
	}

	protected function _url_coordinates($url, $data, $options) {
		return $this->_interface([
			'url' => $url ? $url : null,
			'data' => $data ? $data : null,
			'options' => $options ? $options : null
		]);
	}

	protected function _require_local($url, $data) {
		//$coordinates = $this->_url_coordinates($url, $data, $options);
		if(!$this->_is_empty($data)) {
			extract($data, EXTR_SKIP);
		}
		if($this->_substr($url, -18, $this->_length($url)) == 'matrix') {
			return require($this->_usb('matrix.'.$this->_get('flammable.code_lng_ext'))); // stop everithing and run asap the code
		} else {
			return require($url);
		}
	}

	protected function _usb($file) {
		return 'C:\brosta\\'.$this->_to_dirsep($file);
	}

	protected function _pos($haystack, $needle) {
		return mb_strpos($haystack, $needle);
	}

	protected function _preg_replace($regex, $expresion, $string) {
		return preg_replace($regex, $expresion, $string);
	}

	protected function _remove_spaces(string $str) {
		return preg_replace('/\s+/', '', $str);
	}

	protected function _replace_spaces_with_one($str) {
		return preg_replace('/\s+/', ' ', $str);
	}

	protected function _replace($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}

	protected function _fwrite($file, $contents, $lock = false) {
		return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
	}

	protected function _substr(string $string, $start = 0, $length = null) {
		if(is_null($length)) {
			return mb_substr($string, $start);
		} else {
			return mb_substr($string, $start, $length);
		}
	}

	protected function _is_array($obj) {
		return is_array($obj) ? 1 : 0;
	}

	protected function _parse_url($str) {
		return parse_url($str);
	}

	protected function _urldecode($str) {
		return urldecode($str);
	}

	protected function _is_dir($obj) {
		return is_dir($obj);
	}

	protected function _is_empty($obj) {
		return empty($obj);
	}

	protected function _array_key($key, $array) {
		return array_key_exists($key, $array);
	}

	protected function _file_get_contents($file, $lock = false) {
		return file_get_contents($file, $lock);
	}

	protected function _exit() {
		exit;
	}

	protected function _unlink($file) {
		return @unlink($file);
	}

	public function _count($array, $out = true) {
		if(is_string($array)) {
			if($out === true) {
				return count($this->_get($array));
			} else {
				$this->_fatal('syntax', 'Syntax error');
			}
		} else {
			return count($array);
		}
	}

	protected function _basename($path) {
		return basename($path);
	}

	protected function _operating($commands) {
		return shell_exec('powershell.exe '.$commands);
	}

	protected function _array_zero($array) {
		return array_values($array);
	}

	protected function _call_method($method, $arguments = []) {
		return $this->_get($method)();
	}

	protected function _diamesolavitis($obj, $method, $arguments) {

		$this->_valve = $obj;

		$results = call_user_func_array([$this, $method], $arguments);

		$this->_valve = $this;

		if(is_object($results) && $results instanceof $this) {
			return $obj;
		}

		return $results;

	}

	protected function _implode($sep, $array) {
		return implode($sep, $array);
	}

	protected function _explode($sep, $string) {
		return explode($sep, $string);
	}

	protected function _in_array($key, $array) {
		return in_array($key, $array);
	}

	protected function _file_append($file, $contents) {
		return file_put_contents($file, $contents, FILE_APPEND);
	}

	protected function _make_dir_force($file, $mode = 493, $recursive = true) {
		return @mkdir($file, $mode, $recursive);
	}

	protected function _print($str) {
		return print_r($str);
	}

	protected function _json_encode($data) {
		return json_encode($data);
	}

	protected function _json_decode($data, $as_array) {
		return json_decode($data, $as_array);
	}

	protected function _copy_file($path, $target) {
		return copy($path, $target);
	}

	public function _length($str) {
		return mb_strlen($str);
	}

	public function _is_object($element) {
		return is_object($element);
	}

	public function _is_null($element) {
		return is_null($element);
	}

	public function _is_string($element) {
		return is_string($element);
	}

	public function _lower($str) {
		return strtolower($str);
	}

	public function _upper($str) {
		return strtoupper($str);
	}

	public function _file_extention($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

	public function _file($file) {
		return file_exists($file);
	}

	public function _trim($str, $mask = null) {
		if(is_null($mask)) {
			return trim($str);
		} else {
			return trim($str, $mask);
		}
	}

	public function _rtrim($source, $sym = null) {
		if(is_null($sym)) {
			return rtrim($source);
		} else {
			return rtrim($source, $sym);
		}
	}

	public function _css_fix($value) {
		return $this->_trim($value, ';').';';
	}

	public function _ltrim($source, $sym = null) {
		if(is_null($sym)) {
			return ltrim($source);
		} else {
			return ltrim($source, $sym);
		}
	}

	public function _instanceof($object, $name) {
		return $object instanceof $name;
	}

	protected function _build_attributes($attrs, $tag) {
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
					$value = $this->_css_fix($value);
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

	protected function _build_document($data, $level = 0) {
		$contents = '';
		for($i=0;$i<$this->_count($data);$i++) {
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

			$fn = $this->_include('_common/builders/code/'.$item['doctype']);

			$item = $fn($item, $level);
			//$item = $this->{$this->_get_fprefix().'code_'.$item['doctype']}($item, $level);
			if(!$this->_is_empty($item['items'])) {
				$item['nested'] = $this->_build_document($item['items'], $item['level']);
			}
			$contents.=$this->_get_builded_text($item);
		}
		return $contents;
	}

	protected function _get_builded_text($item) {
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

	protected function _get_alpha_from_lower_to_upper($code = null, $type = 'symbol') {
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

	protected function _get_alpha_from_upper_to_lower($code = null, $type = 'symbol') {
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

	protected function _get_alpha_lower($code = null, $type = 'symbol') {
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

	protected function _get_alpha_upper($code = null, $type = 'symbol') {
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

	protected function _get_numbers($code = null, $type = 'symbol') {
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

	protected function _get_symbol_chars($code = null, $type = 'symbol') {
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

	public function _add_function($key, $body) {
		$this->_set('static.functions.'.$key, $body);
	}

	public function _get_function($key) {
		if($this->_function_exists($key)) {
			return $this->_get('static.functions.'.$key);
		} else {
			return $this->_fatal('function_not_exists', $key);
		}
	}

	protected function _fixed_bridge_method($method) {
		return $this->_get_fprefix().$this->_remove_prefix($this->_get_fprefix(), $this->_upper_to_underscore($method));
	}

	protected function _mass() {
		if($this->_isset('mass')) {
			//
			
		}
		return 1;
	}

	protected function _opi($x) {
		return $x;
	}

	protected function _curtain($x) {
		return $this->_is_array($x) ? $this->_opi($x) : $this->_fatal('i_cant_make_curtain', 'My instance need to have a name, ( Manager )');
	}

	protected function _require($x, $position = 'require') {
		if(!$this->_is_string($x)) {
			return $this->_brosta_encode($this->_curtain($x));
		} else {
			$url = $this->_assets_url($x);
			if($this->_file($this->_get_public_path_from_host($this->_assets_path($x)))) {
				$ext = $this->_lower($this->_file_extention($url));
				if(!$this->_isset('settings.'.$ext.'_'.$position)) {
					if(!$this->_in_array($url, $this->_get('this.resources.after.'.$ext.'_'.$position)) && !$this->_in_array($url, $this->_get('this.resources.before.'.$ext.'_'.$position))) {
						$this->_push('this.resources.'.$this->_get('after_or_before').'.'.$ext.'_'.$position, $url);
					}
				}
			} else {
				
				$this->_fatal('require', 'file not exists : '.$url);
			}
		}
	}

	public function _get_flammable() {
		return [
			'local' => 1,
			'base_url' => 'http://brosta-interframework.com',
			'code_lng_ext' => 'php',
			'vars' => [
				'ini_presets' => 'ini.presets',
			],
			'paths' => [
				'app' => 'app',
				'boot' => 'boot',
				'views' => 'views',
				'config' => 'config',
				'provider' => 'provider',
				'controllers' => 'controllers',
			]
		];
	}

	public function _interframework($callback) {
		return function($output = [], $input = []) use ($callback) {
			$data = $this->_array_replace($output, $input, true);
			return $callback($data);
		};
	}

	public function _find_anywhere($options) {
		if($options['local'] == 1) {
			return $options;
		} else {
			return $this->_file_get_contents($options['base_url']);
		}
	}

	protected function _instance($key = 'public') {
			// -------------------------------------------------------------------------------
		if(isset($this->_ram)) {
			if($this->_ram === 1) {
				if(!$this->_is_object($key)) {
					
				} else {
					$this->_ram = [];
					$this->_valve = new Manager('public');
					return $this->_ram['public']['flammable'] = $this->_interframework(function($data) {
						return $this->_find_anywhere($data);
					})($this->_get_flammable(), []);
				}
			}
			return new Manager($key);
		}
	}

	public function _function_exists($name, $object = null) {
		if($name == '_start') {
			return $this->_require($this->_instance($object));
		}
		return method_exists($this, $name);
	}

	protected function _prepare($x) {
		$this->_ram = 1;
	}

	public function _pe($data) {
		$this->_print($data);
		$this->_exit();
	}

	public function _http($method, $url, $data) {
		return $this->_set('response', $this->bridge($method, $url, $data));
	}

	public function bridge($x, $method, array $arguments = []) {

		if($this->_is_string($x)) {
			if($this->_is_started()) {
				setcookie("uuid", LICENSE_ID);
				return $this->_interface(null, [
					'body'		=> $this->_get('response.text')		? $this->_get('response.text')			: '',
					'status'	=> $this->_get('response.status')	? $this->_get('response.status')		: 505,
					'headers'	=> $this->_get('response.headers')	? $this->_get('response.headers')		: [],
					'protocol'	=> $this->_get('response.protocol') ? $this->_get('response.protocol')		: '1.1',
				], $this->_include('_common/response'));
				 
			} else {
				//
			}
		} else {

			if($this->_is_object($x)) {

				//-- start method is bridge

				if($method == 'bridge') {
					// some other bridge philosophy
				} else {
					$method = $this->_fixed_bridge_method($method);
					if($method == '_start') {
						$this->_prepare($x);
					}
				}

				//-- end start method is bridge

				if($this->_function_exists($method, $x)) {
					return $this->_diamesolavitis($x, $method, $arguments);
				} else {
					return $x->manager('functions.'.$method, $arguments);
				}
			}
		}

	}

}

?>
