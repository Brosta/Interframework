<?php 


use brosta\interframework\manager;
use brosta\interframework\signal;
$brosta = null;

if(!$brosta) {
	$brosta = [];
}

if(!function_exists('Brosta')) {
	function Brosta($complex = '', $context = '') {
		global $brosta;
		if(is_object($complex)) {
			if($complex instanceof Closure) {
				if($brosta) {
					if(is_array($brosta)) {
						if(isset($brosta['manage'])) {
							return $complex($brosta['manage'], $context);
						} else {
							return $complex($brosta['manage'] = _premonitor($brosta, $context), $context);
						}
					} else {
						if(isset($brosta->manage)) {
							return $complex($brosta->manage, $context);
						} else {
							return $complex($brosta->premonitor($brosta->complex, $brosta->context), $context);
						}
					}
				} else {
					$brosta = [
						'manage' => _premonitor($context ? $context : [])
					];

					return $complex($brosta['manage']);
				}
			} else {
				// Not closure, probably instanceof manage
				if($brosta) {
					
				} else {
					
				}
			};
		}
}
}

if(!function_exists('_loader')) {
	function _loader(array $settings = []) {
	return $settings;
}
}

if(!function_exists('_premonitor')) {
	function _premonitor($seed = [], $context = '') {
		if($context != '') {
			return _premonitor(array_merge($seed, $context));
		}

		global $brosta;

		if($brosta) {
			if($brosta->ready) {
				if(isset($brosta->seed) && $brosta->seed) {
					$seed = array_merge($brosta->seed, $seed);
					$seed['ready'] = 1;
					$seed['state'] = 1;
				} else {
					$seed['ready'] = 0;
					$seed['state'] = 0;
				}
			}
		}

		if(!isset($seed['root'])) {
			$seed['root'] = rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'interframework'.DIRECTORY_SEPARATOR;
		}

		$seed = array_merge([
			'done' => array_key_exists('done', $seed)
			? (is_callable($seed['done']) ? $seed['done']($seed) : '')
			: function($settings) {
				if(!array_key_exists('ready', $settings)) {
					$settings = $settings['ini']['loader']($settings);
				}

				if(!isset($settings['get']['update']) || isset($settings['get']['update']) && $settings['get']['update'] == 0) {
					if(!class_exists('brosta\interframework\signal')) {
						if(file_exists($settings['root'].'provider'.DIRECTORY_SEPARATOR.'brosta'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'interframework'.DIRECTORY_SEPARATOR.'signal.php')) {
							require($settings['root'].'provider'.DIRECTORY_SEPARATOR.'brosta'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'interframework'.DIRECTORY_SEPARATOR.'signal.php');
							$signal = new brosta\interframework\signal($settings);
							if($digital = $signal->pirinas()) {
								return $digital->gun($settings['gun']);
							}
						}
					} else {
						$signal = new brosta\interframework\signal($settings);
						if($digital = $signal->Pirinas()) {
							return $digital->gun($settings['gun']);
						}
					}
				}

				if($settings['local']) {
					if(isset($settings['missing']) && !empty($settings['missing'])) {
						if($settings['command_line_enable']) {
							foreach($settings['missing'] as $file) {
								if(!is_dir(dirname($file))) {
									$handle = shell_exec('powershell.exe '.substr($file, 0, 2).'; mkdir '.dirname($file).';');
								}
							}
						}
					}
				}
			},
			// ---------------------------------------------------------------------------------------------------------------
			'url' => array_key_exists('url', $seed)
			? $seed['url']
			: $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'],
			// ---------------------------------------------------------------------------------------------------------------
			'path' => array_key_exists('path', $seed)
			? rtrim(realpath($seed['path']), DIRECTORY_SEPARATOR)
			: rtrim(realpath($seed['root']), DIRECTORY_SEPARATOR),
			// ---------------------------------------------------------------------------------------------------------------
			'local' => array_key_exists('local', $seed)
			? $seed['local']
			: 1,
			// ---------------------------------------------------------------------------------------------------------------
			'with_config' => array_key_exists('with_config', $seed)
			? $seed['with_config']
			: 1,
			// ---------------------------------------------------------------------------------------------------------------
			'complex' => array_key_exists('complex', $seed)
			? $seed['complex']
			: $seed['root'].'provider'.DIRECTORY_SEPARATOR.'brosta.php',
			// ---------------------------------------------------------------------------------------------------------------
			'missing' => array_key_exists('missing', $seed)
			? $seed['missing']
			: [],
			// ---------------------------------------------------------------------------------------------------------------
			'commands' => array_key_exists('commands', $seed)
			? $seed['commands']
			: '',
			// ---------------------------------------------------------------------------------------------------------------
			'enable_command_line' => array_key_exists('enable_command_line', $seed)
			? $seed['enable_command_line']
			: 0,
			// ---------------------------------------------------------------------------------------------------------------
			'state' => array_key_exists('state', $seed)
			? $seed['state']++
			: 0,
			// ---------------------------------------------------------------------------------------------------------------
			'ds' => array_key_exists('ds', $seed)
			? $seed['ds']
			: DIRECTORY_SEPARATOR,
			// ---------------------------------------------------------------------------------------------------------------
			'mode' => array_key_exists('mode', $seed)
			? $seed['mode']
			: 'brosta',
			// ---------------------------------------------------------------------------------------------------------------
			'clng' => array_key_exists('clng', $seed)
			? $seed['clng']
			: 'php',
			// ---------------------------------------------------------------------------------------------------------------
			'get' => array_key_exists('get', $seed)
			? $seed['get']
			: $_GET,
			// ---------------------------------------------------------------------------------------------------------------
			'host' => array_key_exists('host', $seed)
			? $seed['host']
			: $_SERVER['HTTP_HOST'],
			// ---------------------------------------------------------------------------------------------------------------
			'www' => array_key_exists('www', $seed)
			? $seed['www']
			: substr($seed['root'], 0, -16),
			// ---------------------------------------------------------------------------------------------------------------
			'root' => array_key_exists('root', $seed)
			? $seed['root']
			: rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR,
			// ---------------------------------------------------------------------------------------------------------------
			'document_root' => array_key_exists('document_root', $seed)
			? $seed['document_root']
			: $seed['root'],
			// ---------------------------------------------------------------------------------------------------------------
			'gun' => array_key_exists('gun', $seed)
			? $seed['gun']
			: 'client',
			// ---------------------------------------------------------------------------------------------------------------
			'philosophies' => array_key_exists('philosophies', $seed)
			? $seed['philosophies']
			: [
				'admin',
				'documentation',
				'school',
				'developers',
				'tutorials',
			],
			// ---------------------------------------------------------------------------------------------------------------
			'require' => array_key_exists('require', $seed)
			? $seed['require']
			: [
				'/provider/brosta.php',
				'/provider/brosta/src/interframework/decoder.php',
				'/provider/brosta/src/interframework/helpers.php',
				'/provider/brosta/src/interframework/validator.php',
				'/provider/brosta/src/interframework/signal.php',
			],
			// ---------------------------------------------------------------------------------------------------------------
			'ini' => array_key_exists('ini', $seed)
			? $seed['ini']
			: [
				'loader' => function(array $settings = []) {
					$default = defined('BROSTA') ? BROSTA : [
						'ds' => DIRECTORY_SEPARATOR,
						'get' => $_GET,
						'www' => rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR),
						'root' => rtrim(str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $_SERVER['DOCUMENT_ROOT']), DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'interframework'.DIRECTORY_SEPARATOR,
						'loaded' => 1,
						'install_redirect' => $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'],
						'require' => []
					];

					if(!array_key_exists('ready', $settings)) {
						$settings['ready'] = 1;
					}

					if(isset($settings)) {
						$tmp = $seed = array_merge($default, is_array($settings) ? $settings : json_decode($settings, true));
					} else {
						$tmp = $seed = $default;
					}

					for($i=0;$i<count($seed['require']);$i++) {
						if(!is_array($seed['require'][$i])) {
							$file = str_replace(['/', '\\'], $seed['ds'], $seed['require'][$i]);
							if(substr($file, 0 , 1) != $seed['ds']) {
								echo $file;
								exit;
							}
							$base = dirname($file);
							$name = basename($file);
							$seed['require'][$i] = [
								'name' => $name,
								'base' => $base,
								'file' => $file,
							];
						}

						$file = $seed['require'][$i]['file'];

						if(isset($seed['get']['update']) && $seed['get']['update'] == 1 || !file_exists($seed['www'].$file)) {
							$seed['get']['update'] = 1;

							if(!is_dir($seed['www'])) {
								mkdir($seed['www'], 0777, true);
							}

							if(!file_exists($seed['www'].$file)) {
								$www_path = dirname($seed['www'].$file);
								if(!is_dir($www_path)) {
									if(mkdir($www_path, 0777, true)) {
										foreach([
											$seed['root'],
										] as $path) {
											$path = rtrim($path, $seed['ds']);
											if(file_exists($path.$file)) {
												copy($path.$file, $seed['www'].$file);
											} else {
												echo("file not exists : ".$path.$file."\nto copy in : ".$seed['www'].$file."\n");
											}
										}
									} else {
										echo('Can not make dir : '.$www_path);
									}
								} else {
									foreach([
										$seed['root'],
									] as $path) {
										$path = rtrim($path, $seed['ds']);
										if(file_exists($path.$file)) {
											copy($path.$file, $seed['www'].$file);
										} else {
											echo("file not exists : ".$path.$file."\nto copy in : ".$seed['www'].$file."\n");
										}
									}
								}
							} else {
								foreach([
									$seed['root'],
								] as $path) {
									$path = rtrim($path, $seed['ds']);
									if(file_exists($path.$file)) {
										copy($path.$file, $seed['www'].$file);
									} else {
										file_put_contents($seed['www'].$file, file_get_contents($path.$file));
									}
								}
							}
							return header('Location:'.$seed['install_redirect']);
						} else {
							require($seed['www'].$file);
						}

					}

					if($seed['with_config'] === 1) {
						if($seed['local'] === 1) {
							$data = require($seed['complex']);
							if(is_string($data)) {
								$seed = array_merge($seed, json_decode($data, true));
							} else {
								if(is_array($data)) {
									$seed = array_merge($seed, $data);
								}
							}
							$data = require($seed['path'].$seed['ds'].'loader'.$seed['ds'].$seed['mode'].'.'.$seed['clng']);
							if(is_string($data)) {
								$seed = array_merge($seed, json_decode($data, true));
							} else {
								if(is_array($data)) {
									$seed = array_merge($seed, $data);
								}
							}
							unset($data);
						}
					}

					return $seed;
				}
			]
		], $seed ? $seed : []);

		while($seed['done']) {
			switch($seed['state']) {
				case'0':
					return $seed['done']($seed);
				break;
				case'1':
					
				break;
				case'2':
					
				break;
				case'3':
					
				break;
				case'4':
					
				break;
				case'5':
					
				break;
				default:
					
				break;
			}
		}
}
}

