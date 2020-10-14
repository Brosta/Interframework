<?php

namespace brosta\interframework;

use stdClass;
use brosta\interframework\decoder;

// 03-07-2012 + 6, 76/2018

class signal {

	private $app;
	private $ram;

	public function _main($app) {
		$this->app->reset_ram($app);
	}

	public function _get_aliases() {
		return $this->ram[0];
	}

	public function _username($username) {
		$this->ram[0]->control->set('username', $username);
	}

	public function _password($password) {
		$this->ram[0]->control->set('password', $password);
	}

	public function _database_ready($dbname) {
		return $this->app->database_exists($dbname);
	}

	public function _in_file_type($type, $contents, $close = false) {
		if($type == 'php') {
			return '<?php '.$this->app->new_line().$contents.($close === true ? $this->app->new_line().'?>' : '');
		}
		elseif($type == 'javascript') {
			return $contents;
		}
	}

	public function _nextprev(array $array, $sign, $need, $target, $ignore = [], $ignore_keys = [], $range = null, $results = [], $num = 1) {
		$results[$num] = null;
		$drop = false;
		$drop_keys = false;
		$drop_range = false;
		$exists = $this->app->key_exists($target, $array);
		if($exists) {
			$drop = $this->app->in_array($array[$target], $ignore);
			$drop_keys = $this->app->in_array($target, $ignore_keys);
			if($range) {
				if($target >= $range[0] && $target <= $range[1]) {
					$drop_range = true;
				}
			}
		}
		if(!$drop && !$drop_keys && !$drop_range) {
			if($exists) {
				$results[$num] = $array[$target];
			}
			$num++;
			$need--;
		}
		if($sign == '+') {
			$target++;
		}
		elseif($sign == '-') {
			$target--;
		}
		if($need == 0) {
			return $results;
		} else {
			return $this->app->nextprev($array, $sign, $need, $target, $ignore, $ignore_keys, $range, $results, $num);
		}
	}

	public function _build_application($document) {
		if($this->app->in_array($document->get('this.type'), $this->app->allowed_execute_lng_codes())) {

				if($document->get('as', 'helpers')) {
					$document->enter();
					$document->text('use '.$this->ram[0]->client->get('namespace.manager').';');

					$document->enter();
					foreach($document->get('app.methods') as $m) {
						if($m['visibility'] !== 'public') {
							continue;
						}

						if($m['visibility'] == 'public') {
							$document->tag('function');
							foreach($m as $key => $value) {
								if($key == 'body') {
									$m[$key] = '';
									if($m['name'] == '_get_default_static') {
										$document->tag('untaged');
											$document->text('return ['.$this->app->string($this->app->get_default_static(['type' => 'html'])).'];', 1);
										$document->tag();
									} else {
										$document->text('return '.$this->ram[0]->client->get('namespace.manager_name').'::'.$this->app->fixed_bridge_method($m['name']).'('.$this->app->args_to_string_vars($m['arguments'], $document->get('this.type')).');', 1);
									}
								}
								elseif($key == 'arguments') {
									
								}
								elseif($key == 'name') {
									
								}
								$document->attr($key, $m[$key]);
							}
							$document->tag();
							$document->enter();
						}
					}
				}
				elseif($document->get('as', 'manager')) {
					$document->fixedSpace(-1);

					if($document->get('app.in_namespace')) {
						$document->tag('namespace')->attr('name', $this->ram[0]->client->get('namespace.name'));
						$document->enter();
					}

					if($document->get('app.in_class')) {
						$document->tag('class')->attr('name', $this->ram[0]->client->get('namespace.manager_name'));
					}

					if($document->get('app.properties')) {
						$document->enter();
						foreach($document->get('app.properties') as $property) {
							$document->tag('property');
							foreach($property as $key => $value) {
								$document->attr($key, $value);
							}
							$document->tag();
						}
					}

					$document->enter();
					foreach($document->get('app.methods') as $m) {
						if($m['visibility'] !== 'public') {
							continue;
						}

						if($m['visibility'] == 'public') {
							$document->tag('function');
							foreach($m as $key => $value) {
								if($key == 'body') {
									$m[$key] = $this->app->decoder($m[$key], 1);
								}
								$document->attr($key, $m[$key]);
							}
							$document->tag();
							$document->enter();
						}
					}

					if($document->get('app.in_namespace')) {
						$document->tag();
					}

					if($document->get('app.in_class')) {
						$document->tag();
					}
				}
				elseif($document->get('as', 'signal')) {
					$document->fixedSpace(-1);

					if($document->get('app.in_namespace')) {
						$document->tag('namespace')->attr('name', $this->ram[0]->client->get('namespace.name'));
						$document->enter();
					}

					if($document->get('app.in_class')) {
						$document->tag('class')->attr('name', $this->ram[0]->client->get('namespace.signal_name'));
					}

					if($document->get('app.properties')) {
						$document->enter();
						foreach($document->get('app.properties') as $property) {
							$document->tag('property');
							foreach($property as $key => $value) {
								$document->attr($key, $value);
							}
							$document->tag();
						}
					}

					$document->enter();
					foreach($document->get('app.methods') as $m) {
						if($m['visibility'] !== 'public') {
							continue;
						}

						if($m['visibility'] == 'public') {
							$document->tag('function');
							foreach($m as $key => $value) {
								if($key == 'body') {
									$m[$key] = $this->app->decoder($m[$key], 1);
								}
								$document->attr($key, $m[$key]);
							}
							$document->tag();
							$document->enter();
						}
					}

					if($document->get('app.in_namespace')) {
						$document->tag();
					}

					if($document->get('app.in_class')) {
						$document->tag();
					}
				}

				$type = $document->get('this.type');

				if($results = $document->done(1)) {
					return $this->app->in_file_type($type, $results, true);
				}
		} else {
			$this->app->exception('server', 'Server not support ( '.$document->get('this.type').' ) file extention.');
		}

		return $this->app->exception('build', 'Can not create application');
	}

	public function _decode_build($contents, $callback = null) {
		if($callback != null) {
			if(!$this->app->is_int($callback)) {
				$this->app->exception('argument', 'If Argument 2 exists, argument 2 must be integer, or if not exists must be a null');
			}
			return $this->app->decoder($contents, $callback);
		} else {
			return $this->app->decoder($contents, function($data) {
				$this->app->decoder->_prog_process($data);
			});
		}
	}

	public function _disk_absolute($str = '') {
		return $this->app->no_disk($this->app->disk(), $str);
	}