if(!function_exists('_interframework')) {
	function _interframework($name = null, $args = null, $digi = null) {return manager::_interframework($name, $args, $digi);}
}

if(!function_exists('_dec_to_bin')) {
	function _dec_to_bin($number) {return manager::_dec_to_bin($number);}
}

if(!function_exists('_live')) {
	function _live() {return manager::_live();}
}

if(!function_exists('_brosta_construct')) {
	function _brosta_construct($complex = '', $context = '', $digi = '') {return manager::_brosta_construct($complex, $context, $digi);}
}

if(!function_exists('_brostaset')) {
	function _brostaset($key, $value, $digi = null) {return manager::_brostaset($key, $value, $digi);}
}

if(!function_exists('_pirinas')) {
	function _pirinas() {return manager::_pirinas();}
}

if(!function_exists('_brostaget')) {
	function _brostaget($key, $digi = null) {return manager::_brostaget($key, $digi);}
}

if(!function_exists('_brostaunset')) {
	function _brostaunset($key, $inject = null) {return manager::_brostaunset($key, $inject);}
}

if(!function_exists('_call')) {
	function _call($name, $args = []) {return manager::_call($name, $args);}
}

if(!function_exists('__construct')) {
	function __construct($driving = '', $pulse = '', $digi = '') {return manager::__construct($driving, $pulse, $digi);}
}

if(!function_exists('__set')) {
	function __set($key, $val) {return manager::__set($key, $val);}
}

if(!function_exists('__get')) {
	function __get($key) {return manager::__get($key);}
}

if(!function_exists('__unset')) {
	function __unset($key) {return manager::__unset($key);}
}

if(!function_exists('__call')) {
	function __call($name, $args) {return manager::__call($name, $args);}
}

if(!function_exists('__callstatic')) {
	function __callstatic($name, $args) {return manager::__callstatic($name, $args);}
}

if(!function_exists('_else_define')) {
	function _else_define(array $data = []) {return manager::_else_define($data);}
}

if(!function_exists('_complex')) {
	function _complex($complex = null, $context = null) {return manager::_complex($complex, $context);}
}

if(!function_exists('_main')) {
	function _main($driving = null) {return manager::_main($driving);}
}

if(!function_exists('_cockpit')) {
	function _cockpit($driving = null, $pulse = null) {return manager::_cockpit($driving, $pulse);}
}

if(!function_exists('_reversal')) {
	function _reversal($reversal, $data, $value = -1) {return manager::_reversal($reversal, $data, $value);}
}

if(!function_exists('_server_get_parts')) {
	function _server_get_parts(array $server = [], $value = -1) {return manager::_server_get_parts($server, $value);}
}

if(!function_exists('_url_get_parts')) {
	function _url_get_parts(string $url = '', $value = -1) {return manager::_url_get_parts($url, $value);}
}

if(!function_exists('_build_server')) {
	function _build_server($brosta, $data) {return manager::_build_server($brosta, $data);}
}

if(!function_exists('_digital_seed')) {
	function _digital_seed($driving = null, $pulse = null) {return manager::_digital_seed($driving, $pulse);}
}

if(!function_exists('_get_preload_data')) {
	function _get_preload_data($url, $request = null) {return manager::_get_preload_data($url, $request);}
}

if(!function_exists('_prepare_disks')) {
	function _prepare_disks($atom) {return manager::_prepare_disks($atom);}
}

if(!function_exists('_interpret')) {
	function _interpret($complex = null, array $params = []) {return manager::_interpret($complex, $params);}
}

if(!function_exists('_root')) {
	function _root($path = null, $that = null) {return manager::_root($path, $that);}
}

if(!function_exists('_in_type')) {
	function _in_type($data, $type, $mm = []) {return manager::_in_type($data, $type, $mm);}
}

if(!function_exists('_in_file_type')) {
	function _in_file_type($contents, $type, $close = false) {return manager::_in_file_type($contents, $type, $close);}
}

if(!function_exists('_nextprev')) {
	function _nextprev(array $array, $sign, $need, $target, $ignore = [], $ignore_keys = [], $range = null, $results = [], $num = 1) {return manager::_nextprev($array, $sign, $need, $target, $ignore, $ignore_keys, $range, $results, $num);}
}

if(!function_exists('_code_translate')) {
	function _code_translate($from, $to, $code) {return manager::_code_translate($from, $to, $code);}
}

if(!function_exists('_use')) {
	function _use($data) {return manager::_use($data);}
}

if(!function_exists('_build_application')) {
	function _build_application($document) {return manager::_build_application($document);}
}

if(!function_exists('_programmatic')) {
	function _programmatic($str) {return manager::_programmatic($str);}
}

if(!function_exists('_disk_absolute')) {
	function _disk_absolute($str = '') {return manager::_disk_absolute($str);}
}

if(!function_exists('_build')) {
	function _build($name, $data) {return manager::_build($name, $data);}
}

if(!function_exists('_curl_get')) {
	function _curl_get($url) {return manager::_curl_get($url);}
}

if(!function_exists('_version_url')) {
	function _version_url(string $url = '', $data = []) {return manager::_version_url($url, $data);}
}

if(!function_exists('_update')) {
	function _update($name) {return manager::_update($name);}
}

if(!function_exists('_smoothly')) {
	function _smoothly($list) {return manager::_smoothly($list);}
}

if(!function_exists('_check')) {
	function _check($what) {return manager::_check($what);}
}

if(!function_exists('_keep_numbers')) {
	function _keep_numbers($str) {return manager::_keep_numbers($str);}
}

if(!function_exists('_debug')) {
	function _debug($data) {return manager::_debug($data);}
}

if(!function_exists('_strkvby')) {
	function _strkvby($array, $ssymbol, $ksymbol) {return manager::_strkvby($array, $ssymbol, $ksymbol);}
}

if(!function_exists('_build_rules')) {
	function _build_rules($rules, $symbol) {return manager::_build_rules($rules, $symbol);}
}

if(!function_exists('_validate_normalize')) {
	function _validate_normalize($fields) {return manager::_validate_normalize($fields);}
}

if(!function_exists('_bros')) {
	function _bros(string $query) {return manager::_bros($query);}
}

if(!function_exists('_return')) {
	function _return($res) {return manager::_return($res);}
}

if(!function_exists('_and_build_at_the_same_time')) {
	function _and_build_at_the_same_time(array $lngs) {return manager::_and_build_at_the_same_time($lngs);}
}

if(!function_exists('_form')) {
	function _form($name) {return manager::_form($name);}
}

if(!function_exists('_validate')) {
	function _validate($fields, $input = []) {return manager::_validate($fields, $input);}
}

if(!function_exists('_add_meta')) {
	function _add_meta($html = null) {return manager::_add_meta($html);}
}

if(!function_exists('_ready')) {
	function _ready($what = null) {return manager::_ready($what);}
}

if(!function_exists('_setready')) {
	function _setready($what) {return manager::_setready($what);}
}

if(!function_exists('_os_is')) {
	function _os_is($os) {return manager::_os_is($os);}
}

if(!function_exists('_vendor_ready')) {
	function _vendor_ready() {return manager::_vendor_ready();}
}

if(!function_exists('_set_live')) {
	function _set_live($app, $obj) {return manager::_set_live($app, $obj);}
}

if(!function_exists('_get_default_static')) {
	function _get_default_static($default = null) {		return [			'this' => [
		'type' => 'html',
		'bodyclass' => [
			'cm-body',
			'brosta-black',
			'cm-autonomous'
		],
		'resources' => [
			'before' => [
				'js_third' => [],
				'css_third' => [],
				'css_require' => [
					'/assets/app.css'
				],
				'js_require' => [
					'/assets/signal.js',
					'/assets/manager.js',
					'/assets/app.js'
				],
				'css_auto_view' => [],
				'js_auto_view' => [],
				'css_dynamic' => [],
				'js_dynamic' => [],
				'scripts' => [],
				'meta' => [],
				'style' => [],
				'html' => []
			],
			'after' => [
				'js_third' => [],
				'css_third' => [],
				'css_require' => [],
				'js_require' => [],
				'css_auto_view' => [],
				'js_auto_view' => [],
				'css_dynamic' => [],
				'js_dynamic' => [],
				'scripts' => [],
				'meta' => [],
				'style' => [],
				'html' => []
			]
		],
		'count' => 0,
		'index' => 0,
		'level' => 0,
		'space_level' => 0,
		'unclosed_tags' => 0,
		'keep' => [],
		'items' => []
	],
		];}
}

if(!function_exists('_boot_autoloaders')) {
	function _boot_autoloaders() {return manager::_boot_autoloaders();}
}

if(!function_exists('_dataset')) {
	function _dataset(array $data, $value) {return manager::_dataset($data, $value);}
}

if(!function_exists('_foreach')) {
	function _foreach(array $data, $callback, $arg = null) {return manager::_foreach($data, $callback, $arg);}
}

if(!function_exists('_for')) {
	function _for(array $data, $callback, $arg = null) {return manager::_for($data, $callback, $arg);}
}

if(!function_exists('_set_ini_presets')) {
	function _set_ini_presets(array $arr) {return manager::_set_ini_presets($arr);}
}

if(!function_exists('_ini')) {
	function _ini($k, $value = null) {return manager::_ini($k, $value);}
}

if(!function_exists('_is_started')) {
	function _is_started() {return manager::_is_started();}
}

if(!function_exists('_brosta_encode')) {
	function _brosta_encode($data) {return manager::_brosta_encode($data);}
}

if(!function_exists('_brosta_decode')) {
	function _brosta_decode($data, $as_array = false) {return manager::_brosta_decode($data, $as_array);}
}

if(!function_exists('_reset')) {
	function _reset($arr = []) {return manager::_reset($arr);}
}

if(!function_exists('_on')) {
	function _on($event, $callback) {return manager::_on($event, $callback);}
}

if(!function_exists('_get_template')) {
	function _get_template() {return manager::_get_template();}
}

if(!function_exists('_set_template')) {
	function _set_template($name) {return manager::_set_template($name);}
}

if(!function_exists('_page')) {
	function _page($data = null) {return manager::_page($data);}
}

if(!function_exists('_is_active_link')) {
	function _is_active_link($link) {return manager::_is_active_link($link);}
}

if(!function_exists('_manufacture')) {
	function _manufacture($html) {return manager::_manufacture($html);}
}