	public function _build_functions($type, $as, $app) {
		$document = $this->app->document([
			'type' => $type
		]);

		$document->set('as', $as);
		$document->set('app', $app);

		if($document->get('as', 'helpers')) {
			if($this->app->make_file_force($this->app->public_path('assets/helpers.js'))) {
				if($type == 'javascript') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->public_path('assets/helpers.js')));
				}
				elseif($type == 'php') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->provider_path('brosta/src/interframework/helpers.php')));
				}
				$this->app->make_file_force($path, $this->app->build_application($document));
			}
		}
		elseif($document->get('as', 'signal')) {
			if($this->app->make_file_force($this->app->public_path('assets/signal.js'))) {
				if($type == 'javascript') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->public_path('assets/signal.js')));
				}
				elseif($type == 'php') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->provider_path('brosta/src/interframework/signal.php')));
				}
				$this->app->make_file_force($path, $this->app->build_application($document));
			}
		}
		elseif($document->get('as', 'manager')) {
			if($this->app->make_file_force($this->app->public_path('assets/manager.js'))) {
				if($type == 'javascript') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->public_path('assets/manager.js')));
				}
				elseif($type == 'php') {
					$path = $this->app->storage($this->app->disk_absolute($this->app->provider_path('brosta/src/interframework/manager.php')));
				}
				$this->app->make_file_force($path, $this->app->build_application($document));
			}
		}
	}

	public function _smoothly() {
		if(!$this->app->ini('safe_mode')) {
			if(!$this->app->file($this->app->assets_path('helpers.js'))) {
				$data = $this->app->class_to_array($this->ram[0]->client->get('namespace.signal'));
				$this->app->build_functions($this->ram[0]->config->app->get('client_lng'), 'helpers', $data);
			}
			if(!$this->app->file($this->app->assets_path($this->ram[0]->client->get('namespace.signal_name').'.js'))) {
				$data = $this->app->class_to_array($this->ram[0]->client->get('namespace.signal'));
				$this->app->build_functions($this->ram[0]->config->app->get('client_lng'), 'signal', $data);
			}
			if(!$this->app->file($this->app->assets_path($this->ram[0]->client->get('namespace.manager_name').'.js'))) {
				$data = $this->app->class_to_array($this->ram[0]->client->get('namespace.manager'));
				$this->app->build_functions($this->ram[0]->config->app->get('client_lng'), 'manager', $data);
			}
		}
		return 1;
	}

	public function _client($key) {
		return $this->ram[0]->client->get($key);
	}

	public function _set_response($key, $value) {
		return $this->ram[0]->response->set($key, $value);
	}

	public function _preconclude($request = []) {

		if($this->app->smoothly()) {
			$controller = $this->app->get_controller();

			$this->app->set_response('data', $controller);
			$this->app->set_response('data', 200);

			if($this->app->ini('view')) {
				if(!$this->app->isset('view')) {
					$this->ram[0]->client->set('view', $this->app->to_dirsep($this->ram[0]->route->get('controller').'/'.$this->ram[0]->route->get('method')));
				}
			}
			if($this->app->is_object($this->app->get_with())) {
				if($this->app->instanceof($this->app->get_with(), $this->ram[0]->client->get('namespace.manager'))) {
					$this->app->conclude($this->app->get_with()->all());
				} else {
					$this->app->exception('must be an instance of '.$this->ram[0]->client->get('namespace.manager'));
				}
			} else {
				$this->app->conclude($this->app->controlled($this->app->get_with(), 'conclude'));
			}
		}
	}

	public function _keep_numbers($str) {
		return $this->app->decoder($str, function($data) {
			if($data->type != 'number') {
				$data->a_o = '';
			}
		});
	}

	public function _debug($data) {
		$this->app->print($data);
		$this->app->exit();
	}

	public function _strkvby($array, $ssymbol, $ksymbol) {
		$results = [];
		for($i=0;$i<$this->app->count($array);$i++) {
			$arr = $this->app->explode($ssymbol, $array[$i]);
			for($s=0;$s<$this->app->count($arr);$s++) {
				$l = $this->app->explode($ksymbol, $arr[$s]);
				$results[$l[0]] = $l[1];
			}
		}
		return $results;
	}

	public function _build_rules($rules, $symbol) {
		$results = [];
		for($i=0;$i<$this->app->count($rules);$i++) {
			$options = $this->app->explode($symbol, $rules[$i]);
			$results[$i]['rule'] = $options[0];
			$results[$i]['opts'] = [];
			if($this->app->count($options) > 1) {
				unset($options[0]);
				$options = $this->app->array_zero($options);
				if($this->app->contains_in($options[0], '=')) {
					$results[$i]['opts'] = $this->app->strkvby($options, ',', '=');
				}
				elseif($this->app->contains_in($options[0], ',')) {
					$results[$i]['opts'] = $this->app->explode(',', $options[0]);
				} else {
					$results[$i]['opts'] = $options;
				}
			}
		}
		return $results;
	}

	public function _validate_normalize($fields) {
		for($i=0;$i<$this->app->count($fields);$i++) {
			if(!$fields[$i]['valid']) {
				$fields[$i]['valid'] = [];
			} else {
				$fields[$i]['valid'] = $this->app->build_rules($this->app->explode('|', $fields[$i]['valid']), ':');
			}
			$fields[$i]['errors'] = [];
		}
		return $fields;
	}

	public function _get_form($str) {
		return $this->ram[0]->client->lng('forms.'.$str);
	}

	public function _validate($fields, $input = []) {
		return $this->app->new('brosta\interframework\validator', [$this->app->validate_normalize($fields), $input]);
	}

	public function _load_meta() {
		if($this->ram[0]->config->monitor->get('meta.charset')) {
			$this->ram[0]->monitor->html->tag('meta')->attr('charset', $this->ram[0]->config->monitor->get('meta.charset'))->tag();
		}
		$this->ram[0]->monitor->html->tag('meta')->attr('name', 'viewport')->attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->tag();
		$this->ram[0]->monitor->html->tag('meta')->attr('httpequiv', 'Content-Type')->attr('content', 'text/html; charset=UTF-8')->tag();
		$this->ram[0]->monitor->html->tag('meta')->attr('id', 'domain')->attr('content', 'My domain')->tag();
		$this->ram[0]->monitor->html->tag('title')->text($this->ram[0]->client->get('title'))->tag();
		$this->ram[0]->monitor->html->tag('link')->attr('rel', 'shortcut icon')->attr('type', 'image/png')->attr('href', $this->app->assets_url('img/favicon.png'))->tag();
	}

	public function _monitor($data = null) {
		if($this->ram[0]->config->monitor->get('top_comment')) {
			$data->html->text($this->ram[0]->config->monitor->get('top_comment'));
		}
		$data->html->tag('doctype')->attr('html')->tag();
		$data->html->tag('html');
			if($this->ram[0]->config->monitor->get('doctype_lang')) {
				$data->html->attr('lang', $this->ram[0]->config->monitor->get('doctype_lang'));
			}
			$data->html->tag('head');
				$data->html->load_meta();
				$data->html->load_components('css');
				$data->html->load_styles();
			$data->html->tag();
			$data->html->tag('body')->attr('class', $data->html->get_body_classes());
				$data->html->tag('div')->attr('class', 'cm-app');
					$data->html->text($this->ram[0]->monitor->get('contents'), 1);
					$data->html->load_components('js');
					$data->html->load_the_scripts_components();
				$data->html->tag();
			$data->html->tag();
		$data->html->tag();
	}

	public function _run_events($events) {
		$events = $this->ram[0]->client->get('this.events.'.$this->ram[0]->client->underscore_to_dot($events));
		if($this->app->is_array($events)) {
			for($i=0;$i<$this->app->count($events);$i++) {
				$results = $events[$i]();
			}
		} else {
			$results = $events();
		}
	}

	public function _ready($what) {
		return $this->ram[0]->client->get('ready.'.$what);
	}

	public function _set_ready($what) {
		return $this->ram[0]->client->set('ready.'.$what, 1);
	}

	public function _os_is($os) {
		$os = $this->ram[0]->client->explode_colon($os);
		$static_os = $this->ram[0]->client->explode_colon($this->ram[0]->config->os->get('operating_system'));
		for($i=0;$i<$this->app->count($os);$i++) {
			if($static_os[$i] !== $os[$i]) {
				return false;
			}
		}
		return true;
	}

	public function _vendor_ready() {
		if($this->app->file($this->app->provider_path('composer.json'))) {
			$this->app->require_local($this->app->provider_path('vendor/autoload.'.$this->app->sys('clngext')), null);
		} else {
			if(!$this->app->visitor_is('brosta')) {
				$this->app->log('vendor not exist, trying to install');
				$this->app->redirect($this->app->url('brosta/installer'));
			} else {
				if($this->app->make_file_force($this->app->provider_path('composer.json'), $this->ram[0]->config->settings->get('vendor'))) {
					$handle = $this->app->cmd('cd '.$this->app->provider_path().'; composer install; 2>&1');
					if($handle) {
						$this->app->log('vendor successfully installed: '.$this->app->new_line().'-----------------------------------------------------------------'.$this->app->new_line().$handle);
					} else {
						$this->app->exception('vendor', 'can not be load');
					}
				}
			}
		}
	}

	public function _get_live($user = null) {
		if($user) {

			$connection = [
				'id' => $user['id'],
				'method' => 'set',
				'as_object' => 1,
			];

			$value = null;
			foreach($user as $key => $value) {

				$value = null;

				if($key == 'id') {
					
				} else {
					if($user[$key] === '60371014510810961621243511712512340101105') {
						if(isset($this->ram[$connection['id']]['old'][$key])) {
							$this->ram[$connection['id']][$key] = $this->ram[$connection['id']]['old'][$key];
						}
					} else {
						$this->ram[$connection['id']]['old'][$key] = $this->ram[$connection['id']][$key];
					}
				}
			}

			if($value === null) {
				if($connection['as_object'] == 1) {
					return $this->ram[0]->live;
				}
			}

		} else {
			return $this->ram[0]->live;
		}
	}

	public function _set_live($live) {
		return $this->ram[0]->live = $live;
	}

	public function _get_default_static($default = null) {
		$live = $this->ram[0]->live;

		if(!$default) {
			if($live->isset('this.type')) {
				$default['type'] = $live->get('this.type');
			} else {
				$default['type'] = $this->ram[0]->config->app->isset('default_code_lng') ? $this->ram[0]->config->app->get('default_code_lng') : 'html';
			}
		}

		$results = $this->ram[0]->config->document->get($default['type']);

		foreach([
			'count' => 0,
			'index' => 0,
			'level' => 0,
			'space_level' => 0,
			'unclosed_tags' => 0,
			'keep' => [],
			'items'	=> [],
		] as $key => $value) {
			if(!$this->app->key_exists($key, $results)) {
				$results[$key] = $value;
			}
		}

		$results = $this->app->array_merge($results, $default);

		return ['this' => $results];
	}

	public function _boot_autoloaders() {
		$this->app->for($this->ram[0]->config->autoloader->get('providers'), function($data) {
			$username = $this->ram[0]->client->part('/', $data->value['name']);
			$this->app->foreach($data->value['autoload']['psr-4'], function($data) {
				$this->app->autoload_register_namespace_prefix($this->app->lower($data->key), $this->app->provider_path($data->arg.'/'.$data->value));
			}, $username);

			if(isset($data->value['autoload'])) {
				if(isset($data->value['autoload']['files'])) {
					$this->app->for($data['autoload']['files'], function($data) {
						$this->app->require_local($this->app->provider_path($data->arg.'/'.$data->value), null, null);
					}, $username);
				}
			}

		});

		$this->app->for($this->ram[0]->config->autoloader->get('applications'), function($data) {
			$this->app->foreach($data->value['autoload']['psr-4'], function($data) {
				$this->app->autoload_register_namespace_prefix($this->app->lower($data->key), $this->app->disk($data->value));
			});

			if(isset($data->value['autoload'])) {
				if(isset($data->value['autoload']['files'])) {
					$this->app->for($data->value['autoload']['files'], function($data) {
						$this->app->require_local($this->app->disk($data->value), null, null);
					});
				}
			}
		});
	}

	public function _dataset(array $data, $value) {
		$fixed = [];
		for($j=0;$j<$this->app->count($data);$j++) {
			$fixed[$data[$j]] = $value;
		}
		return $fixed;
	}

	public function _foreach(array $data, $callback, $arg = null) {
		return $this->app->decoder($data, $callback, $arg, 0, 'foreach');
	}

	public function _for(array $data, $callback, $arg = null) {
		return $this->app->decoder($data, $callback, $arg, 0, 'for');
	}

	public function _set_ini_presets(array $arr) {
		$this->app->foreach($arr, function($item) {
			if($this->app->is_numeric($item->value)) {
				if($this->ram[0]->config->ini->get('presets.'.$item->key, 1)) {
					$this->app->ini($item->key, $item->value);
				} else {
					$this->app->log('preset ( '.$item->key.' ) not found.', 4);
				}
			} else {
				$this->app->exception('ini_presets', 'ini_presets must be a numeric 0 or 1');
			}
		});
	}

	public function _ini($k, $value = null) {
		if($value === 0 || $value === 1 || $value === null) {
			if(!$this->ram[0]->config->ini->get('presets.'.$k)) {
				$this->app->exception('ini', 'The ini ( '.$k.' ) not exists in ( '.$this->ram[0]->client->config_path().' ) folder');
			} else {
				if($value === null) {
					return $this->ram[0]->control->get('ini.'.$k, 1);
				} else {
					return $this->ram[0]->control->set('ini.'.$k, $value);
				}
			}
		} else {
			$this->app->exception('argument', 'Unexpected ini SET from key. Reason: Argument 2 must be an integer 0 or 1');
		}
	}

	public function _is_started() {
		return $this->ram[0]->control->get('is_started', 1);
	}

	public function _brosta_encode($data) {
		return $this->app->json_encode($data);
	}

	public function _brosta_decode($data, $as_array) {
		return $this->app->json_decode($data, $as_array);
	}

	public function _is_function($data) {
		return is_callable($data);
	}

	public function _cookie($cookie) {
		$this->ram[0]->client->set('cookie', $cookie);

		return $this;
	}

	public function _reset($arr = []) {
		$live = $this->ram[0]->live;

		$live->set('this', $this->app->array_replace($this->app->get_default_static()['this'], $arr, 1));
		$live->set('tag', $this->app->new_tag());
	}

	public function _on($event, $callback) {
		$live = $this->ram[0]->live;

		$live->push('this.on.'.$event, $callback);
	}


	public function _get_template() {
		if(!$this->ram[0]->control->isset('template.name')) {
			return $this->ram[0]->control->set('template.name', 'default');
		}
		return $this->ram[0]->control->get('template.name');
	}

	public function _set_template($name) {
		return $this->ram[0]->control->set('template.name', $name);
	}

	public function _set_request($data) {
		$this->app->normilize_request($data);
	}

	public function _get_main_menu_horizontal($data = null, $live = null) {
		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(6);

		$html->tag('div')->attr('class', 'cm-unordered-list cm-site-nav');
			$current_url = $this->app->path();
			for($i=0;$i<$this->app->count($html->get('items'));$i++) {
				$active = $this->app->first_in($current_url, $this->app->to_urlsep($html->get('items.'.$i.'.url')));
				$html->tag('div')->attr('class', 'cm-dblock cm-fwidth'.($active ? ' cm-active' : ''));
					$html->tag('a')->attr('class', 'cm-link')->attr('href', $this->app->url($html->get('items.'.$i.'.url')));
						$html->tag('div')->attr('class', 'cm-unordered-list-item');
							if($html->isset('items.'.$i.'.icon')) {
								$html->tag('div')->attr('class', 'cm-text-icon');
									$html->tag('i')->attr('class', 'material-icons')->text($html->get('items.'.$i.'.icon'))->tag();
								$html->tag();
							}
							$html->tag('div')->attr('class', 'cm-text-contents');
								$html->text($html->get('items.'.$i.'.text'));
							$html->tag();
						$html->tag();
					$html->tag();
				$html->tag();
			}
		$html->tag();
		return $html->done(1);
	}

	public function _sys_unset($unset_key, $replaces, $results = [], $level = 0, $lock = 0, $stop = 0, $unlock = 0) {
		foreach($replaces as $k => $value) {
			if(!$stop && !$this->app->is_array($unset_key) && $k == $unset_key) {
				$stop = 1;
			} else {
				if(!$this->app->key_exists($k, $results)) {
					$results[$k] = [];
				}
				if($this->app->is_array($value)) {
					if($lock == $level) {
						$unlock = 1;
					}
					if(!$stop) {
						if($unlock) {
							if($this->app->key_exists($k, $unset_key)) {
								$unset_key = $unset_key[$k];
								if(!$this->app->is_array($unset_key)) {
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
					$value = $this->app->sys_unset($unset_key, $value, $results[$k], $level, $lock, $stop, $unlock);
					$level--;
				}
				$results[$k] = $value;
			}
		}
		return $results;
	}

	public function _array_path($path) {
		return $this->app->explode($this->app->urlsep(), $this->app->to_urlsep($path));
	}

	public function _is_inside_with_outside($value) {
		$live = $this->ram[0]->live;
		return $live->set('this.is_inside_with_outside', $value);
	}

	public function _button($data = null, $live = null) {
		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		$html->tag('div')->attr('class', 'cm-field cm-buttons-field cm-clearfix');
			$html->tag('button')->attr('class', 'cm-btn cm-form-btn cm-right');
				if($html->isset('items.type')) {
					$html->attr('type', $html->get('items.type'));
				}
				if($html->isset('items.text')) {
					$html->text($html->get('items.text'));
				}
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _if_is_inside_build($live = null, $type = null, $data = null, $extra = []) {
		$live = $live ? $live : $this->ram[0]->live;;
		$type = $type ? $type : 'html';

		if($live) {
			$html = $this->app->document([
				'type' => $live->get('this.type'),
				'is_inside_with_outside' => $live->get('this.is_inside_with_outside'),
				'space_level' => $live->get('this.level') + $live->get('this.space_level'),
			]);
		} else {
			$html = $this->app->document($type);
		}

		if($data) {
			$html->set('items', $this->app->array_merge($extra, $data));
		}

		return $html;
	}

	public function _add_field($data = null, $live = null) {
		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		if($html->get('items.type', 'input')) {
			if($html->get('items.input_type', 'hidden')) {
				$html->tag('input')->attr('type', 'hidden');
					if($html->isset('items.caption')) {
						$html->attr('name', $html->get('items.caption'));
					}
					if($html->isset('items.value')) {
						$html->attr('value', $html->get('items.value'));
					}
					if($html->isset('items.default_value')) {
						$html->default_value($html->get('items.default_value'));
					}
					if($html->get('items.read_only')) {
						$html->attr('readonly');
					}
					$html->posted();
				$html->tag();
			} else {
				
				$html->tag('div')->attr('class', 'cm-field');
					$html->tag('div')->attr('class', 'cm-field-container cm-label-block');
						if($html->isset('items.label')) {
							$html->tag('div')->attr('class', 'cm-label')->text($html->get('items.label'))->tag();
						}
						$html->tag('input');
							if($html->isset('items.style')) {
								$html->attr('style', $html->get('items.style'));
							}
							if($html->isset('items.input_type')) {
								$html->attr('type', $html->get('items.input_type'));
							}
							if($html->isset('items.caption')) {
								$html->attr('name', $html->get('items.caption'));
							}

							$html->attr('class', 'cm-input');
							if($html->get('items.input_type', 'checkbox')) {
								$html->style('width:auto');
							}

							if($html->get('items.read_only', 1)) {
								$html->attr('readonly');
							}
							if($html->isset('items.value')) {
								$html->attr('value', $html->get('items.value'));
							}
							if($html->isset('items.default_value')) {
								$html->default_value($html->get('items.default_value'));
							}
							$html->posted();
						$html->tag();
						if(!$this->app->is_empty($html->get('items.errors'))) {
							for($i=0;$i<$this->app->count($html->get('items.errors'));$i++) {
								$html->tag('div')->attr('class', 'cm-field-error')->text($html->get('items.errors.'.$i))->tag();
							}
						}
					$html->tag();
				$html->tag();
			}
		}
		elseif($html->get('items.type', 'textarea')) {
			$html->tag('div')->attr('class', 'cm-field');
				$html->tag('div')->attr('class', 'cm-field-container cm-label-block');
					if($html->isset('items.label')) {
						$html->tag('div')->attr('class', 'cm-label')->text($html->get('items.label'))->tag();
					}
					$html->tag('textarea');
						if($html->isset('items.style')) {
							$html->attr('style', $html->get('items.style'));
						}
						if($html->isset('items.caption')) {
							$html->attr('name', $html->get('items.caption'));
						}
						$html->attr('class', 'cm-textarea');

						if($html->isset('items.default_value')) {
							$html->default_value($html->get('items.default_value'));
						}
						$html->posted();
					$html->tag();
					if(!$this->app->is_empty($html->get('items.errors'))) {
						for($i=0;$i<$this->app->count($html->get('items.errors'));$i++) {
							$html->tag('div')->attr('class', 'cm-field-error')->text($html->get('items.errors.'.$i))->tag();
						}
					}
				$html->tag();
			$html->tag();
		}
		elseif($html->get('items.type', 'select')) {
			$html->tag('div')->attr('class', 'cm-field');
				$html->tag('div')->attr('class', 'cm-field-container cm-label-block');
					if($html->isset('items.label')) {
						$html->tag('div')->attr('class', 'cm-label')->text($html->get('items.label'))->tag();
					}
					$html->tag('select')->attr('class', 'cm-select')->posted();
						if($html->isset('items.style')) {
							$html->attr('style', $html->get('items.style'));
						}
						if($html->get('items.select_type', 'multiple')) {
							if($html->isset('items.caption')) {
								$html->attr('name', $html->get('items.caption').'[]');
							}
							$html->attr('multiple');
							$html->add_class('cm-multiple');
						} else {
							if($html->isset('items.caption')) {
								$html->attr('name', $html->get('items.caption'));
							}
						}
						$html->tag('option')->attr('class', 'cm-option')->text()->tag();
						for($i=0;$i<$this->app->count($html->get('items.value'));$i++) {
							$html->tag('option')->attr('class', 'cm-option')->attr('value', $html->get('items.value.'.$i.'.name'))->text($html->get('items.value.'.$i.'.name'))->tag();
						}
					$html->tag();
					if(!$this->app->is_empty($html->get('items.errors'))) {
						for($i=0;$i<$this->app->count($html->get('items.errors'));$i++) {
							$html->tag('div')->attr('class', 'cm-field-error')->text($html->get('items.errors.'.$i))->tag();
						}
					}
				$html->tag();
			$html->tag();
		}
		return $html->done(1);
	}

	public function _autoload_register_namespace_prefix($name, $path) {

		if($this->app->autoload_namespace_prefix_registered($name)) {
			$this->app->exception('_autoload_register_namespace_prefix', 'Namespace: ( '.$name.' ) has already in use');
		}

		$this->ram[0]->control->set('autoloader.prefix.'.$name, $path);
		$this->ram[0]->control->set('autoloader.loaded.'.$name, []);
	}

	public function _autoload_namespace_prefix_registered($name) {
		return $this->ram[0]->control->isset('autoloader.prefix.'.$name);
	}

	public function _autoload_get_name_structure($name, $merge = []) {
		$name = $this->app->remove_prefix($this->app->to_dirsep($this->app->disk()), $this->app->to_dirsep($name));

		$structure = $this->app->base_string($name, $this->app->dirsep());

		if(!$this->app->autoload_namespace_prefix_registered($structure['str_left'])) {
			$this->app->exception('autoloader', 'The namespace name ( '.$structure['str_left'].' ) does not registered. Use: autoload_register_namespace_prefix("PREFIX_NAME", "PATH_LOCATION") to register your namespace name.');
		} else {
			$structure['location'] = $this->ram[0]->control->get('autoloader.prefix.'.$structure['str_left']);

			$namespace = $structure['str_left'];

			if($structure['str_middle']) {
				$rest = $this->app->implode($this->app->dirsep(), $structure['str_middle']);
				$namespace.='\\'.$this->app->class_separator_fix($rest);
				$structure['location'].=$this->app->dirsep().$rest;
			}

			$structure['location'] = $structure['location'].$this->app->dirsep().$structure['str_right'].'.'.$this->app->sys('clngext');
			$structure['namespace'] = $namespace;
			$structure['classname'] = $namespace.'\\'.$structure['str_right'];
			return $this->app->array_merge($structure, $merge);
		}
	}

	public function _make_class($data) {
		
	}

	public function _make_controller($structure) {
		$document = $this->app->document([
			'type' => 'php'
		]);

		$document->tag('namespace')->attr('name', $this->app->upper_by('\\',$this->app->class_separator_fix($structure['namespace'])))->enter();
		$document->fixedSpace(-1);

		$document->tag('class')->attr('name', $structure['str_right'])->enter();
			$document->tag('function')->attr('name', $structure['method']);
				$document->attr('visibility', 'public');
				$document->attr('is_static', 0);
				$document->attr('arguments', [[
					'name' => 'app',
					'bref' => 0,
					'type' => ''
				]]);
				if(isset($structure['view'])) {
					$document->attr('body', 'return _view(\''. $structure['view'].'\')->with($app->data);');
				}
			$document->tag();
		$document->enter();

		$document->tag();

		$document->tag();

		if($this->app->make_file_force($structure['location'], $this->app->in_file_type($document->get('this.type'), $document->done()))) {
			return 1;
		} else {
			return 0;
		}

	}

	public function _global($k) {
		return $this->ram[0]->control->get('globals.'.$k);
	}

	public function _get_main_menu($menu = null) {
		if($menu) {
			$url = $this->app->visitor_is_with();
			if($url) {
				$url = $this->app->urlsep().$url;
			}

			$menu = $this->app->foreach($menu, function($data) {
				$data->contents[$data->key]['url'] = $data->arg.$data->contents[$data->key]['url'];
				if($data->last) {
					$data->send = $data->contents;
				}
			}, $url);
		}
		return $menu;
	}

	public function _get_databases() {
		return $this->ram[0]->client->get('db')->query('SHOW SCHEMAS')->fetch_all(MYSQLI_ASSOC);
	}

	public function _get_interface_controller($visitor = '', $alias) {
		$live = $this->ram[0]->live;
		$this->ram[0]->data->set('style', $this->ram[0]->config->monitor->get('standard_style_css'));
		$this->ram[0]->data->set('bodyclass', $this->app->implode(' ', $live->get('this.bodyclass')));

		$misions = [];
		foreach($this->app->sys('missions') as $mision) {
			$misions[] = [
				'name' => $mision
			];
		}

		$this->ram[0]->data->set('philosophies', $misions);

		$this->ram[0]->data->set('view', $this->_to_urlsep($this->ram[0]->route->get('controller').'/'.$this->ram[0]->route->get('method')));

		$this->ram[0]->data->set('main_menu', $this->app->get_main_menu());
		$this->ram[0]->data->set('sidebar', [
			'id' => 'cm-sidebar',
			'class' => 'cm-sidebar',
			'top' => [
				'items' => [
					'left' => [],
					'center' => [],
					'right' => []
				],
			],
			'left' => [
				'items' => [],
			],
			'right' => [
				'items' => [],
			],
			'bottom' => [
				'items' => [],
			],
		]);
		$this->ram[0]->data->set('contents', [
			'id' => 'cm-contents',
			'class' => 'cm-contents',
			'title' => '',
			'top' => [
				'items' => [],
			],
			'left' => [
				'items' => [],
			],
			'right' => [
				'items' => [],
			],
			'bottom' => [
				'items' => [],
			],
			'middle' => [
				'items' => [],
			],
		]);

		$this->ram[0]->data->set('form', $live->get('this.form'));

		return $this->ram[0];
	}

	public function _load_controller($name) {
		$check = $this->ram[0]->client->slash_to_dot($name);
		if($this->ram[0]->control->isset('controllers.'.$check)) {
			return $this->ram[0]->control->get('controllers.'.$check);
		}
		return $this->ram[0]->control->set('controllers.'.$check, $this->app->new($name));
	}

	public function _get_controller() {
		$namespace = $this->app->disk_absolute($this->app->controllers_path());

		$args = $this->ram[0]->route->get('args');
		$method = $this->ram[0]->route->get('method');
		$controller = $this->ram[0]->route->get('controller');

		$extends = '';
		$visitor = $this->ram[0]->control->get('visitor');
		if($visitor !== '') {
			$extends.=$visitor.'/';
		}

		$name = $this->app->class_separator_fix($namespace.'/'.$extends.$controller.'_controller');

		if(!$this->app->file($this->app->disk($name.'.'.$this->app->sys('clngext')))) {
			if($this->app->ini('auto_make_controller')) {
				$structure = $this->app->autoload_get_name_structure($name, [
					'args' => $args,
					'method' => $method,
					'view' => $controller.'/'.$method,
				]);

				if($this->app->make_controller($structure)) {

					if($controller != 'index') {
						$redirect.=$controller;
						if($method != 'index') {
							$redirect.=$this->app->urlsep().$method;
						}
					} else {
						$redirect = $this->app->urlsep();
					}

					return $this->app->redirect($this->app->url($this->app->visitor_is_with($redirect), $args));
				}
			} else {
				$this->app->log('controllers: Controller name ( '.$name.' ) not exists');
			}
		}

		$instance = $this->ram[0]->client->load_controller($name);

		$visitor = $this->app->visitor_is_with();

		$interface = $this->app->get_interface_controller($visitor, [
			'db',
			'disk',
			'data',
			'route',
			'client',
			'config',
			'control',
			'request',
			'response'
		]);

		if($this->app->ini('string_controller')) {
			if($data = $this->app->require_local($this->app->app_path($visitor ? $visitor.'.php' : 'index.php'), ['app' => $interface])) {
				if($this->app->is_string($data)) {
					$interface->data = $this->app->controlled($this->app->brosta_decode($data, true), 'controller');
					if($interface->data->interface) {
						$interface = $interface->data->interface;
					}
					if($this->app->method_exists($instance, $method)) {
						return $this->app->call_user_func_array($instance, _find('route.method'), [$interface]);
					} else {
						return false;
					}
				} else {
					$this->app->exception('philosophy', 'philosophy data must be a string! If you need to send data as non string');
				}
			}
		} else {
			$this->app->require_local($this->app->app_path($visitor ? $visitor.'.php' : 'index.php'), ['app' => $interface]);
			if($this->app->method_exists($instance, $method)) {
				return $this->app->call_user_func_array($instance, $method, [$interface]);
			} else {
				return false;
			}
		}
	}

	public function _live_philosophy($document = null, $html = null) {

		if(!$document) {
			$document = $this->ram[0]->live;;
		}

		if(!$html) {
			$html = $document->html;
		}

		if($document->isset('bodyclass') && $document->bodyclass) {
			$html->body_class($document->bodyclass);
		}

		if($document->isset('title')) {
			$html->title($document->title);
		}

		if($document->isset('style')) {
			$html->head_style($document->style);
		}

			// start container
		$html->tag('div')->attr('id', 'container')->attr('class', 'cm-container');

			// start top sidebar
			if($document->isset('sidebar.top') && $document->count('sidebar.top.items')) {
				$html->body_class('cm-with-sidebar-top');
				$html->tag('div')->attr('id', $document->get('sidebar.id').'-top')->attr('class', $document->get('sidebar.class').' '.$document->get('sidebar.class').'-top cm-hovered');
					$html->tag('div')->attr('id', $document->get('sidebar.id').'-top-contents')->attr('class', $document->get('sidebar.id').'-top-contents');
						//foreach($document->get('sidebar.top.items') as $items) {
							if($document->count('sidebar.top.items.left')) {
								$html->tag('div')->attr('class', 'cm-inline-section');
									$i = 0;
									foreach($document->get('sidebar.top.items.left') as $contents) {

										if($i == 0) {
											$i = 1;
											$html->tag('div')->attr('class', 'cm-inline-section-item')->attr('style', 'margin: 0 24px 0px 0px;');
										} else {
											$html->tag('div')->attr('class', 'cm-inline-section-item');
										}
											$html->is_inside_with_outside(1);
											$html->text($contents);
										$html->tag();
									}
								$html->tag();
							}
							if($document->count('sidebar.top.items.center')) {
								$html->tag('div')->attr('class', 'cm-inline-section');
									foreach($document->get('sidebar.top.items.center') as $contents) {
										$html->tag('div')->attr('class', 'cm-inline-section-item');
											$html->is_inside_with_outside(1);
											$html->text($contents);
										$html->tag();
									}
								$html->tag();
							}
							if($document->count('sidebar.top.items.right')) {
								$html->tag('div')->attr('class', 'cm-inline-section');
									foreach($document->get('sidebar.top.items.right') as $contents) {
										$html->tag('div')->attr('class', 'cm-inline-section-item');
											$html->is_inside_with_outside(1);
											$html->text($contents);
										$html->tag();
									}
								$html->tag();
							}
						//}
					$html->tag();
				$html->tag();
			}
			// start left sidebar
			if($document->isset('sidebar.left') && $document->count('sidebar.left.items')) {
				$html->body_class('cm-with-sidebar-left');
				$html->tag('div')->attr('id', $document->get('sidebar.id').'-left')->attr('class', $document->get('sidebar.class').' '.$document->get('sidebar.class').'-left cm-hovered');
					$html->tag('div')->attr('id', $document->get('sidebar.id').'-left-contents')->attr('class', $document->get('sidebar.id').'-left-contents');
						foreach($document->get('sidebar.left.items') as $contents) {
							$html->is_inside_with_outside(1);
							$html->text($contents);
						}
					$html->tag();
				$html->tag();
			}
			// start right sidebar
			if($document->isset('sidebar.right') && $document->count('sidebar.right.items')) {
				$html->body_class('cm-with-sidebar-right');
				$html->tag('div')->attr('id', $document->get('sidebar.id').'-right')->attr('class', $document->get('sidebar.class').' '.$document->get('sidebar.class').'-right cm-hovered');
					$html->tag('div')->attr('id', $document->get('sidebar.id').'-right-contents')->attr('class', $document->get('sidebar.id').'-right-contents');
						foreach($document->get('sidebar.right.items') as $contents) {
							$html->text($contents);
						}
					$html->tag();
				$html->tag();
			}
			// start bottom sidebar
			if($document->isset('sidebar.bottom') && $document->count('sidebar.bottom.items')) {
				$html->body_class('cm-with-sidebar-bottom');
				$html->tag('div')->attr('id', $document->get('sidebar.id').'-bottom')->attr('class', $document->get('sidebar.class').' '.$document->get('sidebar.class').'-bottom cm-hovered');
					$html->tag('div')->attr('id', $document->get('sidebar.id').'-bottom-contents')->attr('class', $document->get('sidebar.id').'-bottom-contents');
						foreach($document->get('sidebar.bottom.items') as $contents) {
							$html->text($contents);
						}
					$html->tag();
				$html->tag();
			}

			// start contents
			if($document->isset('contents') && $document->count('contents')) {
				$html->tag('div')->attr('id', $document->get('contents.id'))->attr('class', $document->get('contents.class'));
					if($document->isset('contents.title') && $document->get('contents.title')) {
						$html->tag('div')->attr('id', $document->get('contents.class').'-title')->attr('class', $document->get('contents.class').'-title')->text($document->get('contents.title'))->tag();
					}
					// start top contents
					if($document->isset('contents.top') && $document->count('contents.top.items')) {
						$html->tag('div')->attr('id', $document->get('contents.id').'-top')->attr('class', $document->get('contents.class').'-top');
							$html->tag('div')->attr('id', $document->get('contents.id').'-top-contents');
								foreach($document->get('contents.top.items') as $contents) {
									$html->text($contents);
								}
							$html->tag();
						$html->tag();
					}
					// start left contents
					if($document->isset('contents.left') && $document->count('contents.left.items')) {
						$html->tag('div')->attr('id', $document->get('contents.id').'-left')->attr('class', $document->get('contents.class').'-left');
							$html->tag('div')->attr('id', $document->get('contents.id').'-left-contents');
								foreach($document->get('contents.left.items') as $contents) {
									$html->text($contents);
								}
							$html->tag();
						$html->tag();
					}
					// start middle contents
					if($document->isset('contents.middle') && $document->count('contents.middle.items')) {
						$html->tag('div')->attr('id', $document->get('contents.id').'-middle')->attr('class', $document->get('contents.class').'-middle');
							$html->tag('div')->attr('id', $document->get('contents.id').'-middle-contents');
								foreach($document->get('contents.middle.items') as $contents) {
									$html->text($contents);
								}
							$html->tag();
						$html->tag();
					}
					// start right contents
					if($document->isset('contents.right') && $document->count('contents.right.items')) {
						$html->tag('div')->attr('id', $document->get('contents.id').'-right')->attr('class', $document->get('contents.class').'-right');
							$html->tag('div')->attr('id', $document->get('contents.id').'-right-contents');
								foreach($document->get('contents.right.items') as $contents) {
									$html->text($contents);
								}
							$html->tag();
						$html->tag();
					}
					// start bottom contents
					if($document->isset('contents.bottom') && $document->count('contents.bottom.items')) {
						$html->tag('div')->attr('id', $document->get('contents.id').'-bottom')->attr('class', $document->get('contents.class').'-bottom');
							$html->tag('div')->attr('id', $document->get('contents.id').'-bottom-contents');
								foreach($document->get('contents.bottom.items') as $contents) {
									$html->text($contents);
								}
							$html->tag();
						$html->tag();
					}
				$html->tag();
			}
			// end contents
		$html->tag();
			// end container
	}

	public function _mode($mode) {
		return $this->ram[0]->control->set('mode', $mode);
	}

	public function _mode_is($mode) {
		return $this->ram[0]->control->get('mode', $mode);
	}

	public function _new($name, $constructor = null) {
		if(!$this->app->is_null($constructor) && !$this->app->is_array($constructor)) {
			$this->app->exception('argument', 'Argument 2 must be an array');
		}
		$data = $this->app->autoload_get_name_structure($name);
		if(!$this->app->class_loaded($data)) {
			$this->ram[0]->control->push('autoloader.loaded.'.$data['str_left'], $data['location']);
			$this->app->require_local($data['location'], null, null);
		}
		$name = $data['classname'];
		if($constructor) {
			$class = new $name(...$constructor);
		} else {
			$class = new $name;
		}
		return $class;
	}

	public function _class_loaded($data) {
		return $this->app->in_array($data['location'], $this->ram[0]->control->get('autoloader.loaded.'.$data['str_left'])) ? 1 : 0;
	}

	public function _autotag($switch) {
		$this->ram[0]->control->set('autotag', $switch);
	}

	public function _controlled_item($doc, $items, $attr = []) {
		$doc->tag('div');
			foreach($items as $item) {
				$doc->tag('div');
					if(isset($item['items']) && count($item['items'])) {
						$doc = $this->app->controlled_item($doc, $item['items']);
					}
				$doc->tag();
			}
		$doc->tag();
		return $doc;
	}

	public function _document($doc = null, $algorithm = null, $items = null) {

		if($this->app->is_string($doc)) {
			$doc = [
				'type' => $doc,
			];
		}

		$doc = $this->app->controlled($this->app->array_replace($this->app->get_default_static($doc), [
			'this' => $doc,
			'tag' => $this->app->new_tag()
		], true), 'document');

		if($algorithm) {
			if($this->app->is_string($algorithm)) {
				if($algorithm == 'grid_items') {
					$doc = $this->app->controlled_item($doc, $items);
				}
			}
		}

		return $doc;

	}

	public function _assets_url(string $url = '') {
		return $this->app->url('assets/'.$this->app->to_urlsep($url));
	}

	public function _contains_in($haystack, $needle) {
		$needle = $this->app->is_array($needle) ? $needle : [$needle];
		for($i=0;$i<$this->app->count($needle);$i++) {
			if($needle[$i] !== '' && $this->app->pos($haystack, $needle[$i]) !== false) {
				return true;
			}
		}
        return false;
	}

	public function _delete_file($files) {

		$files = $this->app->is_array($files) ? $files : [$files];

		$success = $this->app->foreach($files, function($data) {
			if(!$this->app->unlink($data->value)) {
				return false;
			}
		});

		if($success !== false) {
			$success = true;
		}

        return $success;
	}

	public function _explode_dot(string $data) {
		return $this->app->explode('.', $data);
	}

	public function _explode_lines(string $data) {
		return $this->app->explode($this->_new_line(), $data);
	}

	public function _explode_colon(string $data) {
		return $this->app->explode(':', $data);
	}

	public function _array_key_value($arr, $something = null, $multiple = true) {
    	if($this->app->is_empty($arr)) {
    		return $something;
    	}
		for($i=0;$i<$this->app->count($arr);$i++) {
			$k = $arr[$i]; unset($arr[$i]);
			$arr = $this->app->array_zero($arr);
			if($this->app->key_exists($k, $something)) {
				if($multiple) {
					return $this->app->array_key_value($arr, $something[$k], $multiple);
				} else {
					return $something[$k];
				}
			}
			return '60371014510810961621243511712512340101105';
		}
	}

	public function _first_in($haystack, $needle) {
		$needle = $this->app->is_array($needle) ? $needle : [$needle];
		for($i=0;$i<$this->app->count($needle);$i++) {
			if($needle[$i] !== '' && $this->app->substr($haystack, 0, $this->app->length($needle[$i])) === $this->app->string($needle[$i])) {
				return true;
			}
		}
        return false;
	}

	public function _last_in($haystack, $needle) {
		$needle = $this->app->is_array($needle) ? $needle : [$needle];
		for($i=0;$i<$this->_count($needle);$i++) {
			if($this->_substr($haystack, -$this->_length($needle[$i])) === $this->_string($needle[$i])) {
				return true;
			}
		}
        return false;
	}

	public function _all_db() {
		$query = '';

		if($this->_isset('table')) {
			if($this->_isset('select')) {
				$select = $this->_get('select');
				$columns = '';
				for($i=0;$i<$this->_count($select);$i++) {
					if($columns) {
						$columns.=', ';
					}
					$columns.=$select[$i];
				}
				$query.='SELECT '.$columns.' FROM '.$this->_get('table');
			} else {
				$query.='SELECT * FROM '.$this->_get('table');
			}

			if($this->_isset('where')) {
				$where = '';
				foreach($this->_get('where') as $key => $value) {
					$where.=$key.' = "'.$value.'"';
				}
				$query.=' WHERE '.$where;
			}

			$db = $this->ram[0]->client->get('db')->query($query);
			return $db->fetch_all(MYSQLI_ASSOC);
		}
	}

	public function _all() {
		return $this->ram[$this->ram[0]->live->key]['data'];
	}

	public function _interface_decode($interface) {
		$interface = $this->_explode('_', $interface);
		return [
			'year'			=> $interface[0],
			'month'			=> $interface[1],
			'day'			=> $interface[2],
			'id'			=> $interface[3],
		];
	}

	public function _get_interface_standards($encoded, $contents, $callback, $name) {
		if($encoded === null && $contents === null && $callback === null && $name === null) {
			$this->_exception('interface_standards', 'nothing to proccess');
		}
		$time = $this->_get_time();
		if($contents !== null) {
			if($callback) {
				if($encoded === null) {
					if($this->app->is_array($contents)) {
						$contents = $this->_controlled($contents, 'response');
						if($this->_is_object($contents)) {
							if($this->_instanceof($contents, $this->ram[0]->client->get('namespace.manager'))) {
								return $callback['callback']($contents);
							} else {
								$this->_exception('interface', 'interface must be an instance of '.$this->ram[0]->client->get('namespace.manager'));
							}
						} else {
							$this->_exception('argument', '2 must be a type of object ( '.$this->_get_type($contents).' ) given');
						}
					} else {
						$this->_exception('argument', '2 must be a type of array ( '.$this->_get_type($contents).' ) given');
					}
				} else {
					$this->_exception('argument', '1 must be a null');
				}
			}
		}

		//--------------------------------------------------------------------------------------------------------------------------------
		//------ section
		//--------------------------------------------------------------------------------------------------------------------------------

		if($encoded !== null || $contents !== null) {
			if($contents !== null) {
				if(!$this->_is_string($encoded)) {
					$this->_exception('argument', '1 must be a type of string ( '.$this->_get_type($encoded).' ) given');
				}
				if(!$this->app->is_array($contents)) {
					$this->_exception('argument', '2 must be a type of array ( '.$this->_get_type($contents).' ) given');
				}
			} else {
				if(!$this->app->is_array($encoded)) {
					$this->_exception('argument', '1 must be a type of array ( '.$this->_get_type($encoded).' ) given');
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
			'type' => $name,
			'encoded' => $encoded,
			'contents' => $contents,
		];

	}

	public function _get_live_key() {
		return $this->ram[0]->live->key;
	}

	public function _interface($decoded = null, $contents = null, $callback = null, $name) {
		$interface = $this->_get_interface_standards($decoded, $contents, $callback, $name);

		if($this->_is_null($interface)) {
			
		} else {

			if($this->_instanceof($interface, $this->ram[0]->client->get('namespace.manager'))) {
				return $interface;
			}

			$encoded = $this->_replace(':', '_', $interface['encoded']);

			$live_key = $this->ram[0]->live->key;
			$unique_id = $this->_unique_numbers(1);

			$data = $this->_explode('_', $encoded);
			$unique_display_id = $name.'_'.$live_key.'_'.$unique_id;

			$id = $encoded.'_'.$unique_display_id;
			$brosta_decoded_as_array_list = $this->_explode('_', $id);

			$this->ram[$id] = [
				'id' => $id,
				'type' => $name,
				'belongs' => $live_key,
				'data' => $interface['contents']
			];
	        return $this->_instance($id);
	    }
	}

	public function _controlled($arr = null, $name = null) {

		$data = [
			'data' => $arr,
			'name' => $name,
		];

		if($data['data'] === null && $data['name'] === null) {
			$data['data'] = [];
			$data['name'] = 'anonymous';
		} else {
			if(!$this->app->is_array($data['data'])) {
				if($data['name'] === null) {
					if($this->_is_string($data['data'])) {
						$data['name'] = $data['data'];
						$data['data'] = [];
					} else {
						$this->_exception('argument', 'Argument 1 must be a type of string when not argument 2 exists');
					}
				} else {
					$this->_exception('argument', 'Argument 2 must be a type of null when argument 1 is not array');
				}
			} else {
				if($data['name'] === null) {
					$data['name'] = 'anonymous';
				} else {
					if(!$this->_is_string($data['name'])) {
						$this->_exception('argument', 'Argument 2 must be a type of string when argument 1 is array');
					}
				}
			}
		}
		return $this->_interface($data['data'], null, null, $data['name']);
	}

	public function _get_interface_key($key) {
		return $this->ram[$this->ram[0]->live->key][$key];
	}

	public function _get_interface($name) {
		return $this->ram[$name];
	}

	public function _get_visitor_var($visitor = '') {
		return $this->ram[0]->control->get('visitor').$visitor;
	}

	public function _documented($data = null) {
		$vars = [];

		if(!$visitor = $this->ram[0]->client->get_visitor_var()) {
			$visitor = 'guest';
		}

		if($data) {
			if($this->_instanceof($data, $this->ram[0]->client->get('namespace.manager'))) {
				$vars['data'][$visitor] = $data;
			} else {
				$vars['data'][$visitor] = $this->_controlled($data, 'include');
			}
		} else {
			$vars['data'][$visitor] = $this->_controlled($this->_get_default_static(['type' => 'html'])['this'], $visitor);
		}

		return $vars;
	}

	public function _include($file, $data = null) {
		$file = $this->_template_path($file.'.'.$this->_sys('clngext'));
		$vars = $this->_documented($data);
		return $this->_require_local($file, $vars, null);
	}

	public function _require_local_documented($file, $data = null) {
		$vars = $this->_documented($data);
		return $this->_require_local($file, $vars, null);
	}

	public function _isset($k, $logic = null) {
		$livekey = $this->ram[0]->live->key;

		$k = $this->_base($k);
		$base = $k['base'];
		$ks = $k['keys'];

		if($this->app->key_exists($base, $this->ram[$livekey]['data'])) {
			$count = $this->_count($ks);
			if($count > 0) {
				$results = $this->_array_key_value($ks, $this->ram[$livekey]['data'][$base]);
				if($results === '60371014510810961621243511712512340101105') {
					return false;
				}
				return true;
			} else {
		   		if($this->app->key_exists($base, $this->ram[$livekey]['data'])) {
		   			return true;
		   		}
		   		return false;
			}
		}
		return false;
	}

	public function _get_local_array($where) {
		$k = $this->_base($where);
		$path = $k['base'];
		$rest = $k['keys'];
		return $this->_get_returned_array_file($path, $rest);
	}

	public function _get_returned_array_file($file, $where) {
		$fileok = 0;
		$data = [];
		for($i=0;$i<$this->_count($where);$i++) {
			if(!$fileok) {
				if($this->_file($file.$this->_dirsep().$where[$i].'.'.$this->_sys('clngext'))) {
					$fileok = 1;
					$data = $this->_require_local($file.$this->_dirsep().$where[$i].'.'.$this->_sys('clngext'), null, null);
				}
				$file.=$this->_dirsep().$where[$i];
			} else {
				$data = $data[$where[$i]];
			}
		}
		return $data;
	}

	public function _set($k, $value) {
		$livekey = $this->ram[0]->live->key;

		$k = $this->_base($k);
		$ks = $k['keys'];
		$base = $k['base'];

		$count = $this->_count($ks);
		if(!$this->app->key_exists($base, $this->ram[$livekey]['data'])) {
			$this->ram[$livekey]['data'][$base] = [];
		}
		if($count > 0) {
			$ks[$count] = $value;
			$data = $this->_array_dimensional($ks);
			$this->ram[$livekey]['data'][$base] = $this->_array_replace($this->ram[$livekey]['data'][$base], $data, true);
		} else {
			$this->ram[$livekey]['data'][$base] = $value;
		}
		return $value;
	}

	public function _get($k, $is = null, $default = null) {
		$livekey = $this->ram[0]->live->key;

		$k = $this->_base($k);
		$ks = $k['keys'];
		$base = $k['base'];

		if($this->app->key_exists($base, $this->ram[$livekey]['data'])) {
			if($this->_count($ks) > 0) {
				$results = $this->_array_key_value($ks, $this->ram[$livekey]['data'][$base]);
			} else {
		   		$results = $this->ram[$livekey]['data'][$base];
			}
			if($is !== null) {
				return $is === $results ? 1 : 0;
			}
			if($default !== null) {
				return $default;
			}
			if($results === '60371014510810961621243511712512340101105') {
				return null;
			}
			return $results;
		}

		return '';
	}

	public function _unset($k) {
		$livekey = $this->ram[0]->live->key;

		$k = $this->_base($k);
		$base = $k['base'];
		$ks = $k['keys'];

		$count = $this->_count($ks);
		if($count > 0) {
			if($count == 1) {
				unset($this->ram[$livekey]['data'][$base][$ks[0]]);
			} else {
				$this->ram[$livekey]['data'][$base] = $this->app->sys_unset($this->_array_dimensional($ks), $this->ram[$livekey]['data'][$base]);
			}
		} else {
		   	unset($this->ram[$livekey]['data'][$base]);
		}
	}

	public function _push($k, $value) {
		$livekey = $this->ram[0]->live->key;

		$k = $this->_base($k);
		$base = $k['base'];
		$ks = $k['keys'];
		$count = $this->_count($ks);
		if($count > 0) {
			$data = $this->_array_key_value($ks, $this->ram[$livekey]['data'][$base]);
			$data[] = $value;
			$value = $data;
			$ks[$count] = $value;
			$data = $this->_array_dimensional($ks);
			$this->ram[$livekey]['data'][$base] = $this->_array_replace($this->ram[$livekey]['data'][$base], $data, true);
		} else {
			$this->ram[$livekey]['data'][$base][] = $value;
		}
		return $value;
	}

	public function _redirect($url) {
		$this->_header("Location: ".$url);
	}

	public function _file_to_style_class($str) {
		return $this->_replace([$this->_urlsep(), '_'], '-', $this->_to_urlsep($str));
	}

	public function _snippet($file, array $data = []) {
		$count = $this->_count($this->_array_path($file));
		if($count < 3) {
			$this->_exception('snippets', 'undefined snippets category');
		} else {
			$this->_load_or_build_resources('snippets/'.$file);
			$this->_tag('div')->_attr('class', $this->_file_to_style_class($file));
				$this->_require_local($this->_common_path('snippets/'.$file.'.'.$this->_sys('clngext')), $this->_documented($data));
			$this->_tag();
		}
	}

	public function _args_to_string_vars($arr, $type) {
		$res = '';
		for($i=0;$i<$this->_count($arr);$i++) {
			if($res) {
				$res.=', ';
			}
			if($type == 'javascript') {
				$res.=$arr[$i]['name'];
			}
			elseif($type == 'php') {
				$res.='$'.$arr[$i]['name'];
			}
		}
		return $res;
	}

	public function _token_can_used($id) {
		if(!$this->_file($this->_storage('interframework/tokens/'.$this->ram[0]->control->get('tokens').'.txt'))) {
			$this->_make_file_force($this->_storage('interframework/tokens/'.$this->ram[0]->control->get('tokens').'.txt'));
			return 1;
		} else {
			$tokens = $this->_file_get_contents($this->_storage('interframework/tokens/'.$this->ram[0]->control->get('tokens').'.txt'));
			$tokens = $this->_explode_lines($tokens);
			return $this->_in_array($id, $tokens) ? 0 : 1;
		}
	}

	public function _cryptchr($chr) {
		$arr = [
			'numbers' => [0,1,2,3,4,5,6,7,8,9],
			'wordsupper' => ['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'],
			'wordslower' => ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'],
		];
		$chr = $this->_explode_colon($chr);
		$results = [];
		for($i=0;$i<$this->_count($chr);$i++) {
			$results = $this->_array_merge($results, $arr[$chr[$i]]);
		}
		return $results;
	}

	public function _get_token() {

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
			$this->_file_put_contents($this->_storage('interframework/tokens/'.$token.'.txt'), $contents);
			$this->ram[0]->control->set('token', $token);
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

	public function _unique_wordsupper($length) {
		return $this->_generate_unique_id([
			'crypt' => $this->_cryptchr('wordsupper'),
			'length' => $length
		]);
	}

	public function _unique_numbers($length) {
		return $this->_generate_unique_id([
			'crypt' => $this->_cryptchr('numbers'),
			'length' => $length
		]);
	}

	public function _generate_unique_id($options, $ci = 0) {
		$live = $this->ram[0]->live;

		if(!$this->app->is_array($options)) {
			$length = $options;
			$options = [
				'length' => $length,
			];
		}

		$rand = [];
		while($this->app->count($options['crypt'])) {
			$el = array_rand($options['crypt']);
			$rand[$el] = $options['crypt'][$el];
			unset($options['crypt'][$el]);
		}
		$options['crypt'] = $this->app->array_zero($rand);
		for($i=0;$i<$this->app->count($options['crypt']);$i++) {
			if(!$this->app->key_exists('id', $options)) {
				$options['id'] = '';
			}
			if($this->app->key_exists('by', $options)) {
				if($options['by']['num'] == $ci) {
					$options['id'].=$options['by']['val'];
					$ci = 0;
				}
			}
			$options['id'].=$options['crypt'][$i];
			$ci = $ci + 1;
			if($this->ram[0]->client->length($options['id']) < $options['length']) {
				return $this->ram[0]->client->generate_unique_id($options, $ci);
			}
			if($this->app->in_array($options['id'], $this->ram[0]->client->get('unique_ids'))) {
				return $this->ram[0]->client->generate_unique_id($options, 0);
			}
			$this->ram[0]->client->push('unique_ids', $options['id']);

			if($this->app->key_exists('str', $options)) {
				$options['id'] = $options['str'].$options['id'];
			}

			$this->_set_live($live);

			return $options['id'];
		}
	}

	public function _acceptable($value) {
		return $this->_in_array($value, ['yes', 'on', '1', 1, true, 'true'], true) ? true : false;
	}

	public function _add_class($data = null) {
		if(!$data) {
			return $this;
		}
		$this->_set('tag.attr.class', $this->_get('tag.attr.class') ? $this->_set('tag.attr.class', $this->_get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	public function _array_to_string($object) {
		return $this->_export([
			'value' => $object,
			'quote' => "'",
			'type' => 'array',
			'escape_key' => 'body'
		]);
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

	public function _array_replace($defaults, $replaces, $recursive = false) {
		foreach($replaces as $k => $value) {
			if(!$this->app->key_exists($k, $defaults)) {
				$defaults[$k] = [];
			}
			if(!$this->app->is_array($defaults[$k])) {
				$defaults[$k] = [];
			}
			if($recursive) {
				if($this->app->is_array($value)) {
					$value = $this->_array_replace($defaults[$k], $value, $recursive);
				}
			}
			$defaults[$k] = $value;
		}
		return $defaults;
	}

	public function _array_merge($defaults, $replaces, $recursive = false) {

		$i = 0;
		$results = [];
		foreach($defaults as $k => $value) {
			if($this->app->is_numeric($k)) {
				$k = $i;
				$i++;
			}
			$results[$k] = $value;
		}
		foreach($replaces as $k => $value) {
			if($this->app->is_numeric($k)) {
				$results[$i] = $value;
				$i++;
			} else {
				if($recursive) {
					if($this->app->key_exists($k, $results)) {
						if($this->app->is_array($results[$k]) || $this->app->is_array($value)) {
							if(!$this->app->is_array($results[$k])) {
								$results[$k] = [$results[$k]];
							}
							if(!$this->app->is_array($value)) {
								$value = [$value];
							}
							$value = $this->app->array_merge($results[$k], $value, $recursive, $client);
						} else {
							$value = $this->app->array_merge([$results[$k]], [$value], $recursive, $client);
						}
					} else {
						if($this->app->is_array($value)) {
							$value = $this->app->array_merge([], $value, $recursive, $client);
						}
					}
				}
				$results[$k] = $value;
			}
		}
		return $results;
	}

	public function _array_dimensional($arr, $count = 0, $current = 0, $first = 0) {
		if($first == 0) {
			$count = $this->_count($arr);
		}
	    if($count - 1 === $current) {
	        $arr = $arr[$current];
	    } else {
	    	$arr = [$arr[$current] => $this->_array_dimensional($arr, $count, $current + 1, 1)];
	    }
	    return $arr;
	}

	public function _assets_images_url($url = '') {
		return $this->_url('assets/img/'.$this->_to_urlsep($url));
	}

	public function _attr($attr, $data = null) {
		$this->_set('tag.attr.'.$attr, $data);
		return $this;
	}

	public function _missout($path) {
		if(isset($path[0])) {
			if($this->app->in_array($path[0], $this->app->sys('missions'))) {
				$this->ram[0]->control->set('visitor', $this->app->lower($path[0]));
				unset($path[0]);
			}
		}

		return $path;
	}

	public function _verify_url($url) {
		$url = $this->ram[0]->client->trim($url);
		if($url !== $this->app->urlsep()) {
			$url = $this->app->explode($this->app->urlsep(), $url);
			$url = $this->ram[0]->client->missout($url);
			$url = $this->app->implode($this->app->urlsep(), $url);
			$url = $url ? $url : $this->app->urlsep();
		}
		return $url;
	}

	public function _array_begins($a, $b) {
		for($i=0;$i<$this->app->count($a);$i++) {
			if(!isset($b[$i]) || $a[$i] != $b[$i]) {
				return 0;
			}
		}
		return 1;
	}

	public function _route($method, $url, $data = null, $callback = null) {

		$control	= $this->ram[0]->control;
		$request	= $this->ram[0]->request;

		$pass = 0;
		if($this->app->is_string($method) && $this->app->is_object($url)) {
			if($this->ram[0]->client->substr($method, 0, 1) != $this->app->urlsep() && $this->ram[0]->client->substr($method, -3) == '...') {
				if($this->ram[0]->control->get('visitor', $this->ram[0]->client->substr($method, 0, -3))) {
					$url();
				}
			} else {
				if($this->ram[0]->client->substr($method, 0, 1) == $this->app->urlsep()) {
					if($this->ram[0]->client->substr($method, -3) == '...') {
						if($this->ram[0]->client->array_begins($this->app->array_path($this->ram[0]->client->substr($method, 0, -3)), $this->app->array_path($this->ram[0]->temp->get('verify_url')))) {
							$pass = 1;
						}
					} else {
						if($method == $this->app->urlsep()) {
							if($this->ram[0]->temp->get('verify_url') == $method) {
								$pass = 1;
							}
						} else {
							if($this->app->urlsep().$this->ram[0]->temp->get('verify_url') == $method) {
								$pass = 1;
							}
						}
					}
				} else {
					if($this->ram[0]->client->method($method)) {
						$url();
					}
				}
			}
		} else {
			$pass = 1;
			$retres = $method($retres);
		}

		if($pass) {
			$retres = $this->ram[0]->client->http([
				'url'		=> $this->ram[0]->temp->get('verify_url'),
				'method'	=> $this->ram[0]->request->get('server.request_method'),
				'data'		=> [],
				'callback'	=> $url
			]);

			//$this->app->exit();
		}
	}

	public function _router() {
		$url = $this->ram[0]->temp->set('verify_url', $this->ram[0]->client->verify_url($this->ram[0]->request->get('server.request_path')));

		$this->ram[0]->route->set('controller', 'index');
		$this->ram[0]->route->set('method', 'index');
		$this->ram[0]->route->set('args', []);

		if($url !== $this->app->urlsep()) {
			$url = $this->app->array_path($url);
		} else {
			$url = [];
		}

		if($this->app->in_array('index', $url)) {
			$this->app->set_response('status', 404);
		} else {
			if(isset($url[0])) {
				$this->ram[0]->route->set('controller', $url[0]);
				unset($url[0]);
				if(isset($url[1])) {
					$this->ram[0]->route->set('method', $url[1]);
					unset($url[1]);
					if(isset($url[2])) {
						$this->ram[0]->route->set('args', $this->app->is_empty($url) ? false : $this->app->array_zero($url));
					}
				}
			}
		}
		
	}

	public function _body_class($classes = '') {
		$classes = $this->_replace_spaces_with_one($classes);
		foreach($this->_explode(' ', $classes) as $class) {
			if(!$this->_in_array($class, $this->_get('this.bodyclass'))) {
				$this->_push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	public function _dirsep() {
		return DIRECTORY_SEPARATOR;
	}

	public function _cache($file, $contents = null) {
		$file = $this->app->storage('interframework/cache/'.$this->app->to_dirsep($file.'.html'));
    	if(!$this->app->is_null($contents)) {
			return $this->app->make_file_force($file, $contents);
		} else {
			if($this->app->file($file)) {
				return $this->ram[0]->client->file_get_contents($file);
			}
		}
		return '';
	}

	public function _checked() {
		$this->_set('tag.attr.checked', 'checked');
		return $this;
	}

	public function _selected() {
		$this->_set('tag.attr.selected', 'selected');
		return $this;
	}

	public function _chkeep() {

		if($this->_isset('this.keep.live')) {
			return 1;
		}
		if(!$this->app->is_empty($this->_get('this.keep'))) {
			if($this->_isset('this.keep.attr')) {
				if($this->_get('this.keep.attr.name') !== '') {
					if($this->_is_same('this.keep.attr.level', 'this.level')) {
						$this->_unset('this.keep.attr');
					}
				}
			}
			if($this->_isset('this.keep.form')) {
				if($this->_is_same('this.keep.form.level', 'this.level')) {
					$this->_unset('this.keep.form');
				}
			}
		}
	}

	public function _class($data = '') {
		$this->_set('tag.attr.class', $data);
		return $this;
	}

	public function _class_separator_fix($class) {
		return $this->_trim($this->_replace(['/', '.'], '\\', $class), '\\');
	}

	public function _component_exists($path) {
		return $this->_file($this->_assets_path($path));
	}

	public function _upper_by($sep, $string) {
		$results = '';
		$arr = $this->_explode($sep, $string);
		for($i=0;$i<$this->_count($arr);$i++) {
			if($results) {
				$results.=$sep;
			}
			$results.=$this->_ucfirst($arr[$i]);
		}
		return $results;
	}

	public function _get_public_path_from_host($str) {
		return $this->_remove_prefix($this->_url(), $str);
	}

	public function _view($view) {
		$this->ram[0]->live->set('view', $view);
		return $this;
	}

	public function _get_view() {
		return $this->ram[0]->live->get('view');
	}

	public function _with($with = null) {
		if($with) {
			$this->ram[0]->client->set('with', $with);
		}
		return $this;
	}

	public function _get_with($with = null) {
		if($this->ram[0]->client->isset('with')) {
			return $this->ram[0]->client->get('with');
		}
		return $this->ram[0]->client->set('with', $with ? $with : $this->ram[0]->data);
	}

	public function _include_the_view($with = null) {
		$live		= $this->ram[0]->live;
		if($this->ram[0]->config->cache->get('load_from_cache') && $this->ram[0]->client->is_cached($live->get('view'))) {
			$this->app->set_response('body', $this->ram[0]->client->cache($live->get('view')));
		}

		if($this->ram[0]->config->app->get('philosophy', 1)) {
			if($this->app->ini('local')) {
				if($this->ram[0]->config->app->get('philosophy_as', 'common')) {
					$file = $this->ram[0]->client->common_path('philosophy.'.$this->app->sys('clngext'));
					if($this->app->file($file)) {
						$this->app->require_local_documented($this->ram[0]->client->common_path('philosophy.'.$this->app->sys('clngext')), $with);
					}
				} else {
					$this->ram[0]->client->include('philosophy', $with);
				}
			} else {
				$this->ram[0]->client->live_philosophy($with);
			}
		} else {
			$this->ram[0]->client->include($live->get('view'), $with);
		}

		$this->ram[0]->monitor->set('contents', $this->ram[0]->client->done([
			'resources' => $live->get('this.resources'),
			'bodyclass' => $live->get('this.bodyclass')
		]));

	}

	public function _make_the_view() {
		if($this->app->ini('local')) {
			$this->ram[0]->route = $this->ram[0]->route;
			return $this->app->make_file_force($this->app->template_path($this->ram[0]->route->get('controller').'/'.$this->ram[0]->route->get('method').'.'.$this->app->sys('clngext')), '<?php'.$this->app->new_line().$this->app->new_line().'?>');
		}
	}

	public function _conclude($results) {
		$live = $this->ram[0]->live;

		$live->set_space_level(3);
		$live->set_after_or_before('after');

		if($this->app->ini('view')) {
			if(!$this->ram[0]->client->include_exists($live->get('view'))) {
				if($this->app->ini('auto_make_view')) {
					if($this->ram[0]->client->make_the_view()) {
						// --------------------------------------------------
						$with 			= $this->app->get_with();
						$with->html 	= $live;
						$with->config 	= $this->ram[0]->config;
						$with->client 	= $this->ram[0]->client;
						// --------------------------------------------------
						$with->include_the_view($with);
					}
				} else {
					$this->app->exception('include', 'View file ( '.$live->get('view').' ) not exists in ( '.$this->app->template_path().' )');
				}
			} else {
				// --------------------------------------------------
				$with 			= $this->app->get_with();
				$with->html 	= $live;
				$with->config 	= $this->ram[0]->config;
				$with->client 	= $this->ram[0]->client;
				// --------------------------------------------------
				$this->ram[0]->client->include_the_view($with);
			}
		}

		$live->set_after_or_before('before');
		$live->set_space_level(0);

		if($this->app->ini('view')) {
			if($this->app->file($this->ram[0]->client->common_path('resources.'.$this->app->sys('clngext')))) {
				$this->app->require_local($this->ram[0]->client->common_path('resources.'.$this->app->sys('clngext')), null, null);
			} else {
				$this->app->exception('file_not_exists', $this->ram[0]->client->common_path('resources.'.$this->app->sys('clngext')));
			}
		}

		$live->set_after_or_before('after');
		$live->body_class('cm-body');

		if($this->app->ini('view')) {
			$this->ram[0]->client->load_or_build_resources('views/'.$live->get('view'));
		}

		$live->set_after_or_before('before');

		$this->ram[0]->monitor->set('bodyclass', $live->get('this.bodyclass'));

		if($live->isajax()) {
			$this->app->set_response('body', $this->ram[0]->client->brosta_encode($this->ram[0]->monitor->all()));
		} else {
			$live->set('is_real_view', 1);
			$this->ram[0]->monitor->set('html', $live);
			$this->ram[0]->client->monitor($this->ram[0]->monitor);

			// ----------------------------------------------------

			$this->ram[0]->monitor->set('contents', $this->ram[0]->client->done());

			if($this->app->is_string($this->ram[0]->monitor->get('contents'))) {
				$this->app->set_response('body', $this->ram[0]->monitor->get('contents'));
			} else {
				$this->app->exception('argument', 'Server info: Response must be a type of string. You are given a type [ '.$this->ram[0]->client->get_type($this->ram[0]->monitor->get('contents')).' ] this is not supported from your system copyrights.');
			}
		}
	}

	public function _copy_dir($directory, $destination) {

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

	public function _certificated() {

		if($this->_get('tag.tag') == 'untaged') {
			return 1;
		}

		if($this->_doctype() == 'html') {

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
				$this->_set('tmp', []);
				if($this->_isset('request.old') && $this->_get('this.keep.attr.name') && $this->_isset('request.old.'.$this->_get('this.keep.attr.name'))) {
					$this->_set('tmp.'.$this->_get('this.keep.attr.name'), $this->_get('request.old.'.$this->_get('this.keep.attr.name')));
				} else {
					if($this->_isset('this.keep.attr.defineds')) {
						if($this->app->key_exists('default_checked', $this->_get('this.keep.attr.defineds'))) {
							$this->_set('tmp.'.$this->_get('this.keep.attr.name'), $this->_get('this.keep.attr.defineds.default_checked'));
						}
						elseif($this->app->key_exists('default_selected', $this->_get('this.keep.attr.defineds'))) {
							$this->_set('tmp.'.$this->_get('this.keep.attr.name'), $this->_get('this.keep.attr.defineds.default_selected'));
						}
						elseif($this->app->key_exists('default_value', $this->_get('this.keep.attr.defineds'))) {
							$this->_set('tmp.'.$this->_get('this.keep.attr.name'), $this->_get('this.keep.attr.defineds.default_value'));
						}
						elseif($this->app->key_exists('default_text', $this->_get('this.keep.attr.defineds'))) {
							$this->_set('tmp.'.$this->_get('this.keep.attr.name'), $this->_get('this.keep.attr.defineds.default_text'));
						}
					}
				}
				if(!$this->_isset('this.keep.attr.defineds.posted') && !$this->_get('this.keep.attr.name')) {
					if($this->app->key_exists($this->_get('this.keep.attr.name'), $this->_get('tmp'))) {
						$this->_unset('tmp.'.$this->_get('this.keep.attr.name'));
					}
				}
				if($this->app->key_exists($this->_get('this.keep.attr.name'), $this->_get('tmp'))) {
					if($this->app->key_exists('value', $this->_get('tag.attr'))) {
						if($this->app->is_array($this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
							if($this->_in_array($this->_get('tag.attr.value'), $this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
								if($this->_get('tag.tag') == 'input') {
									if($this->_lower($this->_get('this.keep.attr.type')) == 'checkbox' || $this->_lower($this->_get('this.keep.attr.type')) == 'radio') {
										$this->_checked();
									}
								}
								if($this->_get('tag.tag') == 'option') {
									$this->_selected();
								}
							}
						} else {
							if($this->_get('tag.tag') == 'input') {
								if($this->_lower($this->_get('this.keep.attr.type')) == 'checkbox' || $this->_lower($this->_get('this.keep.attr.type')) == 'radio') {
									if($this->_get('tag.attr.value', $this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
										$this->_checked();
									}
								} else {
									$this->_set('tag.attr.value', $this->_get('tmp.'.$this->_get('this.keep.attr.name')));
								}
							}
							if($this->_get('tag.tag') == 'option') {
								if($this->_get('tag.attr.value', $this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
									$this->_selected();
								}
							}
						}
					} else {
						if($this->_get('tag.tag') == 'input') {
							if($this->_lower($this->_get('this.keep.attr.type')) == 'checkbox' || $this->_lower($this->_get('this.keep.attr.type')) == 'radio') {
								if($this->_acceptable($this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
									$this->_checked();
								}
							}
							elseif($this->_lower($this->_get('this.keep.attr.type')) == 'text') {
								$this->_set('tag.attr.value', $this->_get('tmp.'.$this->_get('this.keep.attr.name')));
							} else {
								
							} 
						} else {
							if($this->_get('tag.tag') == 'option') {
								if($this->_acceptable($this->_get('tmp.'.$this->_get('this.keep.attr.name')))) {
									$this->_selected();
								}
							} else {
								if($this->_get('tag.tag') == 'textarea') {
									$this->_set('tag.text', $this->_get('tmp.'.$this->_get('this.keep.attr.name')));
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	public function _doctype(string $type = null) {
		if($type === null) {
			return $this->_get('this.type');
		} else {
			return $this->_set('this.type', $type);
		}
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

	public function _dot_to_underscore($str) {
		return $this->_trim($this->_replace('.', '_', $str), '_');
	}

	public function _set_old($name, $value = '') {
		if($this->app->is_array($name)) {
			return $this->_set('request.old', $name);
		} else {
			return $this->_set('request.old.'.$name, $value);
		}
	}

	public function _underscore_to_dot($str) {
		return $this->_trim($this->_replace('_', '.', $str), '.');
	}

	public function _space_to_underscore($str) {
		return $this->_trim($this->_replace(' ', '_', $str), '_');
	}

	public function _dot_to_dirsep($str) {
		return $this->_trim($this->_replace('.', $this->_dirsep(), $str), $this->_dirsep());
	}

	public function _dot_to_urlsep($str) {
		return $this->_trim($this->_replace('.', $this->_urlsep(), $str), $this->_urlsep());
	}

	public function _present(string $text) {
		third('echo', $text);
	}

	public function _presentend(string $text) {
		$this->_present($text);
		$this->_exit();
	}

	public function _echo(string $text) {
		third('echo', $text);
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

	public function _export(array $options = []) {
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

			if(!$this->app->is_array($options['value'])) {
				$this->_exception('export_type', 'unexpected export: array to string');
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
			return $this->_trim($results);
		} else {
			$this->_exception('file_type', 'unknown file type for export');
		}
	}

	public function _exception($k, $text = null) {

		if(!$this->_get_first_available_path($this->_common_path('lng/en/fatal.'.$this->_sys('clngext')))) {
			if($this->_make_file_force($this->_common_path('lng/en/fatal.'.$this->_sys('clngext')), $this->_get_file_type_returned('php'))) {
				return $this->_exception($k, $text);
			}
		}

		$this->_presentend($k.': '. $this->_lng('fatal.'.$k, $text));
	}

	public function _get_first_available_path($posible, $path = '') {
		$posible = $this->_explode('.', $posible);

		if(!$path) {
			$path = $this->_disk();
		}

		$results = [
			'path' => '',
			'exists' => [],
			'remaining' => []
		];

		$is_not = 0;
		for($i=0;$i<$this->_count($posible);$i++) {
			$path.=$this->_dirsep().$posible[$i];
			if($this->_is_dir($path) && !$is_not) {
				$results['exists'][] = $posible[$i];
			} else {
				$is_not = 1;
				$results['remaining'][] = $posible[$i];
			}
		}

		return $results;
	}

	public function _lng($k, $text = null) {

		$lng = $this->_get_returned_array_file($this->_common_path('lng/en'), $this->_explode('.', $k));

		if($lng) {
			if(!$this->ram[0]->control->isset('lng.en.'.$k)) {
				return $this->ram[0]->control->set('lng.en.'.$k, $this->_get_returned_array_file($this->_common_path('lng/en'), $this->_explode('.', $k)));
			}
		} else {
			return $k.': '.$text;
		}
		return $this->ram[0]->control->get('lng.'.$k);
	}

	public function _get_file_type_returned($type, $contents = '') {
		if($type == 'php') {
			return 'return ['.$contents.'];';
		}
		return '';
	}

	public function _file_append_to_top($file, $contents) {
		return $this->ram[0]->client->file_put_contents($file, $contents.$this->ram[0]->client->file_get_contents($file));
	}

	public function _done($reset = null) {
		$live = $this->ram[0]->live;

		if($live->has_more_opened_tags()) {
			$this->app->exception('syntax', 'You have more opened tags than you have closed. in file: '.$live->get('view'));
		}

		if($live->has_more_closed_tags()) {
			$this->app->exception('syntax', 'You have more closed tags than you have opened. in file: '.$live->get('view'));
		}

		$items = $live->get_nested_items();

		if(!$this->app->is_null($reset)) {
			if(!$this->app->is_array($reset)) {
				$reset = [];
			}
			$live->reset($reset);
		}

		$document = $this->ram[0]->client->build_document($items);

		if($live->get('is_real_view') && $this->ram[0]->config->cache->get('recache')) {
			if($this->ram[0]->client->trim($document) !== '') {
				if($this->app->ini('view')) {
					if($this->ram[0]->client->get('view')) {
						if($this->app->ini('local')) {
							if(!$this->ram[0]->client->is_cached($live->get('view'))) {
								$this->ram[0]->client->cache($live->get('view'), $document);
							}
						}
					}
				}
			}
		}

		return $document;
	}

	public function _fix_type($value, $level = 0) {
		$type = $this->_get_type($value);
		$type = $this->_lower($type);
		
		if($type) {
		switch($type) {
			case 'boolean':
				$value = $value ? 'true' : 'false';
			break;
			case 'integer':
			case 'double':
				
			break;
			case 'string':
				$value = "'".$this->_escape($value)."'";
			break;
			case 'null':
				$value = 'null';
			break;
			case 'array':
				if($this->app->is_empty($value)) {
					$value = '[]';
				} else { 
					$value = "[".$this->_new_line().$this->_space_like_tab($level).$this->_export([
						'value' => $value,
						'quote' => "'",
						'level' => $level,
						'type' => 'array',
					]).$this->_space_like_tab($level - 1).']';
				}
			break;
			default:
				
			break;
		}
		}
		return $value;
	}

	public function _items() {
		return $this->ram[0]->live->get('ready.items');
	}

	public function _items_length() {
		return $this->ram[0]->live->get('this.count');
	}

	public function _class_get_reflection($instance) {
		$class = $this->_reflection($instance);
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

	public function _class_get_properties($instance) {
		$class = $this->ram[0]->client->reflection($instance);

		$class_name = get_class($instance);

		$props = $class->getProperties();
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

			$results[$name] = [
				'name' => $name,
				'is_static' => $is_static,
				'visibility' => $visibility,
			];

			if($real->isDefault()) {
				$value = $real->getValue($instance);
				if(!$this->app->instanceof($value, $this->ram[0]->client->get('namespace.signal')) && !$this->app->instanceof($value, $this->ram[0]->client->get('namespace.manager')) && $value != null) {
					$results[$name]['value'] = $value;
				}
			}

		}
		return $results;
	}

	public function _class_get_methods($instance, $ascii = 1) {
		$class = $this->ram[0]->client->reflection($instance);
		$methods = $class->getMethods();
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
				$args[$j]['name'] = $this->app->lower($param->name);
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
					$end	= $rm->getEndLine() - $start;
			        $lines = file($file, FILE_IGNORE_NEW_LINES);
			        $lines = array_slice($lines, $start, $end, true);
			        $lines = $this->app->implode("\n", $lines);
			        $obrace = $this->ram[0]->client->pos($lines, '{');
			        $cbrace = strrpos($lines, '}');
			        $body = $this->ram[0]->client->substr($lines, $obrace + 1, $cbrace - $obrace - 1);
			        if($ascii) {
			        	$body = $this->app->decoder->_text_to_ascii($body);
			        }
		        }
	        }

			$results[$this->app->lower($method->name)] = [
				'name' => $this->app->lower($method->name),
				'arguments' => $args,
				'body' => $body,
				'is_static' => $is_static,
				'visibility' => $visibility
			];
		}
		return $results;
	}

	public function _class_to_array($instance, $ascii = 1) {
		if($this->app->is_string($instance)) {
			$instance = new $instance;
		}
		$app = $this->ram[0]->client->class_get_reflection($instance);
		$app['properties'] = $this->ram[0]->client->class_get_properties($instance);
		$app['methods'] = $this->ram[0]->client->class_get_methods($instance, $ascii);
		return $app;
	}

	public function _get_database_name() {
		return $this->ram[0]->config->database->get('providers.'.$this->ram[0]->config->database->get('default').'.database');
	}

	public function _database_exists($name) {
		return $this->app->file($this->ram[0]->client->database_path($name));
	}

	public function _database_create($name) {
		if($this->ram[0]->client->mkdir($this->_database_path($name))) {
			if($this->ram[0]->client->make_file($this->ram[0]->client->database_path($name.'/structure.txt'), '//write here the number off your option bellow'.$this->app->new_line().'name='.$name.$this->app->new_line().'tables='.$this->app->new_line().'collation=utf8mb4_unicode_ci')) {
				return 1;
			}
			return 0;
		}
	}

	public function _create_database_table($name) {
		return $this->ram[0]->client->database_set_structure($this->ram[0]->client->get_database_name(), [
			'char1' => 'table',
			'char2' => 'create',
			'name' => $name
		]);
	}

	public function _database_table_create_column($name) {
		return $this->ram[0]->client->database_set_structure($this->ram[0]->client->database_get_name(), [
			'char1' => 'column',
			'char2' => 'create',
			'name' => $name
		]);
	}

	public function _get_database_names(array $op = null) {
		$results = [];
		$files = $this->ram[0]->client->get_paths_only($this->ram[0]->client->database_path());
		for($i=0;$i<$this->app->count($files);$i++) {
			$name = $this->ram[0]->client->basename($files[$i]);
			if($op) {
				if(isset($op['with_tables']) && $op['with_tables'] == 1) {
					$tables = $this->ram[0]->client->database_get_tables($name);
					if(isset($op['with_tables_data']) && $op['with_tables_data'] == 1) {
						if(isset($op['keyed']) && $op['keyed'] == 1) {
							for($t=0;$t<$this->app->count($tables);$t++) {
								$results[$name][$tables[$t]] = $this->ram[0]->client->get_database_table_data($name, $tables[$t]);
							}
						} else {
							$arr_tables_data = [];
							for($t=0;$t<$this->app->count($tables);$t++) {
								$arr_tables_data[] = [
									'name' => $tables[$t],
									'data' => $this->ram[0]->client->get_database_table_data($name, $tables[$t])
								];
							}
							$results[] = [
								'name' => $name,
								'tables' => $arr_tables_data
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

	public function _get_database_names_with_tables($ked = 0) {
		return $this->app->controlled($this->ram[0]->client->get_database_names([
			'with_tables' => 1,
			'keyed' => $ked
		]), 'collection');
	}

	public function _get_database_names_with_tables_and_data($ked = 0) {
		return $this->ram[0]->client->get_database_names([
			'with_tables' => 1,
			'with_tables_data' => 1,
			'keyed' => $ked
		]);
	}

	public function _get_database_structure(string $name, $as_lines = 0) {
		$arr = $this->ram[0]->client->explode_lines($this->ram[0]->client->file_get_contents($this->ram[0]->client->database_path($name.'/structure.txt')));

		if($as_lines) {
			return $arr;
		}

		$results = [];
		$ctrl = 0;
		for($i=0;$i<$this->app->count($arr);$i++) {
			if($i == 0) {
				$ctrl = $this->ram[0]->client->int($arr[$i]);
			} else {
				if($i <= $ctrl) {
					$item = $this->app->explode('=', $arr[$i]);
					if($item[0] == 'tables') {
						$results['tables'] = $this->app->explode('|', $this->ram[0]->client->trim($item[1]));
					}
					elseif($item[0] == 'collation') {
						$results['collation'] = $item[1];
					}
				}
			}
		}
		return $results;
	}

	public function _database_set_structure(string $name, array $data) {
		$arr = $this->_get_database_structure($name, 1);
		for($i=0;$i<$this->_count($arr);$i++) {
			if($i == 1) {
				if($data['char1'] == 'table') {
					if($data['char2'] == 'create') {
						if($this->_database_table_exists($name, $data['name'])) {
							$this->_exception('database', 'The table name ( '.$data['name'].' ) already exists in database ( '.$name.' ).');
						} else {
							$rows = $this->_explode('=', $arr[$i]);
							$rows[1] = $this->_lower($rows[1]);
							$names = $this->_explode('|', $rows[1]);
							$arr[$i] = 'tables='.($rows[1] ? $rows[1].'|'.$data['name'] : $data['name']);

							for($j=0;$j<$this->_count($names);$j++) {
								$this->_make_file_force($this->_database_path($name.'/'.$names[$j].'/structure.txt'));
							}
						}
					}
					elseif($data['char2'] == 'delete') {
						
					}
					elseif($data['char2'] == 'update') {
						
					}
					elseif($data['char2'] == 'find') {
						
					}
				}
				elseif($data['char1'] == 'column') {
					
				}
			}
		}

		if(!$this->_file_put_contents($this->_database_path($name.'/structure.txt'), $this->_implode($this->_new_line(), $arr))) {
			$this->_exception('database', 'Can not write to database');
		} else {
			return 1;
		}
	}

	public function _database_table_exists(string $db_name, string $table_name) {
		return $this->_in_array($table_name, $this->_database_get_tables($db_name));
	}

	public function _get_database_table_columns($database, $table) {
		$results = [];
		$data = $this->_get_database_table_config($database, $table);
		for($i=0;$i<$this->_count($data);$i++) {
			$results[] = $data[$i]['name'];
		}
		return $results;
	}

	public function _get_database_table_config(string $database, string $table, array $opts = []) {
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

	public function _get_database_table_config_string($database, $table) {
		if($this->_database_path($database.'/'.$table.'/structure.txt')) {
			return $this->_file_get_contents($this->_database_path($database.'/'.$table.'/structure.txt'));
		}
	}

	public function _get_database_table_data($database, $tables) {
		$results = [];
		if(!$this->app->is_array($tables)) {
			$tables = [$tables];
		}
		$allow = $this->_database_get_tables($database);
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
				$this->_exception('table', 'Table [ '.$table.' ] not exists in database [ '.$database.' ]');
			}
		}
		return $results;
	}

	public function _get_database_table_data_all($database) {
		return $this->_get_database_table_data($database, $this->_database_get_tables($database));
	}

	public function _get_database_table_data_string($database, $table) {
		return $this->_file_get_contents($this->_database_path($database.'/'.$table.'/data.txt'));
	}

	public function _database_get_tables(string $database) {
		return $this->_get_database_structure($database)['tables'];
	}

	public function _dir_file($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

	public function _get_exported_string_defaults($arr) {
		$defaults = [
			'first' => 0,
			's' => "",
			'ss' => "",
			'var' => "",
			'without_numeric_keys' => 0,
			'count' => 0,
			'index' => 0,
			'level' => 1,
			'space' => 0,
			'prefix_key' => '',
			'value' => 0,
			'caption' => '',
			'keytype' => "",
			'newline' => "",
			'spacetab' => "",
			'newspace' => "",
			'key_to_indent' => 0,
			'value_to_indent' => 0,
			'quote' => '"',
			'valuetype' => 'array',
			'escape_key' => null,
			'index_prev' => 0,
			'rowseparator' => ",",
			'opentagsymbol' => "[",
			'closetagsymbol' => "]",
			'key_separator_value' => ' => ',
		];

		foreach($defaults as $k => $value) {
			$arr[$k] = $this->app->key_exists($k, $arr) ? $arr[$k] : $defaults[$k];
		}

		return $arr;
	}

	public function _get_keys($single, $data) {
		$results = [];
		for($i=0;$i<count($single);$i++) {
			$results[$single[$i]] = $data[$single[$i]];
		}
		return $results;
	}

	public function _get_string($single, $data) {
		$results = '';
		for($i=0;$i<count($single);$i++) {
			$results.=$data[$single[$i]];
		}
		return $results;
	}

	public function _get_exported_string_settings($data, $default, $k, $value) {

		$data['key'] = $k;
		$data['value'] = $value;
		$data['newline'] = $this->_new_line();
		$data['keytype'] = $this->_get_type($data['key']);
		$data['valuetype'] = $this->_get_type($data['value']);

		if($data['keytype'] == 'integer') {
			if($data['without_numeric_keys'] == 1) {
				$data['key'] = '';
				$data['key_separator_value'] = '';
			}
		} else {
			$data['key_separator_value'] = $default['key_separator_value'];
			if($data['key_to_indent']) {
				
			} else {
				if($data['keytype'] == 'string') {
					$data['key'] = $data['quote'].$data['key'].$data['quote'];
				}
			}
		}
		return $data;
	}

	public function _get_exported_string($default) {
		$default = $this->_get_exported_string_defaults($default);
		$current = $default;
		$count = 0;
		foreach($default['value'] as $k => $value) {

			$current = $this->_get_exported_string_settings($current, $default, $k, $value);

			if($current['valuetype'] == 'array') {
				$current['level'] = $current['level'] + 1;
				$current['s'] = "";
				$current['ss'] = "";
				for($j=0;$j<$current['level'];$j++) {
					if($j>0) {
						$current['s'].=$current['spacetab'];
					}
					$current['ss'].=$current['spacetab'];
				}
				if($current['count'] - 1 == $count) {
					$current['rowseparator']=$default['newline'].$current['newline'];
				} else {
					$current['rowseparator']=$default['rowseparator'].$default['newline'].$current['newline'];
				}
				$current['nested'] = $this->_get_exported_string([
			    	'space' => $current['space'],
			    	'caption' => $current['caption'],
			    	'value' => $current['value'],
			    	'level' => $current['level'],
			    	'count' => $this->_count($current['value']),
			    	'index' => $current['index'],
			    	'prefix_key' => $current['prefix_key'],
			    	'spacetab' => $current['spacetab'],
			    	'key_to_indent' => $current['key_to_indent'],
			    	'index_prev' => count($current['value']) - 1,
					'opentagsymbol' => $current['opentagsymbol'],
					'closetagsymbol' => $current['closetagsymbol'],
					'without_numeric_keys' => $current['without_numeric_keys'],
					'value_to_indent' => $current['value_to_indent'],
					'quote' => $current['quote'],
					'escape_key' => $current['escape_key'],
			    ]);
				if($current['nested']) {
					$current['var'].=$this->_get_string(['newspace', 's', 'prefix_key', 'key', 'key_separator_value', 'opentagsymbol', 'newline', 'nested', 'newspace', 's', 'closetagsymbol', 'rowseparator'], $current);
				} else {
					$current['var'].=$this->_get_string(['newspace' ,'s' ,'prefix_key' ,'key' ,'key_separator_value' ,'opentagsymbol' ,'closetagsymbol' ,'rowseparator'], $current);
				}
				$current['level'] = $current['level'] - 1;
			} else {
				$current['s'] = "";
				$current['ss'] = "";
				for($j=0;$j<$current['level'];$j++) {
					if($j>0) {
						$current['s'].=$current['spacetab'];
					}
					$current['ss'].=$current['spacetab'];
				}
				switch($current['valuetype']) {
					case 'boolean':
						$current['value'] = $current['value'] ? 'true' : 'false';
		            break;
		            case 'integer':
		            	$current['value'] = $current['value'];
		            break;
		            case 'double':
		            	$current['value'] = $current['value'];
		            break;
		            case 'string':
		            	if($current['escape_key'] == $k) {
		            		$current['value'] = $this->_escape($current['value']);
		            	}
						if(!$current['value_to_indent']) {
							$current['value'] = $current['quote'].$current['value'].$current['quote'];
						}
		            break;
		            case 'NULL':
		            	$current['value'] = 'null';
		            break;
		        }
				if($current['count'] - 1 == $count) {
					$current['rowseparator']=$default['newline'].$current['newline'];
				} else {
					$current['rowseparator']=$default['rowseparator'].$default['newline'].$current['newline'];
				}
				$current['var'].=$this->_get_string(['newspace', 'ss', 'prefix_key', 'key', 'key_separator_value', 'value', 'rowseparator'], $current);
			}
			$count++;
		}
		return $current['var'];
	}

	public function _get_items() {
		$live = $this->ram[0]->live;

		return $live->get('this.items');
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

	public function _get_nested_items() {
		$live = $this->ram[0]->live;
		return $this->ram[0]->client->nested($live->get_items());
	}

	public function _has_more_opened_tags() {
		return $this->ram[0]->live->get('this.unclosed_tags') > 0;
	}

	public function _has_more_closed_tags() {
		return $this->ram[0]->live->get('this.unclosed_tags') < 0;
	}

	public function _get_body_classes() {
		return $this->app->implode(' ', $this->ram[0]->live->get('this.bodyclass'));
	}

	public function _get_include_contents($file) {
		return $this->_file_get_contents($this->_template_path($file.'.'.$this->_sys('clngext')));
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

	public function _include_exists($file) {
		$file = $this->app->template_path($file.'.'.$this->app->sys('clngext'));

		$key = $this->ram[0]->client->slash_to_dot($file);
		if(!$this->ram[0]->control->isset('includes.'.$key)) {
			$this->ram[0]->control->set('includes.'.$key, $this->app->file($file));
		}
		return $this->ram[0]->control->get('includes.'.$key);
	}

	public function _visitor_is($str) {
		return $this->ram[0]->control->get('visitor', $this->app->lower($str));
	}

	public function _visitor_is_with($str = '') {
		$results = '';
		$visitor = $this->ram[0]->control->get('visitor');
		if($visitor != '') {
			$results.=$visitor;
			if($str != '') {
				$results.=$this->app->urlsep().$str;
			}
		} else {
			$results = $str;
		}
		return $results;
	}

	public function _is_cached($file) {
		return $this->_file($this->_storage('interframework/cache/'.$this->_to_dirsep($file.'.'.$this->ram[0]->config->cache->get('cache_file_type'))));
	}

	public function _is_closure($callback) {
		return $this->_instanceof($callback, 'Closure');
	}

	public function _is_int($element) {
		return $this->_is_integer($element);
	}

	public function _is_same($a, $b) {
		return $this->_get($a, $this->_get($b));
	}

	public function _load_components($types, $check = false) {
		if(!$this->app->is_array($types)) {
			$types = [$types];
		}
		foreach($types as $type) {
			foreach([$type.'_require', $type.'_dynamic', $type.'_auto_view'] as $setch) {
				if(!$this->ram[0]->config->settings->get($setch)) {
					foreach(['before', 'after'] as $place) {
						foreach($this->ram[0]->monitor->html->get('this.resources.'.$place.'.'.$setch) as $key => $comp) {
							if($check) {
								if(!$this->app->first_in($comp, $this->ram[0]->config->settings->get('allowed_protocols'))) {
									$this->ram[0]->monitor->html->set('this.resources.'.$place.'.'.$setch.'.'.$key, $this->app->assets_url($comp));
								}
							} else {
								if($type == 'css') {
									$this->ram[0]->monitor->html->tag('link')->attr('data-preload', true)->attr('href', $comp)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
								}
								elseif($type == 'js') {
									$this->ram[0]->monitor->html->tag('script')->attr('data-preload', true)->attr('src', $comp)->attr('type', 'text/javascript')->tag();
								}
							}
						}
					}
				}
			}
		}
		return $this;
	}

	public function _head_style(string $style = '') {
		if($style) {
			$this->app->set_after_or_before('before');
			$this->app->push('this.resources.before.style', $style);
			$this->app->set_after_or_before('after');
		}
	}

	public function _load_styles() {
		foreach(['before', 'after'] as $after_or_before) {
			foreach($this->app->get('this.resources.'.$after_or_before.'.style') as $style) {
				$this->app->tag('style')->text($style)->tag();
			}
		}
	}

	public function _load_the_scripts_components() {
		if(!$this->ram[0]->config->settings->get('scripts')) {
			foreach($this->ram[0]->monitor->html->get('this.resources.after.scripts') as $ajavascript) {
				$this->ram[0]->monitor->html->tag('script')->attr('preload', true)->text($ajavascript)->tag();
			}
			foreach($this->_get('this.resources.before.scripts') as $bjavascript) {
				$this->ram[0]->monitor->html->tag('script')->attr('preload', true)->text($bjavascript)->tag();
			}
		}
		return $this;
	}

	public function _log($msg, $presentend = 0) {
		$time = $this->ram[0]->client->get_time();
		$log = 'Date: '.$time['year']."-".$time['month']."-".$time['day']."\nTime: ".$time['our'].":".$time['minutes'].":".$time['seconds']."\nMessage : ".$msg."\n ---------------------------------------------------------- \n";

		if(!$this->ram[0]->client->ready('config') || $presentend === 1) {
			$this->ram[0]->client->presentend($log);
		}

		if($presentend === 2) {
			$this->ram[0]->client->present($log);
		} else {
			if($presentend === 3) {
				$this->ram[0]->client->present($log);
			}
			if($this->app->ini('local')) {
				if(!$this->app->file($this->app->storage('interframework/logs/log.txt'))) {
					$this->app->make_file_force($this->app->storage('interframework/logs/log.txt'));
				}
				$cont = $log.$this->ram[0]->client->file_get_contents($this->app->storage('interframework/logs/log.txt'));
				$this->ram[0]->client->file_put_contents($this->app->storage('interframework/logs/log.txt'), $cont);
			}
			if($presentend === 4) {
				$this->ram[0]->client->presentend($log);
			}
		}
	}

	public function _make_include($file, $contents = '') {
		if(!$this->app->make_file_force($this->app->template_path($file).'.'.$this->app->sys('clngext'), $contents)) {
			$this->app->exception('connection', 'can\'t connect.');
		}
	}

	public function _manual($k, $value = null) {
		return $this->_set('manual.'.$k, $value === null ? 1 : $value);
	}

	public function _is_manual($k) {
		return $this->_isset('manual.'.$k) && $this->_get('manual.'.$k);
	}

	public function _mb_str_split($str) {
		$results = [];
	    foreach($this->_str_split($str) as $char) {
	    	if(!$this->_in_array($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

	public function _mkdirs(array $arr, $force = false, $path = '') {
		$path = $path ? $path.$this->_dirsep() : '';
		foreach($arr as $row) {
			if($this->_mkdir($path.$row['name'], isset($row['mode']) ? $row['mode'] : 0777)) {
				if(isset($row['files']) && !$this->app->is_empty($row['files'])) {
					foreach($row['files'] as $file) {
						$ready_file = $path.$row['name'].$this->_dirsep().$file['name'];
						$ready_contents = '';
						if($this->app->key_exists('contents', $file)) {
							$ready_contents = $this->_is_string($file['contents']) ? $file['contents'] : $file['contents']();
						}
						if($force) {
							$this->_make_file_force($ready_file, $ready_contents);
						} else {
							$this->_file_put_contents($ready_file, $ready_contents);
						}
					}
				}
				if(isset($row['subfolders']) && !$this->app->is_empty($row['subfolders'])) {
					$this->_mkdirs($row['subfolders'], $force, $path.$row['name']);
				}
			} else {
				$this->_exception('make_directory', 'Cannot make a directory: '.$path.$row['name']);
			}
        }
        return 1;
	}

	public function _globals($key) {

		if($this->ram[0]->control->isset('globals')) {
			if($this->ram[0]->control->get('globals', 0)) {
				return 0;
			}
		}

		if(!isset($GLOBALS)) {
			return $this->ram[0]->control->set('globals', 0);
		}

		$k = $this->_base($key);
		$base = $k['base'];
		$ks = $k['keys'];

		if($this->app->key_exists($base, $GLOBALS)) {
			if($this->_count($ks) > 0) {
				return $this->_array_key_value($ks, $GLOBALS[$base]);
			} else {
		   		return $GLOBALS[$base];
			}
		}
		return null;
	}

	public function _make_file($file, $contents = '', $lock = false) {
		if($this->_file($file)) {
			$this->_exception('files', 'cannot create file ( '.$file.' ) the file already exists');
		} else {
			return $this->_file_put_contents($file, $contents, $lock);
		}
	}

	public function _make_file_force($file, $contents = '') {
		if(!$this->_file($file)) {
			$dir = $this->_dir_file($file);
	   		if(!$this->_is_dir($dir)) {
	   			$this->_mkdir($dir, 0755);
	   		}
	   	} else {
		   	$this->_delete_file($file);
	   	}
	   	if($this->_trim($contents) == '') {
	   		if($this->_make_file($file, 'ok')) {
	   			$this->_file_put_contents($file, '');
	   			return 1;
	   		}
	   	} else {
	   		return $this->_make_file($file, $contents);
	   	}
	}

	public function _get_fprefix() {
		return '_';
	}

	public function _countdown($time, $k) {
		$this->_set('countdown_'.$k, $time);
	}

	public function _chronometer($time, $k) {
		$this->_set('chronometer_'.$k, $time);
	}

	public function _get_time() {

		$datetime = $this->_date_time("now", $this->_get_date_time_zone('Europe/Athens'));
		$datetime->setTimestamp($this->_get_time_in_milliseconds());

		$time 		= $datetime->format('Y/d/m-H:i:s');
		$time 		= $this->_explode('-', $datetime->format('Y/d/m-H:i:s'));

		$date 		= $time[0];
		$date 		= $this->_explode('/', $date);

		$year		= $this->_int($date[0]);
		$month		= $this->_int($date[2]);
		$day		= $this->_int($date[1]);

		$time		= $time[1];
		$time		= $this->_explode(':', $time);

		$our		= $time[0];
		$minutes	= $time[1];
		$seconds	= $time[2];

		return [
			'year' => $year,
			'month' => $month,
			'day' => $day,
			'our' => $our,
			'minutes' => $minutes,
			'seconds' => $seconds,
		];
	}

	public function _nested(array $data = []) {
	    $prev = 0;
		$waiting = [];
	    for($i=0;$i<$this->_count($data);$i++) {
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
	        if(!$this->app->is_empty($results[$i]['items'])) {
	            $results[$i]['items'] = $this->_nested($results[$i]['items']);
	        }
	        $i++;
	    }

	    return $results;
	}

	public function _new_line() {
		return "\n";
	}

	public function _enter($nums = 1, $spaced = 1) {
		for($i=0;$i<$nums;$i++) {
			$this->_tag('untaged')->_attr('lined', 1)->_attr('spaced', $spaced)->_tag();
		}
		return $this;
	}

	public function _new_tag() {
		return [
			'doctype' 					=> $this->_doctype(),
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
			'space_level'				=> 0,
			'extra'						=> null,
			'is_inside_with_outside'	=> 0,
		];
	}

	public function _posted() {
		$this->_set('tag.defineds.posted', 1);
        return $this;
	}

	public function _path() {
		return $this->ram[0]->request->get('server.request_path');
	}

	public function _public_path($str = '') {
		return $this->_disk($this->_sys('paths.public').'/'.$str);
	}

	public function _database_path($str = '') {
		return $this->_storage('interframework/database/'.$str);
	}

	public function _common_path($str = '') {
		return $this->_views_path('common/'.$str);
	}

	public function _template_path($str = '') {
		$visitor = $this->_visitor_is_with();
		return $this->_views_path('templates/'.($visitor ? $visitor : 'guest').'/'.$this->_get_template().'/'.$str);
	}

	public function _assets_path($str = '') {
		return $this->_public_path('assets/'.$str);
	}

	public function _no_disk($source, $dest) {
		return $this->_replace($this->_trim($this->_to_dirsep($this->_lower($source))), '', $this->_trim($this->_to_dirsep($this->_lower($dest))));
	}

	public function _no_url($source, $dest) {
		return $this->_replace($this->_trim($this->_to_urlsep($source)), '', $this->_trim($this->_to_urlsep($dest)));
	}

	public function _sys($k) {
		return $this->_get('flammable.'.$k);
	}

	public function _app_path($str = '') {
		return $this->_disk($this->_sys('paths.app').'/'.$str);
	}

	public function _provider_path($str = '') {
		return $this->_disk($this->_sys('paths.provider').'/'.$str);
	}

	public function _config_path($str = '') {
		return $this->_disk($this->_sys('paths.config').'/'.$str);
	}

	public function _controllers_path($str = '') {
		return $this->_app_path($this->_sys('paths.controllers').'/'.$str);
	}

	public function _boot_path($str = '') {
		return $this->_disk($this->_sys('paths.boot').'/'.$str);
	}

	public function _views_path($str = '') {
		return $this->_disk($this->_sys('paths.views').'/'.$str);
	}

	public function _storage($str = '') {
		return $this->_disk($this->_sys('paths.storage').'/'.$str);
	}

	public function _add_script($script) {
		$this->_push('this.resources.'.$this->_get('after_or_before').'.scripts', $script);
		return $this;
	}

	public function _load_or_build_resources($path, $contents = '') {
		$css = $path.'.css';
		$js = $path.'.js';
		if(!$this->_file($this->_assets_path($css))) {
			if($this->_make_file_force($this->_assets_path($css), $contents)) {
				$this->_require($css, 'auto_view');
			}
		} else {
			$this->_require($css, 'auto_view');
		}
		if(!$this->_file($this->_assets_path($js))) {
			if($this->_make_file_force($this->_assets_path($js), $contents)) {
				$this->_require($js, 'auto_view');
			}
		} else {
			$this->_require($js, 'auto_view');
		}
	}

	public function _set_after_or_before($switch) {
		$this->ram[0]->live->set('after_or_before', $switch);
	}

	public function _set_space_level($level) {
		return $this->ram[0]->live->set('this.space_level', $level);
	}

	public function _get_space_level() {
		return $this->ram[0]->live->get('this.space_level');
	}

	public function _my_space($num) {
		$this->ram[0]->live->set('tag.my_space', $num);
		return $this;
	}

	public function _fixed_space($num) {
		$this->ram[0]->live->set('this.fixed_space', $num);
		return $this;
	}

	public function _set_type($type, $value) {
		switch($type) {
			case'int':
				$value = $this->ram[0]->client->int($value);
			break;
			case'string':
				$value = $this->app->string($value);
			break;
		}
		return $value;
	}

	public function _slash_and_dot_to_dirsep($str) {
		return $this->ram[0]->client->trim($this->ram[0]->client->replace(['/', '\\', '.'], $this->app->dirsep(), $str), $this->app->dirsep());
	}

	public function _slash_and_dot_to_dash($str) {
		return $this->ram[0]->client->trim($this->_replace(['/', '\\', '.'], '_', $str), $this->_urlsep());
	}

	public function _slash_and_dot_to_space($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], ' ', $str), $this->_urlsep());
	}

	public function _slash_and_dot_to_urlsep($str) {
		return $this->_trim($this->_replace(['/', '\\', '.'], $this->_urlsep(), $str), $this->_urlsep());
	}

	public function _slash_to_dot($str) {
		return $this->_trim($this->_replace(['/', '\\'], '.', $str), '.');
	}

	public function _space($number) {
		return $this->ram[0]->client->get_spaces_by_level($number, " ");
	}

	public function _space_like_tab($number) {
		if($this->ram[0]->client->isset('tmp.spaces.'.$number)) {
			$results = $this->ram[0]->client->get('tmp.spaces.'.$number);
		} else {
			$results = $this->ram[0]->client->set('tmp.spaces.'.$number, $this->_get_spaces_by_level($number, "	"));
		}
		return $results;
	}

	public function _space_to_dash($str) {
		return $this->ram[0]->client->replace(' ', '-', $str);
	}

	public function _absolute_disk($path = '') {
		$root = $this->ram[0]->disk->get('absolute_root');
		if($path) {
			$root = $root.$path;
		}
		return $this->app->lower($root);
	}

	public function _disk(string $path = '') {
		$root = $this->ram[0]->disk->get('root');
		if($path) {
			$root = $root.$this->app->to_dirsep($path);
		}
		return $this->app->lower($root);
	}

	public function _storage_copy($source, $destination) {
		return $this->ram[0]->client->copy_dir(
			$this->app->storage($source), $destination
		);
	}

	public function _style($data) {
		$this->ram[0]->client->set('tag.attr.style', $data);
		return $this;
	}

	public function _remove_lines($data, $with = '') {
		return $this->ram[0]->client->replace($this->app->new_line(), $with, $data);
	}

	public function _style_to_file($style, $file) {

		$data = $style;
    	$contents = '';

    	if($class) {
			$class = $this->_trim($class);
			$file_name = 'generated/app.css';
			$start.= $this->_space_to_dash($this->_slash_and_dot_to_space($this->_get('view')));
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

	public function _syntax_error($error, $line = 0) {
		$this->app->exception('syntax', $error.' on line '.$line);
	}

	public function _tab_space($number) {
		return $this->ram[0]->client->get_spaces_by_level($number, "\t");
	}

	public function _tag($tag = null) {

		if($this->_get('this.tag_is_opened')) {

			if($this->_certificated()) {

				if($this->_isset('this.fixed_space')) {
					if($this->_get('tag.my_space', 0)) {
						$this->_set('tag.my_space', $this->_get('this.fixed_space'));
					}
				}

				$is_inside_with_outside = $this->_get('this.is_inside_with_outside');
				if($is_inside_with_outside) {
					$this->_set('this.is_inside_with_outside', 0);
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
					'extra'						=> $this->_get('tag.extra'),
					'is_inside_with_outside'	=> $is_inside_with_outside,
				    'index'						=> $this->_get('this.index'),
				    'level'						=> $this->_get('this.level'),
					'space_level'				=> $this->_get('this.space_level'),
				];


				$this->_set('this.items.'.$this->_get('this.index'), $item);

				$this->_set('tag', $this->_new_tag());

				$this->_set('this.count', $this->_get('this.count') + 1);
				$this->_set('this.index', $this->_get('this.index') + 1);
				$this->_set('this.tag_is_opened', 0);
			} else {
				$this->_exception('security', 'Security error: Certificate has fail to process your request.');
			}
		}

		if(!$tag) {
			$this->_set('this.unclosed_tags', $this->_get('this.unclosed_tags') - 1);
			$this->_chkeep();
			if($this->_get('this.level') > 0) {
				$this->_set('this.level', $this->_get('this.level') - 1);
			}
		} else {

			$this->_set('this.unclosed_tags', $this->_get('this.unclosed_tags') + 1);
			if($this->_get('this.level') >= 0) {
				$this->_set('this.level', $this->_get('this.level') + 1);
			}

			$this->_set('this.tag_is_opened', 1);
			$this->_set('tag.tag', $this->_remove_spaces($tag));
		}
		return $this;
	}

	public function _text($text = null, $lined = 0) {
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

	public function _title($title) {
		return $this->ram[0]->client->set('title', $title);
	}

	public function _subtitle($subtitle) {
		return $this->ram[0]->client->set('subtitle', $subtitle);
	}

	public function _get_title() {
		return $this->ram[0]->client->get('title');
	}

	public function _get_subtitle() {
		return $this->ram[0]->client->get('subtitle');
	}

	public function _allowed_execute_lng_codes() {
		return $this->ram[0]->config->app->get('allowed_execute_lng_codes');
	}

	public function _get_style() {
		return $this->ram[0]->config->monitor->get('standard_style_css');
	}

	public function _get_standard_js() {
		return $this->ram[0]->config->monitor->get('standard_js');
	}

	public function _is_mode($mode) {
		return $this->ram[0]->config->settings->get('mode', $mode);
	}

	public function _base($worm, $way = '.') {

		if(!$this->_is_string($worm)) {
			$sql = $this->_document('sql');

			$sql->tag('table')->attr('name', $worm['base'])->attr('columns', $worm['keys'])->tag();

			$this->_make_file_force($this->_database_path('install.sql'), $sql->done(1));

			$this->ram[0]->db->set('data', $sql);

			return $this->_base($worm['base'].(!$this->app->is_empty($worm['keys']) ? $way.$this->_implode($way, $worm['keys']) : ''), $way);
		}

		if($this->app->first_in($worm, $way) || $this->app->last_in($worm, $way) || $this->app->contains_in($worm, $way.$way)) {
		   	$this->_exception('collection', 'FATAL ERROR: WRONG COLLECTION KEY SKELETON FOR [ '.$worm.' ]');
		} else {

	    	$worm = $this->_explode($way, $worm);
			$base = $worm[0]; unset($worm[0]);
			$worm = $this->_array_zero($worm);

	   		return [
	   			'base' => $base,
	   			'keys' => $worm
	   		];
	   	}
	}

	public function _base_string($k, $way) {
		$structure = $this->_base($k, $way);
		if($this->_count($structure['keys']) == 1) {
			$structure['file'] = $structure['keys'][0];
			$structure['keys'] = null;
		} else {
			$target = $this->_count($structure['keys']) - 1;
			$structure['file'] = $structure['keys'][$target];
			unset($structure['keys'][$target]);
		}
    	return [
    		'str_left' => $structure['base'],
    		'str_middle' => $structure['keys'],
    		'str_right' => $structure['file']
    	];
	}

	public function _to_dirsep($str) {
		return $this->_trim($this->_replace(['/', '\\'], $this->_dirsep(), $str), $this->_dirsep());
	}

	public function _to_urlsep($str = '') {
		return $this->_trim($this->_replace(['/', '\\'], $this->_urlsep(), $str), $this->_urlsep());
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

	public function _ucfirst($str) {
		return ucfirst($str);
	}

	public function _upper_to_underscore($string) {
		return third('lower', $this->_preg_replace('/(.)([A-Z])/', '$1_$2', $string));
	}

	public function _array_keys_lower(array $arr) {
		$results = [];
		foreach($arr as $k => $value) {
			$results[$this->_lower($k)] = $value;
		};
		return $results;
	}

    public function _snake($str) {
		$str = $this->_preg_replace('/\s+/u', '', $this->_ucwords($str));
		$str = $this->_lower($this->_preg_replace('/(.)(?=[A-Z])/u', '$1_', $str));
        return $str;
    }

	public function _is_localhost() {
        return $this->ram[0]->request->get('server.http_host', 'localhost');
    }

	public function _uri($str = '') {
        return $this->ram[0]->request->get('server.request_uri').$str;
    }

    public function _normilize_request($globals) {

    	if($this->_is_object($globals)) {
    		$globals = $globals->all();
    	}

    	$methods = $this->ram[0]->config->settings->get('allowed_methods');

    	for($i=0;$i<$this->app->count($methods);$i++) {
			$this->ram[0]->request->set($methods[$i], []);
			foreach($globals[$methods[$i]] as $k => $value) {
				$k = $this->app->lower($k);

				if($k == 'request_uri') {
					$value = rawurldecode($value);
				}

				$this->ram[0]->request->set($methods[$i].'.'.$this->_lower($k), $value);
			};
    	}



			if($this->app->uri()) {

				if($this->app->substr($this->app->uri(), 0, 1) == $this->app->urlsep()) {

					$parsed = [
						'host' => '',
						'port' => '',
						'user' => '',
						'pass' => '',
						'path' => '',
						'query' => '',
						'fragment' => ''
					];

					if($this->app->uri() != $this->app->urlsep()) {
						$parsed = $this->app->parse_url($this->app->uri());
					}

					$query_string = '';
		        	if(isset($parsed['query'])) {
			            parse_str(html_entity_decode($parsed['query']), $query);
			            if(!empty($get)) {
			                $query_string = $this->_build_query($this->_array_replace($query, $get), '', '&');
			            } else {
			                $query_string = $parsed['query'];
			            }
			        } else {
			        	if(!empty($get)) {
			        		$query_string = $this->_build_query($get, '', '&');
			        	}
			        }

			        $query_string = $this->_trim($query_string);

					$this->ram[0]->request->set('server.request_path', $this->_to_urlsep($parsed['path']));
					$this->ram[0]->request->set('server.query_string', $query_string);
					$this->ram[0]->request->set('server.request_uri', $parsed['path']);
					if($query_string) {
						$this->ram[0]->request->set('server.request_uri', $this->ram[0]->request->get('server.request_uri').'?'.$query_string);
					}
				} else {
					$this->_exception('request', 'Fattal error: Incorrect [ REQUEST_URI ] must begin with [ '.$this->_urlsep().' ]');
				}
			} else {
				$this->_exception('request', 'Fattal error: [ REQUEST_URI ] is missing.');
			}


    	if($this->ram[0]->request->get('server.http_x_requested_with', 'XMLHttpRequest')) {
    		$this->ram[0]->request->set('server.is_ajax', 1);
    	}

		return $this->ram[0]->request;
    }

	public function _server($key = null) {
		if($key === null) {
			return $this->ram[0]->request->get('server');
		}
        return $this->ram[0]->request->get('server.'.$key);
    }

	public function _method($m = null) {
		$method = $this->app->lower($this->app->server('request_method'));
		if($m === null) {
			return $method;
		}
		return $method === $m;
	}

	public function _isajax() {
		return $this->ram[0]->request->get('server.is_ajax', 1);
	}

	public function _is_secure() {
		return $this->ram[0]->request->get('request_scheme', 'https');
	}

	public function _scheme() {
		return $this->app->is_secure() ? 'https' : 'http';
	}

	public function _url_host() {
		return $this->ram[0]->request->get('server.http_host');
	}

	public function _query_replace($query, $replace) {
		return $this->_array_replace($query, $replace);
	}

	public function _get_domain() {
		$host = $this->_url_host();
		return $this->app->scheme().'://'.($host ? $host : 'localhost');
	}

	public function _url(string $path = '', array $query = [], array $replace = []) {

		$results = '';
		if(!$this->app->is_empty($replace)) {
			$query = $this->_query_replace($query, $replace);
		}
		if(!$this->app->is_empty($query)) {
			foreach($query as $k => $value) {
				if($this->_trim($value) !== '') {
					if($results) {
						$results.='&';
					}
					$results.=$k.'='.$value;
				}
			}

			$query = '';
			if($results) {
				$query.='?'.$results;
			}
		} else {
			$query = '';
		}

		$path = $this->_trim($path);

		$domain = $this->_get_domain();

		if($this->_first_in($path, $this->ram[0]->config->settings->get('allowed_protocols'))) {
			if($this->ram[0]->config->settings->get('full_document_url')) {
				$url = $path.$query;
			} else {
				$url = $this->_urlsep().$this->_no_url($domain, $path);
			}
		} else {
			if($path) {
				if($this->_first_in($path, $this->_urlsep())) {
	  				if($this->ram[0]->config->settings->get('full_document_url')) {
						$url = $domain.$path.$query;
					} else {
						$url = $path.$query;
					}
				} else {
					if($this->ram[0]->config->settings->get('full_document_url')) {
						$url = $domain.$this->_urlsep().$path.$query;
					} else {
						$url = $this->_urlsep().$path.$query;
					}
				}
			} else {
				if($this->ram[0]->config->settings->get('full_document_url')) {
					$url = $domain.$query;
				} else {
					$url = $this->_urlsep().$query;
				}
			}
		}

		return $url;
	}

	public function _is_gp($k) {
		if($this->ram[0]->request->isset('post.'.$k)) {
			return 1;
		}
		return $this->ram[0]->request->isset('get.'.$k);
    }

	public function _gp($k) {
		if($this->ram[0]->request->isset('post.'.$k)) {
			return $this->ram[0]->request->get('post.'.$k);
		}
		return $this->ram[0]->request->get('get.'.$k);
    }

	public function _is_g($k) {
		return $this->ram[0]->request->isset('get.'.$k);
    }

	public function _g($k) {
		return $this->ram[0]->request->get('get.'.$k);
    }

	public function _is_p($k) {
		return $this->ram[0]->request->isset('post.'.$k);
    }

	public function _p($k) {
		return $this->ram[0]->request->get('post.'.$k);
    }

	public function _gall() {
		return $this->ram[0]->request->get('get');
    }

	public function _pall() {
		return $this->ram[0]->request->get('post');
    }

	public function _urlsep() {
		return '/';
	}

	public function _array_from_string($case, string $str) {
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

	public function _change_prefix(string $from, string $to, string $str) {
		$len = $this->_length($from);
		if($this->_substr($str, 0, $len) == $from) {
			$str = $this->_substr($str, $len);
		}
		return $to.$str;
	}

	public function _remove_prefix(string $prefix, string $str) {
		$len = $this->_length($prefix);
		if($this->_substr($str, 0, $len) == $prefix) {
			$str = $this->_substr($str, $len);
		}
		return $str;
	}

	public function _usb($file = '') {
		return 'E:'.$this->_dirsep().$this->_dirsep().$this->_to_dirsep($file);
	}

	public function _implode_lines($arr) {
		return $this->_implode($this->_new_line(), $arr);
	}

	public function _require_local($path, $data) {
		return third('require_local', $this->_absolute_disk($path), $data);
	}

	public function _delete_dir($directory, $preserve = false) {
		if(!$this->_is_dir($directory)) {
            return false;
        }
        $items = $this->_file_system($directory);
        foreach($items as $item) {
            if ($item->isdir() && ! $item->isLink()) {
                $this->_delete_dir($item->getPathname());
            }
            else {
               $this->_delete_file($item->getPathname());
            }
        }
        if (! $preserve) {
            third('delete_dir', $directory);
        }
        return true;
	}

	public function _mkdir($dir, $mode = 777, $recursive = true) {
		if(!$this->_is_dir($dir)) {
			if(!third('mkdir', $this->_absolute_disk($dir), $mode, $recursive)) {
				return false;
			}
		}
		return true;
	}

	public function _part($bisectrix, $content, $before = true) {
        return third('part', $content, $bisectrix, $before);
	}

	public function _function_exists($name) {
		return third('function_exists', $name);
	}

	public function _get_compiler_info() {
		return third('get_compiler_info');
	}

	public function _file_system($directory) {
        return third('file_system', $directory);
	}

	public function _bind($callback, $obj) {
		return third('bind', $callback, $obj);
	}

	public function _reflection($class) {
		return third('reflection', $class);
	}

	public function _date_time($when, $zone) {
		return third('date_time', $when, $zone);
	}

	public function _get_date_time_zone($zone) {
		return third('get_date_time_zone', $zone);
	}

	public function _get_paths($path) {
		return third('get_paths', $path);
	}

	public function _get_paths_only($path) {
		return third('get_paths_only', $path);
	}

	public function _get_time_in_milliseconds() {
		return third('get_time_in_milliseconds');
	}

	public function _get_type($element) {
		return third('get_type', $element);
	}

	public function _is_double($element) {
		return third('is_double', $element);
	}

	public function _is_float($element) {
		return third('is_float', $element);
	}

	public function _is_integer($element) {
		return third('is_integer', $element);
	}

	public function _is_numeric($element) {
		return third('is_numeric', $element);
	}

	public function _string($str, $type = 'php') {
		if($this->app->is_array($str)) {
			$str = $this->_export([
				'value' => $str,
				'quote' => "'",
				'type' => 'array',
				'escape_key' => 'body'
			]);

		}
		return third('string', $str);
	}

    public function _str_split($str) {
		return third('str_split', $str);
	}

    public function _ucwords($str) {
		return third('ucwords', $str);
	}

	public function _str_trans($one, $two) {
		return third('str_trans', $one, $two);
	}

	public function _header($str) {
		return third('header', $str);
	}

	public function _build_query($data, $prefix, $separator) {
		return third('build_query', $data, $prefix, $separator);
	}

	public function _pos($haystack, $needle) {
		return third('pos', $haystack, $needle);
	}

	public function _preg_replace($regex, $expresion, $string) {
		return third('preg_replace', $regex, $expresion, $string);
	}

	public function _replace_spaces_with_one($str) {
		return third('replace_spaces_with_one', $str);
	}

	public function _replace($search, $replace, $subject) {
		return third('replace', $search, $replace, $subject);
	}

	public function _file_put_contents($file, $contents, $lock = false) {
		return third('file_put_contents', $this->_absolute_disk($file), $contents, $lock);
	}

	public function _substr(string $string, $start = 0, $length = null) {
		return third('substr', $string, $start, $length);
	}

	public function _is_array($obj) {
		return third('is_array', $obj);
	}

	public function _parse_url($str) {
		return third('parse_url', $str);
	}

	public function _urlencode($str) {
		return third('urlencode', $str);
	}

	public function _urldecode($str) {
		return third('urldecode', $str);
	}

	public function _is_dir($dir) {
		return third('is_dir', $this->_absolute_disk($dir));
	}

	public function _is_empty($obj) {
		return third('is_empty', $obj);
	}

	public function _key_exists($k, $arr) {
		return third('key_exists', $k, $arr);
	}

	public function _file_get_contents($file, $lock = false) {
		return third('file_get_contents', $this->_absolute_disk($file), $lock);
	}

	public function _exit() {
		return third('exit');
	}

	public function _method_exists($instance, $method) {
		return third('method_exists', $instance, $method);
	}

	public function _unlink($file) {
		return third('unlink', $this->_absolute_disk($file));
	}

	public function _count($arr, $out = true) {
		if($this->_is_string($arr)) {
			if($out === true) {
				return third('count', $this->_get($arr));
			} else {
				$this->_exception('syntax', 'Syntax error');
			}
		} else {
			return third('count', $arr);
		}
	}

	public function _basename($path) {
		return third('basename', $path);
	}

	public function _cmd($commands) {
		return third('cmd', $commands);
	}

	public function _call_method($method, $arguments = []) {
		return $this->_get($method)();
	}

	public function _array_zero($arr) {
		return third('array_zero', $arr);
	}

	public function _mysqli($host, $username, $password, $database) {
		return third('mysqli', $host, $username, $password, $database);
	}

	public function _implode($sep, $arr) {
		return third('implode', $sep, $arr);
	}

	public function _explode($sep, $string) {
		return third('explode', $sep, $string);
	}

	public function _in_array($k, $arr, $absol = false) {
		return third('in_array', $k, $arr, $absol);
	}

	public function _file_append($file, $contents) {
		return third('file_append', $this->_absolute_disk($file), $contents);
	}

	public function _make_dir_force($file, $mode = 777, $recursive = true) {
		return third('make_dir_force', $this->_absolute_disk($file), $mode, $recursive);
	}

	public function _print($str) {
		return third('print_r', $str);
	}

	public function _json_encode($data) {
		return third('json_encode', $data);
	}

	public function _json_decode($data, $as_array) {
		return third('json_decode', $data, $as_array);
	}

	public function _copy_file($file, $target) {
		return third('copy_file', $this->_absolute_disk($file), $target);
	}

	public function _length($str) {
		return third('length', $str);
	}

	public function _is_object($element) {
		return third('is_object', $element);
	}

	public function _is_null($element) {
		return third('is_null', $element);
	}

	public function _is_string($element) {
		return third('is_string', $element);
	}

	public function _decoder($contents, $callback = null, $arg = null, $right_to_left = 0, $option = null) {
		$live = $this->ram[0]->live;
		$results = $this->ram[0]->client->get('decoder')->_decoder($contents, $callback, $arg, $right_to_left, $option);
		$this->_set_live($live);
		return $results;
	}

	public function _lower($str) {
		if($str == null) {
			return '';
		}
		return $this->_decoder($str, function($data) {
			if($data->first) {
				$data->disable_auto_results = 1;
			}
			if($data->type == 'alpha') {
				if($data->is_upper) {
					$data->a_o = $data->a;
				}
			}
			$data->results.=$data->a_o;
		});
	}

	public function _upper($str) {
		return third('upper', $str);
	}

	public function _file_extention($file) {
		return $this->_decoder($file, function($data) {
			if($data->decimal == 46) {
				$data->send = $data->results;
			}
		}, null, 1);
	}

	public function _file($file) {
		return third('file', $this->_absolute_disk($file));
	}

	public function _remove_spaces($str) {
		return $this->_decoder($str, function($data) {
			if($data->decimal == 9 || $data->decimal == 32) {
				$data->a_o = '';
			}
		});
	}

	public function _css_fix($value) {
		return $this->_trim($value, ';').';';
	}

	public function _trim($str, $mask = '') {
		return $this->_rtrim($this->_ltrim($str, $mask), $mask);
	}

	public function _add_modal_btn($data) {
		$html = $this->_document('html');
		$html->tag('div')->attr('class', 'cm-modal-open')->attr('data-target', $data['target'])->text($data['text'])->tag();
		return $html->done(1);
	}

	public function _add_modal($data) {
		$html = $this->_document('html');
		$html->tag('div')->attr('data-modal', $data['id'])->attr('class', 'cm-modal');
			$html->tag('div')->attr('class', 'modal-content');
				$html->tag('div')->attr('class', 'modal-header');
					$html->tag('span')->attr('class', 'close')->text('X')->tag();
					$html->tag('h2')->text('My header')->tag();
				$html->tag();
				$html->tag('div')->attr('class', 'modal-body')->text($data['contents'])->tag();
				$html->tag('div')->attr('class', 'modal-footer');
					$html->tag('h3')->text('My footer')->tag();
				$html->tag();
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _add_table($data) {
		
	}

	public function _add_icons_table($data, $by = 1) {
		$html = $this->_document('html');
		$html->tag('table')->attr('class', 'cm-table cm-fixed');
			$html->tag('tbody')->attr('class', 'cm-table-body');
			$html->tag('tr');
				for($i=0;$i<$this->_count($data);$i++) {
					$html->tag('td');
						$html->tag('div');
							$html->tag('i')->attr('class', 'material-icons')->text($data[$i]['name'])->tag();
							$html->text($data[$i]['name']);
						$html->tag();
					$html->tag();
					if($by == 3) {
						$html->tag();
						$html->tag('tr');
						$by = 0;
					} else {
						$by++;
					}
				}
			$html->tag();
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _get_between($str, $start, $end) {
		return $this->_decoder($str, function($data) {
			if($data->first) {
				$data->all = [];
				$data->start = $this->ram[0]->client->get('decoder')->_string_to_ascii_array($data->arg[0]);
				$data->end = $data->arg[1];
				$data->countstart = $this->_count($data->start);
				$data->i = 0;
				$data->conts = '';
				$data->alowed = 0;
				$data->ready = 0;
			}

			if(!$data->alowed) {
				if($data->decimal == $data->start[$data->i]) {
					if($data->countstart == $data->i + 1) {
						$data->alowed = 1;
					} else {
						$data->i++;
					}
				} else {
					$data->alowed = 0;
					$data->i = 0;
				}
			} else {
				if($data->end == $data->a_o) {
					$data->alowed = 0;
					$data->i=0;
					$data->all[] = $data->conts;
					$data->ready++;
					$data->conts = '';


				} else {
					$data->conts.=$data->a_o;
				}
			}

			if($data->last) {
				$data->send = $data->all;
			}

		}, [$start, $end]);
	}

	public function _rtrim($str, $mask = '') {
		return $this->_decoder($str, function($data) {
			if($data->last) {
				$data->string = '';
				$data->to_remove = [9, 32];
				if($data->arg !== '') {
					$data->to_remove = $this->ram[0]->client->get('decoder')->_string_to_ascii_array($data->arg);
				}
				if($this->_in_array($data->decimal, $data->to_remove)) {
					$data->a_o = '';
				} else {
					$data->stop_process = 1;
					$data->send = $data->original_contents;
				}
			} else {
				if($this->_in_array($data->decimal, $data->to_remove)) {
					if($data->string !== '') {
						$data->stop_process = 1;
					} else {
						$data->a_o = '';
					}
				}
				$data->string = $data->a_o;
			}
		}, $mask, 1);
	}

	public function _ltrim($str, $mask = '') {
		return $this->_decoder($str, function($data) {
			if($data->first) {
				$data->string = '';
				$data->to_remove = [9, 32];
				if($data->arg !== '') {
					$data->to_remove = $this->ram[0]->client->get('decoder')->_string_to_ascii_array($data->arg);
				}
				if($this->_in_array($data->decimal, $data->to_remove)) {
					$data->a_o = '';
				} else {
					$data->stop_process = 1;
					$data->send = $data->original_contents;
				}
			} else {
				if($this->_in_array($data->decimal, $data->to_remove)) {
					if($data->string !== '') {
						$data->stop_process = 1;
					} else {
						$data->a_o = '';
					}
				}

				$data->string = $data->a_o;
			}
		}, $mask);
	}

	public function _instanceof($object, $name) {
		return third('instanceof', $object, $name);
	}

	public function _int($str) {
		return third('int', $str);
	}

	public function _ord($str) {
		return third('ord', $str);
	}

	public function _build_attributes($type, $tag, $attrs) {
		$results = '';
		if($type == 'html') {
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
		}
		elseif($type == 'sql') {
			return $attrs;
		}

		return $results;
	}

	public function _build_document($data, $level = 0) {
		$contents = '';
		for($i=0;$i<$this->_count($data);$i++) {
			$item = $data[$i];
			$item['tag_lower'] = $this->_lower($item['tag']);
			$item['new_line_after'] = $this->_new_line();
			$item['new_line_before'] = $this->_new_line();
			$item['space'] = $this->_space_like_tab(($level + $item['my_space']) + $item['space_level']);

			if($this->ram[0]->control->isset('builders.code.'.$item['doctype'])) {
				$fn = $this->ram[0]->control->get('builders.code.'.$item['doctype']);
			} else {
				$fn = $this->_require_local($this->_common_path('builders/code/'.$item['doctype'].'.'.$this->_sys('clngext')), null, null);
				$fn = $this->ram[0]->control->set('builders.code.'.$item['doctype'], $fn);
			}

			$item = $fn($item, $level, $this->ram[0]->live);
			if(!$this->app->is_empty($item['items'])) {
				$item['nested'] = $this->_build_document($item['items'], $item['level']);
			}
			$contents.=$this->_get_builded_text($item);
		}
		return $contents;
	}

	public function _get_builded_text($item) {
		$contents = "";

		if($item['is_inside_with_outside']) {
				$item['tag_after'] = $item['new_line_after'].$item['space'].$item['tag_after'];

				$contents.=$item['new_line_before'];
		} else {
			if($item['nested'] !== null && $item['tag_after'] !== null && $item['tag_lower'] != 'untaged') {
				$item['tag_after'] = $item['new_line_after'].$item['space'].$item['tag_after'];
			}

			if($item['tag_before'] !== null && $item['tag_lower'] != 'untaged') {
				$contents.=$item['new_line_before'];
			}
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

	public function _fixed_bridge_method($method) {
		return $this->_get_fprefix().$this->_remove_prefix($this->_get_fprefix(), $this->_upper_to_underscore($method));
	}

	public function _mass() {
		if($this->_isset('mass')) {
			
		}
		return 1;
	}

	public function _opi($obj) {
		return $obj;
	}

	public function _add_options_menu($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		$html->tag('div')->attr('class', 'cm-box');
			$html->tag('div')->attr('class', 'cm-box-nav');
				$html->tag('div')->attr('class', 'cm-inline');
					foreach($html->get('items') as $key => $link) {
						$html->tag('a')->attr('href', $html->get('items.'.$key.'.url'))->attr('class', 'cm-btn-link'.($html->get('items.'.$key.'.active') ? ' cm-active' : ''))->text($html->get('items.'.$key.'.label'))->tag();
					}
				$html->tag();
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _add_form($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data, [
			'enctype' => 0,
			'method' => 'GET',
		]);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		$html->tag('form');
			if($html->isset('items.action')) {
				$html->attr('action', $html->get('items.action'));
			}
			if($html->isset('items.name')) {
				$html->attr('name', $html->get('items.name'));
			}
			if($html->isset('items.method')) {
				$html->attr('method', $html->get('items.method'));
				if($html->get('items.method', 'post')) {
					if($html->isset('items.enctype')) {
						$html->attr('enctype', $html->get('items.enctype'));
					} else {
						$html->attr('enctype', 'multipart/form-data');
					}
				}
			} else {
				if($html->isset('items.enctype')) {
					$html->attr('enctype', $html->get('items.enctype'));
				}
			}
			$html->tag('div')->attr('class', 'cm-form');
				$this->app->for($html->get('items.fields'), function($data) {
					$data->arg->text($data->arg->add_field($data->arg->get('items.fields.'.$data->key), $data->arg));
				}, $html);
				if($html->get('items.with_submit_button')) {
					$html->text($html->button([
						'type' => 'submit',
						'text' => 'Submit'
					]));
				}
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _require($x, $position = 'require') {
		$url = $this->_assets_url($x);
		if($this->_file($this->_get_public_path_from_host($this->_assets_path($x)))) {
			$ext = $this->_lower($this->_file_extention($url));
			if(!$this->ram[0]->config->settings->get($ext.'_'.$position)) {
				if(!$this->_in_array($url, $this->_get('this.resources.after.'.$ext.'_'.$position)) && !$this->_in_array($url, $this->_get('this.resources.before.'.$ext.'_'.$position))) {
					$this->_push('this.resources.'.$this->_get('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		} else {
			$this->_log('file not exists : '.$this->_get_public_path_from_host($this->_assets_path($x)));
		}
	}

	public function _get_flammable_paths() {
		return $this->_get('flammable.paths');
	}

	public function _interframework($callback) {
		return function($output = null) use ($callback) {
			if($this->ram === 1) {
				return $callback($output);
			}
		};
	}

	public function _add_client($username, $data) {
		$this->_set('client.'.$username, $data);
	}

	public function _array_next(int $key, array $array) {
		return $this->app->key_exists($key + 1, $array) ? $array[$key + 1] : null;
	}

	public function _array_prev(int $key, array $array) {
		return $this->app->key_exists($key - 1, $array) ? $array[$key - 1] : null;
	}


	public function _flammable($data) {

		$data['flammable'] = [
			'local' => 0,
			'missions' => [
				'admin',
				'brosta',
				'install',
			],
			'clngext' => 'php',
			'paths' => [
				'app' => 'app',
				'boot' => 'boot',
				'views' => 'views',
				'config' => 'config',
				'public' => 'public',
				'provider' => 'provider',
				'controllers' => 'controllers',
				'storage' => 'storage',
			]
		];



		$data['unique_ids'] = [0,1,2,3,4,5,6,7,8,9];
		$data['namespace']['name'] = $data['namespace'][0];
		$data['namespace']['path'] = $data['namespace'][1];
		$data['namespace']['signal_name'] = 'signal';
		$data['namespace']['manager_name'] = 'manager';
		$data['namespace']['signal'] = $data['namespace']['name'].'\signal';
		$data['namespace']['manager'] = $data['namespace']['name'].'\manager';

		return $data;
	}

	public function _load_ram($obj, $method, $arguments) {
		if($method == '_main') {
			if($obj === $arguments[0] && $obj->key === '0000-0000-0000-0000-0000' && $arguments[0]->key === '0000-0000-0000-0000-0000') {
				if($this->ram === 1) {
					return $obj->instance($obj, 'brosta\interframework\manager', $method, $arguments);
				}

				if(isset($obj->verification) && $obj->verification === 1) {
					$this->app = $obj->instance('brosta', $obj, 1);
				} else {
					$this->app = $this->ram[0]->brosta;
				}

				$this->ram[0]->client = $obj;
				foreach($obj::$rom as $id => $data) {
					if($id == '0000-0000-0000-0000-0000') {
						$data['tmp'] = $this->ram[0]->client->flammable($data['data']);

						$this->ram[$id] = [
							'id' => '0000-0000-0000-0000-0000',
							'type' => 'client',
							'data' => $data['tmp']
						];
					} else {
						$this->ram[$id] = [
							'id' => $id,
							'type' => $id,
							'data' => $data['tmp']
						];
					}
				}

				if(!$this->ram[0]->client->ready('ram')) {

					if(!$this->ram[0]->client->isset('decoder')) {
						$this->ram[0]->client->set('decoder', new decoder($this->ram[0]->client));
					}

					$this->ram[0]->client->alias_load([
						'db',
						'html',
						'live',
						'temp',
						'disk',
						'data',
						'route',
						'config',
						'monitor',
						'control',
						'request',
						'response'
					]);
					$this->ram[0]->client->preparing = 0;
				} else {
					
				}
			} else {
				$this->app->exit();
			}
		}
	}

	public function _instance($id, $name = null, $method = null, $arguments = null) {
		if($name) {
			if($this->ram = 2) {
				$this->ram = [new stdClass];
				$this->ram[0]->brosta = new $name('brosta');
				$this->ram[0]->brosta->load_ram($id, $method, $arguments);
				return 1;
			} else {
				return isset($this->ram[0]->{$id}) ? $this->ram[0]->{$id} : null;
			}
		} else {
			$name = $this->ram['0000-0000-0000-0000-0000']['data']['namespace']['manager'];
		}

		return new $name($id);
	}

	public function _prepare($obj, $method, $arguments) {
		if($this->ram = 1) {
			if($obj === $arguments[0]) {
				$this->_load_ram($obj, $method, $arguments);
			}
		}
	}

	public function _app($alias = null) {
		return $this->ram[0]->client->get('static.alias.'.$alias);
	}

	public function _database_is_installed($level = 0, $setch) {
		return false;
	}

	public function _alias_load($name) {
		if(!$this->app->is_array($name)) {
			$name = [$name];
		}
		for($i=0;$i<$this->app->count($name);$i++) {
			$this->ram[0]->{$name[$i]} = $this->app->controlled($name[$i]);
		}
	}

	public function _alias_unload($name) {
		if(!$this->app->is_array($name)) {
			$name = [$name];
		}
		for($i=0;$i<$this->app->count($name);$i++) {
			unset($this->ram[0]->{$name[$i]});
		}
	}

	public function _fixroot($path) {
		if($this->app->first_in($path, $this->app->dirsep()) || $this->app->first_in($path, $this->app->urlsep())) {
			$this->ram[0]->control->set('is_real_path', 0);
		} else {
			$this->ram[0]->control->set('is_real_path', 1);
		}

		$path = $this->app->to_dirsep($path);

		if($this->ram[0]->control->get('is_real_path', 0)) {
			if($path == '') {
				$path = $this->app->dirsep();
			} else {
				$path = $this->app->dirsep().$path.$this->app->dirsep();
			}
		} else {
			if($path) {
				$path = $path.$this->app->dirsep();
			}
		}

		return $path;
	}

	public function _disk_sep_finder($location) {
		$file = $location.$this->_dirsep().'.access';
		if(third('file', $file)) {
			return $location;
		}
		elseif(third('file', $this->_dirsep().$file)) {
			return $this->_dirsep().$location;
		} else {
			return $this->_dirsep().$this->_unique_numbers(15);
		}
	}

	public function _infinity() {

		$brosta = $this->_globals('brosta');

		if(!isset($brosta['grab'])) {
			$brosta['grab'] = [
				[
					'name' => 'ini',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'presets' => [
							'local' => 1,
							'string_controller' => 1,
							'router' => 1,
							'vendor' => 1,
							'view' => 1,
							'safe_mode' => 1,
							'bridge' => 1,
							'auto_make_view' => 1,
							'auto_make_controller' => 1
						]
					],
				],
				[
					'name' => 'database',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
					    'default' => 'mysql',
						'providers' => [
							'brosta' => [
								'database' => 'default',
								'host' => 'localhost',
								'username' => 'db_username',
								'password' => 'db_password',
								'charset' => 'utf8mb4',
								'collation' => 'utf8mb4_unicode_ci',
								'prefix' => '',
							],
							'pdo' => [
								'provider' => 'mysql',
								'host' => 'localhost',
								'database' => 'test',
								'username' => 'root',
								'password' => '',
								'collation' => 'utf8mb4_unicode_ci',
								'prefix' => '',
							],
							'mysql' => [
								'host' => 'localhost',
								'database' => 'brosta',
								'username' => 'root',
								'password' => '',
								'charset' => 'utf8mb4',
								'collation' => 'utf8mb4_unicode_ci',
								'prefix' => '',
							],
						],
					],
				],
				[
					'name' => 'document',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'html' => [
							'type'						=> 'html',
							'bodyclass'					=> [
								'cm-body',
								'cm-app-black',
								'cm-autonomous'
							],
							'resources' => [
								'before' => [
									'css_require' => [
										'/assets/app.css',
									],
									'js_require' => [
										'/assets/plugins/jquery.min.js',
										'/assets/signal.js',
										'/assets/manager.js',
										'/assets/app.js',
									],
									'css_auto_view' => [],
									'js_auto_view' => [],
									'css_dynamic' => [],
									'js_dynamic' => [],
									'scripts' => [],
									'meta' => [],
									'style' => [],
									'html' => [],
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
									'html' => [],
								]
							],
						],
						'php' => [
							'type'						=> 'php',
							'file_ext'					=> 'php',
						],
						'javascript' => [
							'type'						=> 'javascript',
							'file_ext'					=> 'js',
						],
						'sql' => [
							'type'						=> 'sql',
							'file_ext'					=> 'sql',
						],
						'autocad_dot_net' => [
							'type'						=> 'dot_net',
							'file_ext'					=> 'dot_net',
						]
					],
				],
				[
					'name' => 'autoloader',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'applications' => [
							[
								'name' => 'brosta/brosta',
								'authors' => [
									'John Stamoutsos'
								],
								'autoload' => [
									'psr-4' => [
										'App' => 'app'
									]
								],
								'require' => [
									'Brosta' => '*'
								]
							],
						],
						'providers' => [
							[
								'name' => 'brosta/interframework',
								'authors' => [
									'John Stamoutsos'
								],
								'autoload' => [
									'psr-4' => [
										'Brosta' => 'src'
									]
								]
							],
						],
						'loaded' => [],
					],
				],
				[
					'name' => 'os',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'operating_system' => 'windows:10',
					],
				],
				[
					'name' => 'app',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'mode' => 'build_my_self',
						'installed' => 'client',
						'philosophy' => 1,
						'philosophy_as' => 'common',
						'load_there_or' => 'there',
						'version' => '1.1',
						'license_id' => '0000-0000-0000-0000-0000',
						'code_lng' => 'php',
						'default_code_lng' => 'html',
						'client_lng' => 'php',
						'subclient_lng' => 'js',
						'start_code_space_level' => 0,
						'name' => 'Signal',
						'namespace' => 'brosta\interframework',
						'username' => 'john.stamoutsos@gmail.com',
						'root' => '',
						'root_auto' => true,
						'absolute_root' => '',
						'url' => 'http://brosta.org',
						// -----------------------------------------
						'uri' => '',
						'host' => 'localhost',
						'method' => 'GET',
						'protocol' => 'http',
						// -----------------------------------------
						'url_auto' => true,
						'languages' => [
							'default' => 'en',
							'items' => [
								'en',
								'gr',
							]
						],
						'allowed_execute_lng_codes' => [
							'linux',
							'windows',
							'javascript',
							'c++',
							'jquery',
							'lisp',
							'php'
						],
						'controllers' => [
							'tools' => []
						]
					],
				],
				[
					'name' => 'settings',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'mode' => 'server',
						'full_document_url' => 0,
						'allowed_doctypes' => [
							'html',
							'php',
							'javascript',
						],
						'allowed_protocols' => [
							'http',
							'https',
							'ftp',
							'ftps'
						],
						'allowed_methods' => [
							'get',
							'post',
							'files',
							'cookie',
							'server'
						],
						'vendor' => '{
    "name": "brosta/app",
    "description": "Brosta Interframework",
    "type": "project",
    "require": {
        "brosta/interframework": "dev-master",
        "guzzlehttp/guzzle": "~5.3.1||~6.0",
        "guzzlehttp/psr7": "^1.2"
    },
    "autoload": {
        "psr-4": {
            "App\\\\": "app/"
        }
    },
    "license": "MIT",
    "authors": [
        {
            "name": "John Stamoutsos",
            "email": "john.stamoutsos@gmail.com"
        }
    ]
}

'
				],
				],
				[
					'name' => 'monitor',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'display_model' => 'standard-2048:1980',
						'device' => 'desktop',
						'standard_style_css' => '.cm-app {margin: 0 auto;width:1200px;min-width: auto};',
						'standard_js' => '',
						'top_comment' => '<!-- Brosta Interframework Author: John Stamoutsos - john.stamoutsos@gmail.com -->',
						'doctype_lang' => 'en',
						'meta' => [
							'charset' => 'utf-8',
						]
					],
				],
				[
					'name' => 'cache',
					'sets' => 'returned',
					'jsoned' => 0,
					'absolute' => 0,
					'data' => [
						'load_from_cache' => 1,
						'recache' => 1,
						'cache_file_type' => 'html',
					],
				]
			];
		}

		$brosta = $this->_array_replace([
			[
				'name' => 'ini',
				'sets' => 'empty',
			],
			[
				'name' => 'database',
				'sets' => 'empty',
			],
			[
				'name' => 'document',
				'sets' => 'empty',
			],
			[
				'name' => 'autoloader',
				'sets' => 'empty',
			],
			[
				'name' => 'os',
				'sets' => 'empty',
			],
			[
				'name' => 'app',
				'sets' => 'empty',
			],
			[
				'name' => 'settings',
				'sets' => 'empty',
			],
			[
				'name' => 'monitor',
				'sets' => 'empty',
			],
			[
				'name' => 'cache',
				'sets' => 'empty',
			]
		], $brosta['grab']);

		return $brosta;
	}

	public function _load_config($configs, $location) {
		for($i=0;$i<$this->app->count($configs);$i++) {

			$file = $location.$this->app->dirsep().$configs[$i]['name'].'.'.$this->app->sys('clngext');

			$absolute = 'config';

			if($configs[$i]['sets'] == 'empty') {
				$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled($configs[$i]['name']));
			}
			elseif($configs[$i]['sets'] == 'variabled') {
				if($configs[$i]['absolute']) {
					$absolute = $configs[$i]['name'];
				}
				$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled([], $configs[$i]['name']));
				third('require_local', $file , [$absolute => $this->ram[0]->config->get($configs[$i]['name'])]);
				if($configs[$i]['jsoned']) {
					foreach($this->ram[0]->config->get($configs[$i]['name'])->all() as $key => $value) {
						$this->ram[0]->config->get($configs[$i]['name'])->set($key, $this->app->brosta_decode($value, true));
					}
				}
			}
			elseif($configs[$i]['sets'] == 'returned') {
				if($configs[$i]['data']) {
					$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled($configs[$i]['data'], $configs[$i]['name']));
				} else {
					$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled(third('require_local', $file), $configs[$i]['name']));
				}
			}
			elseif($configs[$i]['sets'] == 'array') {
				$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled(third('require_local', $file), $configs[$i]['name']));
			}
			elseif($configs[$i]['sets'] == 'jsoned') {
				$this->ram[0]->config->set($configs[$i]['name'], $this->app->controlled($this->app->brosta_decode(third('file_get_contents', $file), true)));
			}
		}

		$this->ram[0]->client->set_ini_presets([
			'local' => 1,
			'string_controller' => 0,
			'router' => 1,
			'vendor' => 1,
			'view' => 1,
			'safe_mode' => 0,
			'bridge' => 1,
			'auto_make_view' => 1,
			'auto_make_controller' => 1
		]);

	}

	public function _download($url, $file) {
		$ch = curl_init($url);
		$fp = fopen($file, "w");

		curl_setopt($ch, CURLOPT_FILE, $fp);
		curl_setopt($ch, CURLOPT_HEADER, 0);

		curl_exec($ch);
		if(curl_error($ch)) {
		    fwrite($fp, curl_error($ch));
		}
		curl_close($ch);
		fclose($fp);
	}

	public function _stringel($array, string $string) {
		if($this->_is_string($array)) {
			$array = $this->_explode($string, $array);
		}
		$results = '';
		for($i=0;$i<$this->_count($array);$i++) {
			if($results) {
				$results.=$string.' ';
			}
			$results.=$array[$i];
		}
		return $results;
	}

	public function _reset_ram($app) {

		$this->app->public_root = $this->app->fixroot($this->app->public_root);
		$config_path = $this->app->config_path();

		if($this->app->public_root == $this->app->dirsep()) {
			$config_path = $this->app->dirsep().$config_path;
		} else {
			$config_path = $this->app->public_root.$config_path;
		}

		$this->app->load_config($this->app->infinity(), $config_path);


		if($this->ram[0]->config->app->get('root_auto')) {
			$this->ram[0]->disk->set('root', $this->app->public_root);
		} else {
			$this->ram[0]->disk->set('root', $this->app->fixroot($this->ram[0]->config->app->get('root')));
		}

		$this->ram[0]->disk->set('absolute_root', $this->ram[0]->config->app->get('absolute_root'));

		$this->app->set_ready('config');

		$this->app->boot_autoloaders();

		$this->app->set_request([
			'get' => $_GET,
			'post' => $_POST,
			'files' => $_FILES,
			'cookie' => $_COOKIE,
			'server' => $_SERVER
		]);

		$this->app->router();

		if(!$this->ram[0]->config->app->get('license_id', $this->ram[0]->client->key)) {
			$this->app->exception('license_id', 'The license id : '.$this->ram[0]->client->key.' seems to be incorrect');
		}

		if($this->app->ini('vendor')) {
			$this->app->vendor_ready();
		}

		$this->ram[0]->db->set('settings', $this->ram[0]->config->database->get('providers.'.$this->ram[0]->config->database->get('default')));

		$this->ram[0]->control->set('is_started', 1);

		$this->app->reset();
		$this->app->preconclude();
		$this->app->set_ready('ram');
	}

	public function _http($options) {
		return $this->bridge($options['url'], $options);
	}

	public function _add_logo($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, null, $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(8);

		$html->tag('a')->attr('href', $this->app->url($this->app->visitor_is_with()));
			$html->tag('div')->attr('class', 'cm-logo');
				if(!$html->items) {
					$html->tag('span')->attr('class', 'bro')->text('Brost')->tag();
					$html->tag('span')->attr('class', 'sta')->text('ά')->tag();
				}
			$html->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _add_search_box($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		$url = $this->app->visitor_is_with('search');

		$html->tag('div')->attr('class', 'cm-search-box');
			if($html->isset('items.style')) {
				$html->attr('style', $html->get('items.style'));
			} else {
				$html->attr('style', 'width: 680px;margin-left:27px;');
			}

			$html->text($this->ram[0]->client->add_form([
				'name' => 'search',
				'action' => $this->app->url($url, $this->ram[0]->client->gall()),
				'fields' => [
					[
						'style' => 'font-size:18px;height:64px;line-height:34px;border-left-width:0px;border-right-width:0px;border-top-width:0px;border-bottom-width:0px;padding:7px 35px;',
						'caption' => 'q',
						'type' => 'input',
						'input_type' => 'text',
						'value' => '',
						'default_value' => $this->ram[0]->client->g('q'),
						'valid' => 'required',
						'errors' => [],
					],
				],
			], $html));
		$html->tag();
		return $html->done(1);
	}

	public function _add_item($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->tag('div')->attr('class', 'id-'.$data->item->id)->attr('class', 'cm-'.$data->item->name);
			//
		$html->tag();

		return $html->done(1);
	}

	public function _search_results_item($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(9);

		if($html->isset('items.items') && $html->count('items.items')) {
			$html->tag('div');
				if($html->isset('items.style')) {
					$html->attr('style', $html->get('items.style'));
				}

				for($i=0;$i<$this->app->count($html->get('items.items'));$i++) {
					$html->tag('div')->attr('class', 'cm-search-item');
						$html->tag('div')->attr('class', 'cm-search-item-contents');
							$html->tag('div')->attr('class', 'cm-search-item-top');
								$html->tag('a')->attr('href', $html->get('items.items.'.$i.'.link'));
									if($html->get('items.items.'.$i.'.open_new_tab')) {
										$html->attr('target', '_blank');
									}
									//$html->tag('br')->tag();

									$html->tag('h3')->attr('class', 'cm-search-item-title');
										$html->text($html->get('items.items.'.$i.'.title'));
									$html->tag();
									$html->text($html->get('items.items.'.$i.'.info'));
								$html->tag();
							$html->tag();
							$html->tag('div')->attr('class', 'cm-search-item-bottom');
								$html->text($html->get('items.items.'.$i.'.description'));
							$html->tag();
						$html->tag();
					$html->tag();
				}
			$html->tag();
		}

		return $html->done(1);
	}

	public function _mb_replace($heystack, $needle, $source) {
		return $this->ram[0]->client->get('decoder')->_mb_replace($heystack, $needle, $source);
	}

	public function _add_info_table($data, $titles = null) {
		$html = $this->app->document('html');
		$html->tag('div')->attr('class', 'cm-list cm-list-table');
			if($titles) {
				$html->tag('div')->attr('class', 'cm-list-row cm-title cm-clearfix');
					$html->tag('div')->attr('class', 'cm-list-link');
						foreach($titles as $title) {
							$html->tag('span')->attr('class', 'cm-list-link-auto')->text($title['label'])->tag();
						}
					$html->tag();
				$html->tag();
			}
			foreach($data as $item) {
				$html->tag('div')->attr('class', 'cm-list-row cm-clearfix');
					$html->tag('a')->attr('href', $this->_url('admin/controllers/edit', ['id' => $item['id']]))->attr('class', 'cm-list-link');
						$html->tag('span')->attr('class', 'cm-list-link-auto')->text($item['name'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-auto')->text($item['url'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-auto')->text($item['alias'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-auto')->text($item['icon'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-auto')->text($item['philosophy'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-right');
							$html->tag('span')->attr('class', 'cm-edit')->text('Edit')->tag();
						$html->tag();
					$html->tag();
				$html->tag();
			}
		$html->tag();
		return $html->done(1);
	}

	public function _add($algorithm, $items = [], $type = 'html') {
		return $this->app->document($type, $algorithm, $items)->done(1);
	}

	public function _add_formable_list($data) {
		$html = $this->_document('html');
		$html->tag('div')->attr('class', 'cm-list');
			foreach($data['sections_labels'] as $section) {
				$html->tag('div')->attr('class', 'cm-list-row cm-clearfix'.($section['url'] == $this->_g('section') ? ' opened' : ''));
					$html->tag('a')->attr('data-id', $html->unique_numbers(8))->attr('class', 'cm-list-link')->attr('href', $this->_url($data['action'], ['section' => $section['url']]));
						$html->tag('span')->attr('class', 'cm-list-link-left')->text($section['label'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-center')->text($section['value'])->tag();
						$html->tag('span')->attr('class', 'cm-list-link-right');
							$html->tag('span')->attr('class', 'cm-left cm-list-loader');
								$html->tag('img')->attr('class', 'cm-loader')->attr('src', '/assets/img/loaders/wait.gif')->tag();
							$html->tag();
							$html->tag('span')->attr('class', 'cm-edit')->text('Edit')->tag();
						$html->tag();
					$html->tag();
					$html->tag('div')->attr('class', 'cm-data');
						if($section['url'] == $this->_g('section')) {
							$html->is_inside_with_outside(1);
							$html->text($this->_add_form($data, $html));
						}
					$html->tag();
				$html->tag();
			}
		$html->tag();
		return $html->done(1);
	}

	public function _add_menu_button($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(8);

		$html->tag('div')->attr('class', 'cm-text-icon');
			$html->tag('i')->attr('class', 'material-icons')->text('menu')->tag();
		$html->tag();
		return $html->done(1);
	}

	public function _add_user_menu($data = null, $live = null) {

		$html = $this->app->if_is_inside_build($live, 'html', $data);

		$html->is_inside_with_outside(1);
		$html->set_space_level(8);

		$html->tag('div')->attr('style', 'width:32px;height:32px;background-color:#ffffff;border-radius:50%;float:right;overflow:hidden');
			$html->tag('img')->attr('style', 'width:32px;height:32px;')->attr('src', '/assets/img/user.jpg')->tag();
		$html->tag();
		return $html->done(1);
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

	public function _find($key) {

		if(_isset('window.brosta')) {
			return _get('window.brosta')->get($key);
		}

		$brosta = $this->ram[0]->client->set('window.brosta', $this->app->controlled());

		foreach($this->ram[0] as $property => $value) {
			if($this->app->is_object($value)) {
				if($this->app->instanceof($value, $this->ram[0]->client->get('namespace.manager'))) {
					$value = $value->all();
					if($this->app->is_null($value)) {
						$brosta->set($property, []);
					} else {
						if($this->app->is_array($value)) {
							$brosta->set($property, $value);
						}
					}
				} else {
					$brosta->set($property, $value);
				}
			}
		}
		return $brosta->get($key);
	}

	public function _variabled($str) {
		return $this->_trim($this->_replace('-', '_', $str), '_');
	}

	public function _get_window() {
		return _controlled($this->app('window')->all());
	}

	public function _call_user_func_array($object, $method = null, $arguments = null) {
		return third('call_user_func_array', [$object, $method], $arguments);
	}

	public function _diamesolavitis($obj, $method, $arguments) {
		if($method != '_diamesolavitis') {
			// -----------------------------------------------------------------------------
			if($method == '_main') {
				$this->_prepare($obj, $method, $arguments);
			}
			// -----------------------------------------------------------------------------

			$this->_set_live($obj);

			if($this->_method_exists($this, $method)) {
				$results = $this->_call_user_func_array($this, $method, $arguments, 1);
				$this->_set_live($this->ram[0]->client);

				if(!$this->app->is_array($results) && $this->_is_object($results)) {
					if($this->_instanceof($results, $this)) {
						return $obj;
					} else {
						return $results;
					}
				} else {
					return $results;
				}
			} else {
				$this->_present('__-->[ Not exists... '.$method.'_'.$obj->key.']<--__');
			}

		} else {
			$this->_present('__-->[ Diamesolavitis say... '.$method.'_'.$obj->key.']<--__');
		}
	}

	public function bridge($obj, $method, array $arguments = [], $injector = null) {
		if(!$this->_is_string($obj)) {
			$method = $this->_fixed_bridge_method($method);
			if($this->ram === 1 || $obj->key === 'brosta') {
				// CORE -----------------------------------------------------------
				$results = call_user_func_array([$this, $method], $arguments);
				if(!is_array($results) && is_object($results)) {
					if($results instanceof $this) {
						return $obj;
					} else {
						return $results;
					}
				} else {
					return $results;
				}
				// END CORE -------------------------------------------------------
			} else {
				return $this->_diamesolavitis($obj, $method, $arguments, $injector);
			}
		} else {
			if($obj === $method['url']) {
				if($this->_is_started()) {
					return $this->_interface(null, [
						'body'		=> $this->ram[0]->response->isset('body')		? $this->ram[0]->response->get('body')			: '',
						'status'	=> $this->ram[0]->response->isset('status')		? $this->ram[0]->response->get('status')		: 500,
						'headers'	=> $this->ram[0]->response->isset('headers')	? $this->ram[0]->response->get('headers')		: [],
						'protocol'	=> $this->ram[0]->response->isset('protocol')	? $this->ram[0]->response->get('protocol')		: '1.1',
					], $method, null, 'response');
				} else {
					//
				}
			}
		}
	}


}

?>