if(!function_exists('_sys_unset')) {
	function _sys_unset($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {return manager::_sys_unset($unset_key, $replaces, $results, $level, $lock, $stop, $unlock);}
}

if(!function_exists('_array_path')) {
	function _array_path($path) {return manager::_array_path($path);}
}

if(!function_exists('_is_inside_with_outside')) {
	function _is_inside_with_outside($value) {return manager::_is_inside_with_outside($value);}
}

if(!function_exists('_add_button')) {
	function _add_button($html = null) {return manager::_add_button($html);}
}

if(!function_exists('_if_is_inside_build')) {
	function _if_is_inside_build($live, $data = null) {return manager::_if_is_inside_build($live, $data);}
}

if(!function_exists('_add_form_list')) {
	function _add_form_list($html = null) {return manager::_add_form_list($html);}
}

if(!function_exists('_add_field')) {
	function _add_field($html = null) {return manager::_add_field($html);}
}

if(!function_exists('_autoload_register_namespace_prefix')) {
	function _autoload_register_namespace_prefix($name, $path) {return manager::_autoload_register_namespace_prefix($name, $path);}
}

if(!function_exists('_autoload_namespace_prefix_registered')) {
	function _autoload_namespace_prefix_registered($name) {return manager::_autoload_namespace_prefix_registered($name);}
}

if(!function_exists('_autogns')) {
	function _autogns($name) {return manager::_autogns($name);}
}

if(!function_exists('_make_controller')) {
	function _make_controller($structure) {return manager::_make_controller($structure);}
}

if(!function_exists('_get_main_menu')) {
	function _get_main_menu($menu = null) {return manager::_get_main_menu($menu);}
}

if(!function_exists('_get_databases')) {
	function _get_databases() {return manager::_get_databases();}
}

if(!function_exists('_philosophies')) {
	function _philosophies() {return manager::_philosophies();}
}

if(!function_exists('_load_controller_args')) {
	function _load_controller_args($visitor) {return manager::_load_controller_args($visitor);}
}

if(!function_exists('_load_controller')) {
	function _load_controller($name) {return manager::_load_controller($name);}
}

if(!function_exists('_is_local')) {
	function _is_local() {return manager::_is_local();}
}

if(!function_exists('_dig_storage')) {
	function _dig_storage($data) {return manager::_dig_storage($data);}
}

if(!function_exists('_controller_exists')) {
	function _controller_exists($class_name) {return manager::_controller_exists($class_name);}
}

if(!function_exists('_get_controller')) {
	function _get_controller($namespace, $controller, $visitor) {return manager::_get_controller($namespace, $controller, $visitor);}
}

if(!function_exists('_live_philosophy')) {
	function _live_philosophy($data) {return manager::_live_philosophy($data);}
}

if(!function_exists('_new')) {
	function _new($name, $constructor = null) {return manager::_new($name, $constructor);}
}

if(!function_exists('_class_loaded')) {
	function _class_loaded($data) {return manager::_class_loaded($data);}
}

if(!function_exists('_autotag')) {
	function _autotag($switch) {return manager::_autotag($switch);}
}

if(!function_exists('_document')) {
	function _document($options = null) {return manager::_document($options);}
}

if(!function_exists('_assets_url')) {
	function _assets_url(string $url = '') {return manager::_assets_url($url);}
}

if(!function_exists('_assets_img_url')) {
	function _assets_img_url($url = '') {return manager::_assets_img_url($url);}
}

if(!function_exists('_contains_in')) {
	function _contains_in($haystack, $needle) {return manager::_contains_in($haystack, $needle);}
}

if(!function_exists('_explode_dot')) {
	function _explode_dot(string $data) {return manager::_explode_dot($data);}
}

if(!function_exists('_implode_dot')) {
	function _implode_dot(array $data) {return manager::_implode_dot($data);}
}

if(!function_exists('_explode_lines')) {
	function _explode_lines(string $data) {return manager::_explode_lines($data);}
}

if(!function_exists('_explode_colon')) {
	function _explode_colon(string $data) {return manager::_explode_colon($data);}
}

if(!function_exists('_get_key_value')) {
	function _get_key_value(array $array, $something = null, $multiple = true) {return manager::_get_key_value($array, $something, $multiple);}
}

if(!function_exists('_first_in')) {
	function _first_in($haystack, $needle) {return manager::_first_in($haystack, $needle);}
}

if(!function_exists('_last_in')) {
	function _last_in($haystack, $needle) {return manager::_last_in($haystack, $needle);}
}

if(!function_exists('_all_db')) {
	function _all_db() {return manager::_all_db();}
}

if(!function_exists('_interface_decode')) {
	function _interface_decode($interface) {return manager::_interface_decode($interface);}
}

if(!function_exists('_get_interface_standards')) {
	function _get_interface_standards($encoded, $contents, $callback, $name) {return manager::_get_interface_standards($encoded, $contents, $callback, $name);}
}

if(!function_exists('_encode')) {
	function _encode($data) {return manager::_encode($data);}
}

if(!function_exists('_decode')) {
	function _decode($id) {return manager::_decode($id);}
}

if(!function_exists('_controlled')) {
	function _controlled($arr = null, $name = null) {return manager::_controlled($arr, $name);}
}

if(!function_exists('_getv_var')) {
	function _getv_var($visitor = '') {return manager::_getv_var($visitor);}
}

if(!function_exists('_documented')) {
	function _documented($data = null) {return manager::_documented($data);}
}

if(!function_exists('_include')) {
	function _include($file, $data = null, $document_false = 0) {return manager::_include($file, $data, $document_false);}
}

if(!function_exists('_require_local_documented')) {
	function _require_local_documented($file, $data = null) {return manager::_require_local_documented($file, $data);}
}

if(!function_exists('_is_correct')) {
	function _is_correct($correct, $a = 'nothing', $b = 'nothing') {return manager::_is_correct($correct, $a, $b);}
}

if(!function_exists('_get_local_array')) {
	function _get_local_array($where) {return manager::_get_local_array($where);}
}

if(!function_exists('_get_returned_array_file')) {
	function _get_returned_array_file($file, $where) {return manager::_get_returned_array_file($file, $where);}
}

if(!function_exists('_redirect')) {
	function _redirect($url) {return manager::_redirect($url);}
}

if(!function_exists('_file_to_style_class')) {
	function _file_to_style_class($str) {return manager::_file_to_style_class($str);}
}

if(!function_exists('_snippet')) {
	function _snippet($file, array $data = []) {return manager::_snippet($file, $data);}
}

if(!function_exists('_args_to_string_vars')) {
	function _args_to_string_vars($arr, $type) {return manager::_args_to_string_vars($arr, $type);}
}

if(!function_exists('_license_ok')) {
	function _license_ok($id) {return manager::_license_ok($id);}
}

if(!function_exists('_cryptchr')) {
	function _cryptchr($chr) {return manager::_cryptchr($chr);}
}

if(!function_exists('_integrate')) {
	function _integrate() {return manager::_integrate();}
}

if(!function_exists('_unique_wordslower')) {
	function _unique_wordslower($length) {return manager::_unique_wordslower($length);}
}

if(!function_exists('_unique_wordsupper')) {
	function _unique_wordsupper($length) {return manager::_unique_wordsupper($length);}
}

if(!function_exists('_unique_numbers')) {
	function _unique_numbers($length) {return manager::_unique_numbers($length);}
}

if(!function_exists('_generate_unique_id')) {
	function _generate_unique_id($options, $ci = 0) {return manager::_generate_unique_id($options, $ci);}
}

if(!function_exists('_acceptable')) {
	function _acceptable($value) {return manager::_acceptable($value);}
}

if(!function_exists('_append_class')) {
	function _append_class($data) {return manager::_append_class($data);}
}

if(!function_exists('_array_to_string')) {
	function _array_to_string($object) {return manager::_array_to_string($object);}
}

if(!function_exists('_append')) {
	function _append($data) {return manager::_append($data);}
}

if(!function_exists('_append_after_tag')) {
	function _append_after_tag($data = null) {return manager::_append_after_tag($data);}
}

if(!function_exists('_append_after_text')) {
	function _append_after_text($data = null) {return manager::_append_after_text($data);}
}

if(!function_exists('_append_before_tag')) {
	function _append_before_tag($data = null) {return manager::_append_before_tag($data);}
}

if(!function_exists('_append_before_text')) {
	function _append_before_text($data = null) {return manager::_append_before_text($data);}
}

if(!function_exists('_array_replace')) {
	function _array_replace($defaults, $replaces, $recursive = false) {return manager::_array_replace($defaults, $replaces, $recursive);}
}

if(!function_exists('_array_merge')) {
	function _array_merge($defaults, $replaces, $recursive = false) {return manager::_array_merge($defaults, $replaces, $recursive);}
}

if(!function_exists('_array_dimensional')) {
	function _array_dimensional($arr, $count = 0, $current = 0, $first = 0) {return manager::_array_dimensional($arr, $count, $current, $first);}
}

if(!function_exists('_attr')) {
	function _attr($attr, $data = '') {return manager::_attr($attr, $data);}
}

if(!function_exists('_missout')) {
	function _missout($path) {return manager::_missout($path);}
}

if(!function_exists('_verify_url')) {
	function _verify_url($url) {return manager::_verify_url($url);}
}

if(!function_exists('_get_verify_url')) {
	function _get_verify_url() {return manager::_get_verify_url();}
}

if(!function_exists('_array_begins')) {
	function _array_begins($a, $b) {return manager::_array_begins($a, $b);}
}

if(!function_exists('_router')) {
	function _router() {return manager::_router();}
}

if(!function_exists('_body_class')) {
	function _body_class($classes = '') {return manager::_body_class($classes);}
}

if(!function_exists('_dirsep')) {
	function _dirsep($str = '') {return manager::_dirsep($str);}
}

if(!function_exists('_cache')) {
	function _cache($file, $contents = null) {return manager::_cache($file, $contents);}
}

if(!function_exists('_checked')) {
	function _checked() {return manager::_checked();}
}

if(!function_exists('_selected')) {
	function _selected() {return manager::_selected();}
}

if(!function_exists('_chkeep')) {
	function _chkeep() {return manager::_chkeep();}
}

if(!function_exists('_class')) {
	function _class($data = '') {return manager::_class($data);}
}

if(!function_exists('_class_separator_fix')) {
	function _class_separator_fix($class) {return manager::_class_separator_fix($class);}
}

if(!function_exists('_component_exists')) {
	function _component_exists($path) {return manager::_component_exists($path);}
}

if(!function_exists('_upper_by')) {
	function _upper_by($sep, $string) {return manager::_upper_by($sep, $string);}
}

if(!function_exists('_class_get_methods')) {
	function _class_get_methods($hole, $options = []) {return manager::_class_get_methods($hole, $options);}
}

if(!function_exists('_get_public_path_from_host')) {
	function _get_public_path_from_host($str) {return manager::_get_public_path_from_host($str);}
}

if(!function_exists('_view')) {
	function _view($view) {return manager::_view($view);}
}

if(!function_exists('_get_view')) {
	function _get_view() {return manager::_get_view();}
}

if(!function_exists('_algo_add')) {
	function _algo_add($name, $items) {return manager::_algo_add($name, $items);}
}

if(!function_exists('_contents_top')) {
	function _contents_top($items) {return manager::_contents_top($items);}
}

if(!function_exists('_contents_left')) {
	function _contents_left($items) {return manager::_contents_left($items);}
}

if(!function_exists('_contents_middle')) {
	function _contents_middle($items) {return manager::_contents_middle($items);}
}

if(!function_exists('_contents_right')) {
	function _contents_right($items) {return manager::_contents_right($items);}
}

if(!function_exists('_contents_bottom')) {
	function _contents_bottom($items) {return manager::_contents_bottom($items);}
}

if(!function_exists('_sidebar_top')) {
	function _sidebar_top($items) {return manager::_sidebar_top($items);}
}

if(!function_exists('_sidebar_left')) {
	function _sidebar_left($items) {return manager::_sidebar_left($items);}
}

if(!function_exists('_sidebar_right')) {
	function _sidebar_right($items) {return manager::_sidebar_right($items);}
}

if(!function_exists('_sidebar_bottom')) {
	function _sidebar_bottom($items) {return manager::_sidebar_bottom($items);}
}

if(!function_exists('_sidebar_top_left')) {
	function _sidebar_top_left($items) {return manager::_sidebar_top_left($items);}
}

if(!function_exists('_sidebar_top_center')) {
	function _sidebar_top_center($items) {return manager::_sidebar_top_center($items);}
}

if(!function_exists('_sidebar_top_right')) {
	function _sidebar_top_right($items) {return manager::_sidebar_top_right($items);}
}

if(!function_exists('_get_philosophies')) {
	function _get_philosophies() {return manager::_get_philosophies();}
}

if(!function_exists('_with')) {
	function _with($with) {return manager::_with($with);}
}

if(!function_exists('_route')) {
	function _route($method, $url = null, $callback = null) {return manager::_route($method, $url, $callback);}
}

if(!function_exists('_look')) {
	function _look($name, $callback = null) {return manager::_look($name, $callback);}
}

if(!function_exists('_view_ready')) {
	function _view_ready($contents = '') {return manager::_view_ready($contents);}
}

if(!function_exists('_db')) {
	function _db($query) {return manager::_db($query);}
}

if(!function_exists('_to_string')) {
	function _to_string() {return manager::_to_string();}
}

if(!function_exists('_copy_dir')) {
	function _copy_dir($directory, $destination) {return manager::_copy_dir($directory, $destination);}
}

if(!function_exists('_security')) {
	function _security($key = null, $value = null) {return manager::_security($key, $value);}
}

if(!function_exists('_get_field_defaults')) {
	function _get_field_defaults() {return manager::_get_field_defaults();}
}

if(!function_exists('_tag')) {
	function _tag($tag = null) {return manager::_tag($tag);}
}

if(!function_exists('_doctype')) {
	function _doctype(string $type = null) {return manager::_doctype($type);}
}

if(!function_exists('_default_checked')) {
	function _default_checked($data = '') {return manager::_default_checked($data);}
}

if(!function_exists('_default_selected')) {
	function _default_selected($data = '') {return manager::_default_selected($data);}
}

if(!function_exists('_default_text')) {
	function _default_text($data = '') {return manager::_default_text($data);}
}

if(!function_exists('_default_value')) {
	function _default_value($data = '') {return manager::_default_value($data);}
}

if(!function_exists('_dot_to_underscore')) {
	function _dot_to_underscore($str) {return manager::_dot_to_underscore($str);}
}

if(!function_exists('_underscore_to_dot')) {
	function _underscore_to_dot($str) {return manager::_underscore_to_dot($str);}
}

if(!function_exists('_space_to_underscore')) {
	function _space_to_underscore($str) {return manager::_space_to_underscore($str);}
}

if(!function_exists('_underscore_to_space')) {
	function _underscore_to_space($str) {return manager::_underscore_to_space($str);}
}

if(!function_exists('_dot_to_dirsep')) {
	function _dot_to_dirsep($str) {return manager::_dot_to_dirsep($str);}
}

if(!function_exists('_dot_to_urlsep')) {
	function _dot_to_urlsep($str) {return manager::_dot_to_urlsep($str);}
}

if(!function_exists('_present')) {
	function _present($text, $another = '') {return manager::_present($text, $another);}
}

if(!function_exists('_presentend')) {
	function _presentend($text, $another = '') {return manager::_presentend($text, $another);}
}

if(!function_exists('_escape')) {
	function _escape($str) {return manager::_escape($str);}
}

if(!function_exists('_unescape')) {
	function _unescape($str) {return manager::_unescape($str);}
}

if(!function_exists('_export')) {
	function _export(array $options = []) {return manager::_export($options);}
}

if(!function_exists('_exception')) {
	function _exception($key, $options = '') {return manager::_exception($key, $options);}
}

if(!function_exists('_get_first_available_path')) {
	function _get_first_available_path($posible, $path = '') {return manager::_get_first_available_path($posible, $path);}
}

if(!function_exists('_keypass')) {
	function _keypass($name, $type = null) {return manager::_keypass($name, $type);}
}

if(!function_exists('_form_lng')) {
	function _form_lng($key, $options = []) {return manager::_form_lng($key, $options);}
}

if(!function_exists('_lng')) {
	function _lng($key, $options = '') {return manager::_lng($key, $options);}
}

if(!function_exists('_get_file_type_returned')) {
	function _get_file_type_returned($type, $contents = '') {return manager::_get_file_type_returned($type, $contents);}
}

if(!function_exists('_file_append_to_top')) {
	function _file_append_to_top($file, $contents) {return manager::_file_append_to_top($file, $contents);}
}

if(!function_exists('_done')) {
	function _done($reset = null) {return manager::_done($reset);}
}

if(!function_exists('_fix_type')) {
	function _fix_type($value, $level = 0) {return manager::_fix_type($value, $level);}
}

if(!function_exists('_items')) {
	function _items() {return manager::_items();}
}

if(!function_exists('_items_length')) {
	function _items_length() {return manager::_items_length();}
}

if(!function_exists('_class_get_reflection')) {
	function _class_get_reflection($name, $options = []) {return manager::_class_get_reflection($name, $options);}
}

if(!function_exists('_class_get_properties')) {
	function _class_get_properties($hole) {return manager::_class_get_properties($hole);}
}

if(!function_exists('_class_to_array')) {
	function _class_to_array($class, $options = []) {return manager::_class_to_array($class, $options);}
}

if(!function_exists('_db_name')) {
	function _db_name() {return manager::_db_name();}
}

if(!function_exists('_db_provider')) {
	function _db_provider() {return manager::_db_provider();}
}

if(!function_exists('_db_exists')) {
	function _db_exists(string $name) {return manager::_db_exists($name);}
}

if(!function_exists('_mkdirforce')) {
	function _mkdirforce(string $dir, $mode = 511) {return manager::_mkdirforce($dir, $mode);}
}

if(!function_exists('_db_build')) {
	function _db_build(string $provider, array $options = []) {return manager::_db_build($provider, $options);}
}

if(!function_exists('_db_delete')) {
	function _db_delete(string $provider, array $options = []) {return manager::_db_delete($provider, $options);}
}

if(!function_exists('_db_build_table')) {
	function _db_build_table(string $name) {return manager::_db_build_table($name);}
}

if(!function_exists('_database_table_create_column')) {
	function _database_table_create_column(string $name) {return manager::_database_table_create_column($name);}
}

if(!function_exists('_db_names')) {
	function _db_names(array $op = null) {return manager::_db_names($op);}
}

if(!function_exists('_db_names_with_tables')) {
	function _db_names_with_tables($ked = 0) {return manager::_db_names_with_tables($ked);}
}

if(!function_exists('_db_names_with_tables_and_data')) {
	function _db_names_with_tables_and_data($ked = 0) {return manager::_db_names_with_tables_and_data($ked);}
}

if(!function_exists('_db_structure')) {
	function _db_structure(string $name, $as_lines = 0) {return manager::_db_structure($name, $as_lines);}
}

if(!function_exists('_database_set_structure')) {
	function _database_set_structure(string $name, array $data) {return manager::_database_set_structure($name, $data);}
}

if(!function_exists('_db_table_exists')) {
	function _db_table_exists(string $db_name, string $table_name) {return manager::_db_table_exists($db_name, $table_name);}
}

if(!function_exists('_get_database_table_columns')) {
	function _get_database_table_columns($database, $table) {return manager::_get_database_table_columns($database, $table);}
}

if(!function_exists('_get_database_table_config')) {
	function _get_database_table_config(string $database, string $table, array $opts = []) {return manager::_get_database_table_config($database, $table, $opts);}
}

if(!function_exists('_get_database_table_config_string')) {
	function _get_database_table_config_string($database, $table) {return manager::_get_database_table_config_string($database, $table);}
}

if(!function_exists('_get_database_table_data')) {
	function _get_database_table_data($database, $tables) {return manager::_get_database_table_data($database, $tables);}
}

if(!function_exists('_get_database_table_data_all')) {
	function _get_database_table_data_all($database) {return manager::_get_database_table_data_all($database);}
}

if(!function_exists('_get_database_table_data_string')) {
	function _get_database_table_data_string($database, $table) {return manager::_get_database_table_data_string($database, $table);}
}

if(!function_exists('_db_tables')) {
	function _db_tables(string $database) {return manager::_db_tables($database);}
}

if(!function_exists('_dir_file')) {
	function _dir_file($file) {return manager::_dir_file($file);}
}

if(!function_exists('_get_exported_string_defaults')) {
	function _get_exported_string_defaults($arr) {return manager::_get_exported_string_defaults($arr);}
}

if(!function_exists('_get_keys')) {
	function _get_keys($single, $data) {return manager::_get_keys($single, $data);}
}

if(!function_exists('_get_string')) {
	function _get_string($single, $data) {return manager::_get_string($single, $data);}
}

if(!function_exists('_get_exported_string_settings')) {
	function _get_exported_string_settings($data, $default, $k, $value) {return manager::_get_exported_string_settings($data, $default, $k, $value);}
}

if(!function_exists('_get_exported_string')) {
	function _get_exported_string($default) {return manager::_get_exported_string($default);}
}

if(!function_exists('_get_items')) {
	function _get_items($id = null) {return manager::_get_items($id);}
}

if(!function_exists('_get_month')) {
	function _get_month($month) {return manager::_get_month($month);}
}

if(!function_exists('_has_more_opened_tags')) {
	function _has_more_opened_tags() {return manager::_has_more_opened_tags();}
}

if(!function_exists('_has_more_closed_tags')) {
	function _has_more_closed_tags() {return manager::_has_more_closed_tags();}
}

if(!function_exists('_get_body_classes')) {
	function _get_body_classes() {return manager::_get_body_classes();}
}

if(!function_exists('_get_include_contents')) {
	function _get_include_contents($file) {return manager::_get_include_contents($file);}
}

if(!function_exists('_get_spaces_by_level')) {
	function _get_spaces_by_level(int $number, string $operator) {return manager::_get_spaces_by_level($number, $operator);}
}

if(!function_exists('_include_exists')) {
	function _include_exists($file) {return manager::_include_exists($file);}
}

if(!function_exists('_visitor_is')) {
	function _visitor_is($str) {return manager::_visitor_is($str);}
}

if(!function_exists('_getv')) {
	function _getv($str = '') {return manager::_getv($str);}
}

if(!function_exists('_getvor')) {
	function _getvor($str = '') {return manager::_getvor($str);}
}

if(!function_exists('_is_cached')) {
	function _is_cached($file) {return manager::_is_cached($file);}
}

if(!function_exists('_is_closure')) {
	function _is_closure($callback) {return manager::_is_closure($callback);}
}

if(!function_exists('_is_int')) {
	function _is_int($element) {return manager::_is_int($element);}
}

if(!function_exists('_is_same')) {
	function _is_same($a, $b) {return manager::_is_same($a, $b);}
}

if(!function_exists('_load_components')) {
	function _load_components($types, $check = false) {return manager::_load_components($types, $check);}
}

if(!function_exists('_head_style')) {
	function _head_style(string $style = '') {return manager::_head_style($style);}
}

if(!function_exists('_load_styles')) {
	function _load_styles() {return manager::_load_styles();}
}

if(!function_exists('_load_the_scripts_components')) {
	function _load_the_scripts_components() {return manager::_load_the_scripts_components();}
}

if(!function_exists('_log')) {
	function _log($msg, $fname = 'common') {return manager::_log($msg, $fname);}
}

if(!function_exists('_make_include')) {
	function _make_include($file, $contents = '') {return manager::_make_include($file, $contents);}
}

if(!function_exists('_manual')) {
	function _manual($k, $value = null) {return manager::_manual($k, $value);}
}

if(!function_exists('_is_manual')) {
	function _is_manual($k) {return manager::_is_manual($k);}
}

if(!function_exists('_mb_str_split')) {
	function _mb_str_split($str) {return manager::_mb_str_split($str);}
}

if(!function_exists('_mkdirs')) {
	function _mkdirs(array $arr, $force = false, $path = '') {return manager::_mkdirs($arr, $force, $path);}
}

if(!function_exists('_global')) {
	function _global($key) {return manager::_global($key);}
}

if(!function_exists('_make_file')) {
	function _make_file($file, $contents = '', $lock = false) {return manager::_make_file($file, $contents, $lock);}
}

if(!function_exists('_mkfileforce')) {
	function _mkfileforce($file, $contents = '', $mode = 493) {return manager::_mkfileforce($file, $contents, $mode);}
}

if(!function_exists('_countdown')) {
	function _countdown($time, $k) {return manager::_countdown($time, $k);}
}

if(!function_exists('_chronometer')) {
	function _chronometer($time, $k) {return manager::_chronometer($time, $k);}
}

if(!function_exists('_get_time')) {
	function _get_time($zone = 'Europe/Athens') {
		$start = microtime(true);
		$miliseconds = time();
		$datetime = new DateTime('now', new DateTimeZone($zone));
		$datetime->setTimestamp($miliseconds);
		$time 		= $datetime->format('Y/d/m-H:i:s');
		$time 		= explode('-', $datetime->format('Y/d/m-H:i:s'));
		$date 		= $time[0];
		$date 		= explode('/', $date);
		$year		= (int)$date[0];
		$month		= (int)$date[2];
		$day		= (int)$date[1];
		$time		= $time[1];
		$time		= explode(':', $time);
		$our		= $time[0];
		$minutes	= $time[1];
		$seconds	= $time[2];
		$end		= microtime(true);
		$timetotime = $end - $start;
		return [
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'our' => $our,
			'minutes' => $minutes,
			'seconds' => $seconds,
			'timetotime' => $timetotime,
			'miliseconds' => $miliseconds,
			'time' => $our.':'.$minutes.':'.$seconds,
			'date' => $day.'-'.$month.'-'.$year,
			'date_and_time' => 'Date: '.$day.'-'.$month.'-'.$year.' Time: '.$our.':'.$minutes.':'.$seconds,
		];
	}
}

if(!function_exists('_nested')) {
	function _nested(array $data = []) {return manager::_nested($data);}
}

if(!function_exists('_new_line')) {
	function _new_line() {return manager::_new_line();}
}

if(!function_exists('_enter')) {
	function _enter($nums = 1, $spaced = 1) {return manager::_enter($nums, $spaced);}
}

if(!function_exists('_new_tag')) {
	function _new_tag($type = null) {return manager::_new_tag($type);}
}

if(!function_exists('_posted')) {
	function _posted() {return manager::_posted();}
}

if(!function_exists('_path')) {
	function _path() {return manager::_path();}
}

if(!function_exists('_public_path')) {
	function _public_path($str = '') {return manager::_public_path($str);}
}

if(!function_exists('_database_path')) {
	function _database_path($str = '') {return manager::_database_path($str);}
}

if(!function_exists('_common_path')) {
	function _common_path($str = '') {return manager::_common_path($str);}
}

if(!function_exists('_template_path')) {
	function _template_path($str = '') {return manager::_template_path($str);}
}

if(!function_exists('_assets_path')) {
	function _assets_path($str = '') {return manager::_assets_path($str);}
}

if(!function_exists('_no_disk')) {
	function _no_disk($source, $dest, $strim = 1, $dtrim = 1, $sdtrim = 1) {return manager::_no_disk($source, $dest, $strim, $dtrim, $sdtrim);}
}

if(!function_exists('_no_url')) {
	function _no_url($source, $dest) {return manager::_no_url($source, $dest);}
}

if(!function_exists('_sys')) {
	function _sys($k = null) {return manager::_sys($k);}
}

if(!function_exists('_app_path')) {
	function _app_path($str = '') {return manager::_app_path($str);}
}

if(!function_exists('_provider_path')) {
	function _provider_path($str = '') {return manager::_provider_path($str);}
}

if(!function_exists('_config_path')) {
	function _config_path($str = '') {return manager::_config_path($str);}
}

if(!function_exists('_controllers_path')) {
	function _controllers_path($str = '') {return manager::_controllers_path($str);}
}

if(!function_exists('_boot_path')) {
	function _boot_path($str = '') {return manager::_boot_path($str);}
}

if(!function_exists('_views_path')) {
	function _views_path($str = '') {return manager::_views_path($str);}
}

if(!function_exists('_storage')) {
	function _storage($str = '') {return manager::_storage($str);}
}

if(!function_exists('_add_script')) {
	function _add_script($html = null) {return manager::_add_script($html);}
}

if(!function_exists('_build_resources')) {
	function _build_resources($item) {return manager::_build_resources($item);}
}

if(!function_exists('_load_resources')) {
	function _load_resources($item) {return manager::_load_resources($item);}
}

if(!function_exists('_set_after_or_before')) {
	function _set_after_or_before($switch) {return manager::_set_after_or_before($switch);}
}

if(!function_exists('_set_space_level')) {
	function _set_space_level($level) {return manager::_set_space_level($level);}
}

if(!function_exists('_get_space_level')) {
	function _get_space_level() {return manager::_get_space_level();}
}

if(!function_exists('_my_space')) {
	function _my_space($num) {return manager::_my_space($num);}
}

if(!function_exists('_fixed_space')) {
	function _fixed_space($num) {return manager::_fixed_space($num);}
}

if(!function_exists('_set_type')) {
	function _set_type($type, $value) {return manager::_set_type($type, $value);}
}

if(!function_exists('_slash_and_dot_to_dirsep')) {
	function _slash_and_dot_to_dirsep($str) {return manager::_slash_and_dot_to_dirsep($str);}
}

if(!function_exists('_slash_and_dot_to_dash')) {
	function _slash_and_dot_to_dash($str) {return manager::_slash_and_dot_to_dash($str);}
}

if(!function_exists('_slash_and_dot_to_space')) {
	function _slash_and_dot_to_space($str) {return manager::_slash_and_dot_to_space($str);}
}

if(!function_exists('_slash_and_dot_to_urlsep')) {
	function _slash_and_dot_to_urlsep($str) {return manager::_slash_and_dot_to_urlsep($str);}
}

if(!function_exists('_slash_to_dot')) {
	function _slash_to_dot($str) {return manager::_slash_to_dot($str);}
}

if(!function_exists('_keyboard')) {
	function _keyboard($data = null) {return manager::_keyboard($data);}
}

if(!function_exists('_tab')) {
	function _tab($nums = 1) {return manager::_tab($nums);}
}

if(!function_exists('_space')) {
	function _space($number = 1) {return manager::_space($number);}
}

if(!function_exists('_space_to_dash')) {
	function _space_to_dash($str) {return manager::_space_to_dash($str);}
}

if(!function_exists('_absolute_disk')) {
	function _absolute_disk($path) {return manager::_absolute_disk($path);}
}

if(!function_exists('_disk')) {
	function _disk($path = '') {return manager::_disk($path);}
}

if(!function_exists('_media')) {
	function _media($source, $destination) {return manager::_media($source, $destination);}
}

if(!function_exists('_style')) {
	function _style($data) {return manager::_style($data);}
}

if(!function_exists('_remove_lines')) {
	function _remove_lines($data, $with = '') {return manager::_remove_lines($data, $with);}
}

if(!function_exists('_style_to_file')) {
	function _style_to_file($style, $file) {return manager::_style_to_file($style, $file);}
}

if(!function_exists('_syntax_error')) {
	function _syntax_error($error, $line = 0) {return manager::_syntax_error($error, $line);}
}

if(!function_exists('_tab_space')) {
	function _tab_space($number) {return manager::_tab_space($number);}
}

if(!function_exists('_text')) {
	function _text($text = null, $lined = 0) {return manager::_text($text, $lined);}
}

if(!function_exists('_title')) {
	function _title($title) {return manager::_title($title);}
}

if(!function_exists('_subtitle')) {
	function _subtitle($subtitle) {return manager::_subtitle($subtitle);}
}

if(!function_exists('_get_title')) {
	function _get_title() {return manager::_get_title();}
}

if(!function_exists('_get_subtitle')) {
	function _get_subtitle() {return manager::_get_subtitle();}
}

if(!function_exists('_allowed_execute_lng_codes')) {
	function _allowed_execute_lng_codes() {return manager::_allowed_execute_lng_codes();}
}

if(!function_exists('_get_style')) {
	function _get_style() {return manager::_get_style();}
}

if(!function_exists('_get_standard_js')) {
	function _get_standard_js() {return manager::_get_standard_js();}
}

if(!function_exists('_is_mode')) {
	function _is_mode($mode) {return manager::_is_mode($mode);}
}

if(!function_exists('_penetration')) {
	function _penetration(string $something, string $way = '.') {return manager::_penetration($something, $way);}
}

if(!function_exists('_string_base')) {
	function _string_base($k, $way) {return manager::_string_base($k, $way);}
}

if(!function_exists('_to_dirsep')) {
	function _to_dirsep($str) {return manager::_to_dirsep($str);}
}

if(!function_exists('_to_urlsep')) {
	function _to_urlsep($str = '') {return manager::_to_urlsep($str);}
}

if(!function_exists('_underscore_to_upercase')) {
	function _underscore_to_upercase($name) {return manager::_underscore_to_upercase($name);}
}

if(!function_exists('_array_keys_lower')) {
	function _array_keys_lower(array $arr) {return manager::_array_keys_lower($arr);}
}

if(!function_exists('_snake')) {
	function _snake($str) {return manager::_snake($str);}
}

if(!function_exists('_delete')) {
	function _delete($key) {return manager::_delete($key);}
}

if(!function_exists('_uri')) {
	function _uri($str = '') {return manager::_uri($str);}
}

if(!function_exists('_request')) {
	function _request(array $data = []) {return manager::_request($data);}
}

if(!function_exists('_method')) {
	function _method($m = null) {return manager::_method($m);}
}

if(!function_exists('_equal')) {
	function _equal($a, $b) {return manager::_equal($a, $b);}
}

if(!function_exists('_loader_is')) {
	function _loader_is($loader) {return manager::_loader_is($loader);}
}

if(!function_exists('_is_secure')) {
	function _is_secure() {return manager::_is_secure();}
}

if(!function_exists('_scheme')) {
	function _scheme() {return manager::_scheme();}
}

if(!function_exists('_url_host')) {
	function _url_host() {return manager::_url_host();}
}

if(!function_exists('_get_domain')) {
	function _get_domain() {return manager::_get_domain();}
}

if(!function_exists('_vrl')) {
	function _vrl(string $path = '', array $query = [], array $replace = []) {return manager::_vrl($path, $query, $replace);}
}

if(!function_exists('_url')) {
	function _url(string $path = '', array $query = [], array $replace = []) {return manager::_url($path, $query, $replace);}
}

if(!function_exists('_is_gp')) {
	function _is_gp($key) {return manager::_is_gp($key);}
}

if(!function_exists('_gp')) {
	function _gp($key) {return manager::_gp($key);}
}

if(!function_exists('_is_g')) {
	function _is_g($k) {return manager::_is_g($k);}
}

if(!function_exists('_g')) {
	function _g($k) {return manager::_g($k);}
}

if(!function_exists('_is_p')) {
	function _is_p($k) {return manager::_is_p($k);}
}

if(!function_exists('_p')) {
	function _p($k) {return manager::_p($k);}
}

if(!function_exists('_gall')) {
	function _gall() {return manager::_gall();}
}

if(!function_exists('_pall')) {
	function _pall() {return manager::_pall();}
}

if(!function_exists('_urlsep')) {
	function _urlsep($str = '') {return manager::_urlsep($str);}
}

if(!function_exists('_array_from_string')) {
	function _array_from_string($case, string $str) {return manager::_array_from_string($case, $str);}
}

if(!function_exists('_change_prefix')) {
	function _change_prefix(string $from, string $to, string $str) {return manager::_change_prefix($from, $to, $str);}
}

if(!function_exists('_remove_prefix')) {
	function _remove_prefix(string $prefix, string $str, string $append = '') {return manager::_remove_prefix($prefix, $str, $append);}
}

if(!function_exists('_usb')) {
	function _usb($file = '') {return manager::_usb($file);}
}

if(!function_exists('_implode_lines')) {
	function _implode_lines($arr) {return manager::_implode_lines($arr);}
}

if(!function_exists('_require_local')) {
	function _require_local($path, $data = []) {return manager::_require_local($path, $data);}
}

if(!function_exists('_get_files')) {
	function _get_files($dir) {return manager::_get_files($dir);}
}

if(!function_exists('_redir')) {
	function _redir($dir, $preserve = false) {return manager::_redir($dir, $preserve);}
}

if(!function_exists('_mkdir')) {
	function _mkdir($dir, $mode = 777, $recursive = true) {return manager::_mkdir($dir, $mode, $recursive);}
}

if(!function_exists('_part')) {
	function _part($bisectrix, $content, $before = true) {return manager::_part($bisectrix, $content, $before);}
}

if(!function_exists('_get_compiler_info')) {
	function _get_compiler_info() {return manager::_get_compiler_info();}
}

if(!function_exists('_file_system')) {
	function _file_system($directory) {return manager::_file_system($directory);}
}

if(!function_exists('_bind')) {
	function _bind($callback, $obj) {return manager::_bind($callback, $obj);}
}

if(!function_exists('_reflection')) {
	function _reflection($class) {return manager::_reflection($class);}
}

if(!function_exists('_date_time')) {
	function _date_time($when, $zone) {return manager::_date_time($when, $zone);}
}

if(!function_exists('_get_date_time_zone')) {
	function _get_date_time_zone($zone) {return manager::_get_date_time_zone($zone);}
}

if(!function_exists('_get_paths')) {
	function _get_paths($path) {return manager::_get_paths($path);}
}

if(!function_exists('_get_paths_only')) {
	function _get_paths_only($path) {return manager::_get_paths_only($path);}
}

if(!function_exists('_get_time_in_milliseconds')) {
	function _get_time_in_milliseconds() {return manager::_get_time_in_milliseconds();}
}

if(!function_exists('_get_type')) {
	function _get_type($element) {return manager::_get_type($element);}
}

if(!function_exists('_is_double')) {
	function _is_double($element) {return manager::_is_double($element);}
}

if(!function_exists('_is_float')) {
	function _is_float($element) {return manager::_is_float($element);}
}

if(!function_exists('_is_numeric')) {
	function _is_numeric($element) {return manager::_is_numeric($element);}
}

if(!function_exists('_string')) {
	function _string($str, $options = []) {return manager::_string($str, $options);}
}

if(!function_exists('_str_split')) {
	function _str_split($str) {return manager::_str_split($str);}
}

if(!function_exists('_ucwords')) {
	function _ucwords($str) {return manager::_ucwords($str);}
}

if(!function_exists('_str_trans')) {
	function _str_trans($one, $two) {return manager::_str_trans($one, $two);}
}

if(!function_exists('_build_query')) {
	function _build_query($data, $prefix, $separator) {return manager::_build_query($data, $prefix, $separator);}
}

if(!function_exists('_pos')) {
	function _pos($haystack, $needle) {return manager::_pos($haystack, $needle);}
}

if(!function_exists('_replace_spaces_with_one')) {
	function _replace_spaces_with_one($str) {return manager::_replace_spaces_with_one($str);}
}

if(!function_exists('_replace')) {
	function _replace($search, $replace, $subject) {return manager::_replace($search, $replace, $subject);}
}

if(!function_exists('_file_put_contents')) {
	function _file_put_contents($file, $contents, $lock = false) {return manager::_file_put_contents($file, $contents, $lock);}
}

if(!function_exists('_is_array')) {
	function _is_array($obj) {return manager::_is_array($obj);}
}

if(!function_exists('_parse_url')) {
	function _parse_url($str) {return manager::_parse_url($str);}
}

if(!function_exists('_urlencode')) {
	function _urlencode($str) {return manager::_urlencode($str);}
}

if(!function_exists('_urldecode')) {
	function _urldecode($str) {return manager::_urldecode($str);}
}

if(!function_exists('_opendir')) {
	function _opendir($dir) {return manager::_opendir($dir);}
}

if(!function_exists('_readdir')) {
	function _readdir($dir) {return manager::_readdir($dir);}
}

if(!function_exists('_closedir')) {
	function _closedir($dir) {return manager::_closedir($dir);}
}

if(!function_exists('_is_dir')) {
	function _is_dir($dir) {return manager::_is_dir($dir);}
}

if(!function_exists('_is_empty')) {
	function _is_empty($obj) {return manager::_is_empty($obj);}
}

if(!function_exists('_key_exists')) {
	function _key_exists($k, $arr) {return manager::_key_exists($k, $arr);}
}

if(!function_exists('_file_get_contents')) {
	function _file_get_contents($file, $lock = false) {return manager::_file_get_contents($file, $lock);}
}

if(!function_exists('_exit')) {
	function _exit() {return manager::_exit();}
}

if(!function_exists('_method_exists')) {
	function _method_exists($instance, $name) {return manager::_method_exists($instance, $name);}
}

if(!function_exists('_refile')) {
	function _refile($file) {return manager::_refile($file);}
}

if(!function_exists('_count')) {
	function _count($array = null, $auto = 1) {return manager::_count($array, $auto);}
}

if(!function_exists('_basename')) {
	function _basename($path) {return manager::_basename($path);}
}

if(!function_exists('_cmd')) {
	function _cmd($commands) {return manager::_cmd($commands);}
}

if(!function_exists('_array_zero')) {
	function _array_zero($arr) {return manager::_array_zero($arr);}
}

if(!function_exists('_mysqli')) {
	function _mysqli($host, $username, $password, $database) {return manager::_mysqli($host, $username, $password, $database);}
}

if(!function_exists('_implode')) {
	function _implode($sep, $arr) {return manager::_implode($sep, $arr);}
}

if(!function_exists('_explode')) {
	function _explode($sep, $string) {return manager::_explode($sep, $string);}
}

if(!function_exists('_in_array')) {
	function _in_array($k, $arr, $absol = false) {return manager::_in_array($k, $arr, $absol);}
}

if(!function_exists('_file_append')) {
	function _file_append($file, $contents) {return manager::_file_append($file, $contents);}
}

if(!function_exists('_make_dir_force')) {
	function _make_dir_force($file, $mode = 777, $recursive = true) {return manager::_make_dir_force($file, $mode, $recursive);}
}

if(!function_exists('_print')) {
	function _print($str) {return manager::_print($str);}
}

if(!function_exists('_json_encode')) {
	function _json_encode($data) {return manager::_json_encode($data);}
}

if(!function_exists('_json_decode')) {
	function _json_decode($data, $as_array) {return manager::_json_decode($data, $as_array);}
}

if(!function_exists('_copy_file')) {
	function _copy_file($file, $target) {return manager::_copy_file($file, $target);}
}

if(!function_exists('_is_object')) {
	function _is_object($element) {return manager::_is_object($element);}
}

if(!function_exists('_is_null')) {
	function _is_null($element) {return manager::_is_null($element);}
}

if(!function_exists('_decoder')) {
	function _decoder($contents, $callback = null, $arg = null, $right_to_left = 0, $option = null) {return manager::_decoder($contents, $callback, $arg, $right_to_left, $option);}
}

if(!function_exists('_lower')) {
	function _lower($str) {return manager::_lower($str);}
}

if(!function_exists('_upper')) {
	function _upper($str) {return manager::_upper($str);}
}

if(!function_exists('_file_extention')) {
	function _file_extention($file) {return manager::_file_extention($file);}
}

if(!function_exists('_file')) {
	function _file($file) {return manager::_file($file);}
}

if(!function_exists('_remove_spaces')) {
	function _remove_spaces($str) {return manager::_remove_spaces($str);}
}

if(!function_exists('_css_fix')) {
	function _css_fix($value) {return manager::_css_fix($value);}
}

if(!function_exists('_trim')) {
	function _trim($str, $mask = '') {return manager::_trim($str, $mask);}
}

if(!function_exists('_add_modal_btn')) {
	function _add_modal_btn($data) {return manager::_add_modal_btn($data);}
}

if(!function_exists('_add_modal')) {
	function _add_modal($data) {return manager::_add_modal($data);}
}

if(!function_exists('_add_icons_table')) {
	function _add_icons_table($data, $by = 1) {return manager::_add_icons_table($data, $by);}
}

if(!function_exists('_get_between')) {
	function _get_between($str, $start, $end) {return manager::_get_between($str, $start, $end);}
}

if(!function_exists('_rtrim')) {
	function _rtrim($str, $mask = '') {return manager::_rtrim($str, $mask);}
}

if(!function_exists('_ltrim')) {
	function _ltrim($str, $mask = '') {return manager::_ltrim($str, $mask);}
}

if(!function_exists('_instanceof')) {
	function _instanceof($object, $name) {return manager::_instanceof($object, $name);}
}

if(!function_exists('_int')) {
	function _int($str) {return manager::_int($str);}
}

if(!function_exists('_ord')) {
	function _ord($str) {return manager::_ord($str);}
}

if(!function_exists('_build_attributes')) {
	function _build_attributes($type, $tag, $attrs) {return manager::_build_attributes($type, $tag, $attrs);}
}

if(!function_exists('_item_check_spaces')) {
	function _item_check_spaces($item, $level) {return manager::_item_check_spaces($item, $level);}
}

if(!function_exists('_build_document')) {
	function _build_document($data, $level = 0) {return manager::_build_document($data, $level);}
}

if(!function_exists('_get_builded_text')) {
	function _get_builded_text($item) {return manager::_get_builded_text($item);}
}

if(!function_exists('_is_obj')) {
	function _is_obj($sog) {return manager::_is_obj($sog);}
}

if(!function_exists('_opi')) {
	function _opi($obj) {return manager::_opi($obj);}
}

if(!function_exists('_add_options_menu')) {
	function _add_options_menu($html = null) {return manager::_add_options_menu($html);}
}

if(!function_exists('_add_form')) {
	function _add_form($html = null) {return manager::_add_form($html);}
}

if(!function_exists('_require')) {
	function _require($file, $position = 'require') {return manager::_require($file, $position);}
}

if(!function_exists('_get_bro_paths')) {
	function _get_bro_paths() {return manager::_get_bro_paths();}
}

if(!function_exists('_array_next')) {
	function _array_next(int $key, array $array) {return manager::_array_next($key, $array);}
}

if(!function_exists('_array_prev')) {
	function _array_prev(int $key, array $array) {return manager::_array_prev($key, $array);}
}

if(!function_exists('_get_active_disk')) {
	function _get_active_disk($id = null) {return manager::_get_active_disk($id);}
}

if(!function_exists('_mmable')) {
	function _mmable($id, $data = null) {return manager::_mmable($id, $data);}
}

if(!function_exists('_object_to_array')) {
	function _object_to_array($object) {return manager::_object_to_array($object);}
}

if(!function_exists('_get_core_public_functions')) {
	function _get_core_public_functions() {return manager::_get_core_public_functions();}
}

if(!function_exists('_brofnintel')) {
	function _brofnintel($key, $is, $a = null, $b = null) {return manager::_brofnintel($key, $is, $a, $b);}
}

if(!function_exists('_interface')) {
	function _interface($decoded, $contents, $callback, $name) {return manager::_interface($decoded, $contents, $callback, $name);}
}

if(!function_exists('_gun')) {
	function _gun($name = null, $data = []) {return manager::_gun($name, $data);}
}

if(!function_exists('_getlicenseid')) {
	function _getlicenseid() {return manager::_getlicenseid();}
}

if(!function_exists('_all')) {
	function _all() {return manager::_all();}
}

if(!function_exists('_get_interface_key')) {
	function _get_interface_key($key) {return manager::_get_interface_key($key);}
}

if(!function_exists('_get_interface')) {
	function _get_interface($name) {return manager::_get_interface($name);}
}

if(!function_exists('_notset')) {
	function _notset($k, $logic = null) {return manager::_notset($k, $logic);}
}

if(!function_exists('_isset')) {
	function _isset($k, $logic = null) {return manager::_isset($k, $logic);}
}

if(!function_exists('_isset_get_else')) {
	function _isset_get_else($key, $value = null) {return manager::_isset_get_else($key, $value);}
}

if(!function_exists('_set')) {
	function _set($key, $value) {return manager::_set($key, $value);}
}

if(!function_exists('_get')) {
	function _get($key, $is = null, $default = null) {return manager::_get($key, $is, $default);}
}

if(!function_exists('_unset')) {
	function _unset($key) {return manager::_unset($key);}
}

if(!function_exists('_push')) {
	function _push($k, $value) {return manager::_push($k, $value);}
}

if(!function_exists('_function_exists')) {
	function _function_exists($name) {return manager::_function_exists($name);}
}

if(!function_exists('_set_function')) {
	function _set_function($name, $callback, $obj = null) {return manager::_set_function($name, $callback, $obj);}
}

if(!function_exists('_load_alg')) {
	function _load_alg($visy, $name = null, $exec = null, $algo = null, $loto = null) {return manager::_load_alg($visy, $name, $exec, $algo, $loto);}
}

if(!function_exists('_load_ram')) {
	function _load_ram($atom, $method, $arguments) {return manager::_load_ram($atom, $method, $arguments);}
}

if(!function_exists('_instance')) {
	function _instance($id, $name = null, $method = null, $arguments = null) {return manager::_instance($id, $name, $method, $arguments);}
}

if(!function_exists('_get_guns_to_load')) {
	function _get_guns_to_load() {return manager::_get_guns_to_load();}
}

if(!function_exists('_load')) {
	function _load($name = '', $data = 'nothing') {return manager::_load($name, $data);}
}

if(!function_exists('_unload')) {
	function _unload($name) {return manager::_unload($name);}
}

if(!function_exists('_unload_all')) {
	function _unload_all() {return manager::_unload_all();}
}

if(!function_exists('_get_function')) {
	function _get_function($name) {return manager::_get_function($name);}
}

if(!function_exists('_remove_function')) {
	function _remove_function($name) {return manager::_remove_function($name);}
}

if(!function_exists('_digital')) {
	function _digital($a = null, $b = null, $c = null) {return manager::_digital($a, $b, $c);}
}

if(!function_exists('_digital')) {
	function _digital($a = null, $b = null, $c = null) {return manager::_digital($a, $b, $c);}
}

if(!function_exists('_library')) {
	function _library($manage) {return manager::_library($manage);}
}

if(!function_exists('_prepare')) {
	function _prepare($obj, $method, $arguments) {return manager::_prepare($obj, $method, $arguments);}
}

if(!function_exists('_infinity')) {
	function _infinity() {return manager::_infinity();}
}

if(!function_exists('_config')) {
	function _config($id, $key) {return manager::_config($id, $key);}
}

if(!function_exists('_load_config')) {
	function _load_config($location) {return manager::_load_config($location);}
}

if(!function_exists('_monitor')) {
	function _monitor() {return manager::_monitor();}
}

if(!function_exists('_conclude')) {
	function _conclude() {return manager::_conclude();}
}

if(!function_exists('_furl')) {
	function _furl() {return manager::_furl();}
}

if(!function_exists('_preconclude')) {
	function _preconclude() {return manager::_preconclude();}
}

if(!function_exists('_complex_http_request')) {
	function _complex_http_request($provider = 'curl', string $method = 'get', string $url = '/', $data = [], array $headers = [], $priority = null) {return manager::_complex_http_request($provider, $method, $url, $data, $headers, $priority);}
}

if(!function_exists('_stringel')) {
	function _stringel($array, string $string) {return manager::_stringel($array, $string);}
}

if(!function_exists('_sessid')) {
	function _sessid($id) {return manager::_sessid($id);}
}

if(!function_exists('_http')) {
	function _http($options) {return manager::_http($options);}
}

if(!function_exists('_add_logo')) {
	function _add_logo($html = null) {return manager::_add_logo($html);}
}

if(!function_exists('_add_info_key_value')) {
	function _add_info_key_value($html = null) {return manager::_add_info_key_value($html);}
}

if(!function_exists('_add_search_box')) {
	function _add_search_box($html = null) {return manager::_add_search_box($html);}
}

if(!function_exists('_add_search_results_item')) {
	function _add_search_results_item($html = null) {return manager::_add_search_results_item($html);}
}

if(!function_exists('_mb_replace')) {
	function _mb_replace($heystack, $needle, $source) {return manager::_mb_replace($heystack, $needle, $source);}
}

if(!function_exists('_add')) {
	function _add($algorithm, $items = [], $type = 'html') {return manager::_add($algorithm, $items, $type);}
}

if(!function_exists('_build_fields')) {
	function _build_fields($fields) {return manager::_build_fields($fields);}
}

if(!function_exists('_add_menu_button')) {
	function _add_menu_button($html = null) {return manager::_add_menu_button($html);}
}

if(!function_exists('_add_user_menu')) {
	function _add_user_menu($html = null) {return manager::_add_user_menu($html);}
}

if(!function_exists('_get_non_alpha_numeric_characters')) {
	function _get_non_alpha_numeric_characters() {return manager::_get_non_alpha_numeric_characters();}
}

if(!function_exists('_find')) {
	function _find($key) {return manager::_find($key);}
}

if(!function_exists('_variabled')) {
	function _variabled($str) {return manager::_variabled($str);}
}

if(!function_exists('_call_array_obj')) {
	function _call_array_obj($obj, $name, $args) {return manager::_call_array_obj($obj, $name, $args);}
}

if(!function_exists('_ucfirst')) {
	function _ucfirst($str) {return manager::_ucfirst($str);}
}

if(!function_exists('_substr')) {
	function _substr(string $string, $start = 0, $length = null) {return manager::_substr($string, $start, $length);}
}

if(!function_exists('_length')) {
	function _length($data) {return manager::_length($data);}
}

if(!function_exists('_is_string')) {
	function _is_string($element) {return manager::_is_string($element);}
}

if(!function_exists('_upper_to_underscore')) {
	function _upper_to_underscore($string) {return manager::_upper_to_underscore($string);}
}

if(!function_exists('_third')) {
	function _third() {return manager::_third();}
}

if(!function_exists('_fixed_bridge_method')) {
	function _fixed_bridge_method($str, $prefix) {return manager::_fixed_bridge_method($str, $prefix);}
}


?>
