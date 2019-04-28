<?php

namespace Brosta;

use FilesystemIterator;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;

use Closure;
use stdClass;

ini_set('max_execution_time', 2000);
ini_set('memory_limit', '-1');

class Signal {

	private $memory = [];
	private $unique_ids = [];

	public function construct($root, $server = null, $install = '/') {

		$this->reset();

		if($server['uri']) {
			if($this->sub($server['uri'], 0, 1) == $this->fslash()) {
				if($server['uri'] == $this->fslash()) {
					$this->set('request.uri', $server['uri']);
				} else {
					if($this->uri_is_safe($server['uri'])) {
						$this->set('request.uri', $this->sub($server['uri'], 1));
					} else {
						$this->fail('Unsafe uri');
					}
				}
			} else {
				$this->fail('Incorrect uri');
			}
		} else {
			$this->fail('Request uri is empty');
		}

		$this->set('request.docs', $server['docs']);
		$this->set('request.scheme', $server['scheme']);
		$this->set('request.query', $server['query']);
		$this->set('server.gateway', $server['gateway']);
		$this->set('server.server_ip', $server['server_ip']);
		$this->set('server.remote_ip', $server['remote_ip']);
		$this->set('server.server_port', $server['server_port']);
		$this->set('server.server_protocol', $server['server_protocol']);
		$this->set('server.server_software', $server['server_software']);

		$this->set('http.host', $server['host']);
		$this->set('http.agent', $server['agent']);
		$this->set('http.accept', $server['accept']);
		$this->set('http.connection', $server['connection']);
		$this->set('http.accept_encodidng', $server['accept_encodidng']);
		$this->set('http.accept_languange', $server['accept_languange']);

		$this->set('request.get', $server['get']);
		$this->set('request.post', $server['post']);
		$this->set('request.files', $server['files']);
		$this->set('request.cookies', $server['cookies']);

		if($this->undefined('disk.local')) {
			$this->set('disk.local', $root);
			$this->set('install.redirect', $install);
		}

		if($this->get('disk.local')) {

			if($this->include_exists('_common/config/settings')) {
				$this->memory = $this->merge($this->memory, $this->include('_common/config/settings'));
				$this->signal();
			} else {
				if($this->isDir($this->storage('manufacturer/_common'))) {
					if($this->isset('install.redirect') && $this->get('install.redirect')) {
						if($this->copy_dir($this->storage('manufacturer/_common'), $this->project('_common'))) {
							$this->redirect($this->get('install.redirect'));
						}
					}
					$this->Fail('Fattal error: System can not installed. The argument 3 are missing to redirect after instalation from construct');
				} else {
					$this->Fail('Server is down');
				}
			}
		}

	}

	public function signal() {

		$this->request();
		$this->setView($this->to_bslash($this->get('request.library').'/'.$this->get('request.show')));

		$this->setStartCodeSpaceLevel(2);
		$this->setAfterOrBefore('after');
		$this->include($this->get('view'));
		$this->setText($this->finalize());

		$this->setAfterOrBefore('before');
		$this->setStartCodeSpaceLevel(0);
		$this->include('_common/resources');
		$this->setAfterOrBefore('after');
		$this->resources();
		$this->setAfterOrBefore('before');
		$this->monitor();
		$this->setText($this->finalize());
		$this->send();
	}

	public function setView($view) {
		$this->set('view', $view);
	}

	public function setStartCodeSpaceLevel($level) {
		$this->set('this.start_code_space_level', $level);
	}

	public function setAfterOrBefore($switch) {
		$this->set('after_or_before', $switch);
	}

	public function setText($text) {
		$this->set('text', $text);
	}

	public function monitor() {
		if($this->isset('monitor.info') && $this->get('monitor.info')) {
			$this->text($this->get('monitor.info'));
		}
		if($this->isset('monitor.doctype')) {
			if($this->get('monitor.doctype', 'html')) {
				$this->tag('doctype')->attr('html')->tag();
				$this->tag('html');
					if($this->isset('monitor.doctype_lang')) {
						$this->attr('lang', $this->get('monitor.doctype_lang'));
					}
					$this->tag('head');
						$this->tag('meta')->attr('charset', 'utf-8')->tag();
						$this->tag('meta')->attr('name', 'viewport')->attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->tag();
						$this->tag('meta')->attr('httpequiv', 'Content-Type')->attr('content', 'text/html; charset=UTF-8')->tag();
						$this->tag('title')->attr('id', 'pageTitle')->text($this->get('page.title'))->tag();
						$this->tag('meta')->attr('name', 'John Stamoutsos')->attr('content', 'Brosta')->tag();
						$this->tag('meta')->attr('id', 'domain')->attr('content', 'My domain')->tag();
						$this->loadComponents(['css']);
					$this->tag();
					$this->tag('body');

						$this->tag('div')->attr('style', 'position:absolute;top:0;left:0;bottom:0;right:0;width:100%;height:100%;z-index:1;');
							$this->tag('img')->attr('style', 'width:100%;height:100%')->attr('src', 'http://localhost/assets/img/brosta-words-colored-spray.png')->tag();
						$this->tag();

						$this->tag('div')->attr('style', 'position:absolute;top:0;left:0;bottom:0;right:0;width:100%;height:100%;z-index:2;');
							$this->text($this->get('text'));
						$this->tag();

						$this->loadComponents(['js']);
						$this->loadTheScriptsComponents();
					$this->tag();
				$this->tag();
			}
		}
	}

	public function unique_id($str = '', $id = null, $length = 5) {
		$crypt = ['A','a','B','b','C','c','D','d','E','e','F','f','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z','1','2','3','4','5','6','7','8','9','0'];
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
				return $this->unique_id($str, $id, $length);
			}
			if(in_array($id, $this->unique_ids)) {
				return $this->unique_id($str, $id, $length);
			}

			$this->unique_ids[] = $id;

			if($str) {
				$id = $str.$id;
			}
			return $id;
		}
	}

    public function uri_is_safe($uri) {
		return true;
    }

    public function login_ok()
    {
		return $this->license_id == $this->licenses['BROSTA'];
    }

    public function lisense_ok($lisense) {
		return $this->value('this.license_id', $lisense);
    }

    public function first_in($haystack, $needle) {
		if($needle !== '' && $this->pos($haystack, 0, $this->length($needle)) === (string) $needle) {
			return true;
		}
        return false;
    }

    public function last_in($haystack, $needle) {
		if($this->sub($haystack, -$this->length($needle)) === (string)$needle) {
			return true;
		}
        return false;
    }

    public function contains_in($haystack, $needle) {
		if($needle !== '' && $this->pos($haystack, $needle) !== false) {
			return true;
		}
        return false;
    }

    public function lower($str) {
		return strtolower($str);
	}

    public function explode($separator, $string) {
    	return explode($separator, $string);
	}

	public function key_exists($name, $array) {
		foreach($array as $key => $value) {
			if($key == $name) {
				return true;
			}
		}
		return false;
	}

	public function isMethodPost() {
		return $this->get('request.method', 'post');
	}

	public function isMethodGet() {
		return $this->get('request.method', 'get');
	}

	public function array_single_to_multidimentional($array, $count = 0, $current = 0) {
	    if($count - 1 === $current) {
	        $array = $array[$current];
	    } else {
	    	$array = [$array[$current] => $this->array_single_to_multidimentional($array, $count, $current + 1)];
	    }
	    return $array;
	}

	public function merge($defaults, $replaces) {

		if(!$this->is_array($defaults)) {
			return $defaults;
		}

		foreach($replaces as $key => $value) {
			if(!isset($defaults[$key]) || (isset($defaults[$key]) && !$this->is_array($defaults[$key]))) {
				$defaults[$key] = [];
			}
			if($this->is_array($value)) {
				$value = $this->merge($defaults[$key], $value);
			}
			$defaults[$key] = $value;
		}

		return $defaults;
	}

	public function is_array($element) {
		return is_array($element) ? 1 : 0;
	}

    public function undefined($key) {
		return $this->isset($key) == 0;
	}

	public function to_bslash($str) {
		return $this->trim($this->replace(['/', '\\'], $this->bslash(), $str), $this->bslash());
	}

	public function replace($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}

	public function trim($str, $character_mask = null) {
		if($this->is_null($character_mask)) {
			return trim($str);
		} else {
			return trim($str, $character_mask);
		}
	}

	public function is_null($element) {
		return is_null($element);
	}

	public function disk($str = '') {
		$path = $this->trimright($this->get('disk.local'), $this->bslash());
		if($str) {
			$path = $path.$this->bslash().$this->to_bslash($str);
		}
		return $path;
	}

    public function trimright($source, $sym = null) {
    	if($this->is_null($sym)) {
    		return rtrim($source);
    	} else {
			return rtrim($source, $sym);
    	}
	}

    public function make_dir($dir, $mode = 0755, $recursive = true) {
    	if(!$this->isDir($dir)) {
        	if(mkdir($dir, $mode, $recursive)) {
				$this->set('make_dir.success', 1);
        	} else {
    			$this->set('make_dir.success', 0);
    		}
        } else {
        	$this->set('make_dir.already_exists', 1);
        }
	}

	public function include_exists($file) {
		return $this->file_exists($this->project($file.'.php'));
	}

	public function file_exists($file) {
		return file_exists($file);
	}

	public function get_include_contents($file) {
		return $this->file_contents($this->project($file.'.php'));
	}

	public function include($file, $brosta = 0) {
		$file = $this->project($file.'.php');
		if(!$this->inArray($file, $this->get('include'))) {
			$this->push('include', $file);
		}
		return require($file);
	}

	public function reset() {

		$this->set(
			'this', [
				'license_id'				=> 'GDFS-HSKA-OLEK-OWTD',
				'on'						=> [],
				'document'					=> [],
				'bodyclass'					=> [],
				'index'						=> 0,
				'prev_index'				=> 0,
				'next_index'				=> 0,
				'level'						=> 0,
				'prev_level'				=> 0,
				'next_level'				=> 0,
				'unclosed_tags'				=> 0,
				'scripts'					=> [],
				'form'						=> [],
				'has_open_tag'				=> 0,
			    'doctype'					=> 'html',
				'tag'						=> '',
				'text'						=> '',
				'attr'						=> [],
				'defineds'					=> [],
				'append_before_tag'			=> [],
				'append_after_tag'			=> [],
				'append_before_text'		=> [],
				'append_after_text'			=> [],
				'nested'					=> '',
				'contents'					=> '',
				'open_tag'					=> '<',
				'close_tag'					=> '>',
				'tag_after'					=> '',
				'tag_before'				=> '',
				'start_code_space_level'	=> 0,
				'resources'	=> [
					'before' => [
						'css_require'		=> [],
						'js_require'		=> [],
						'css_auto_view'		=> [],
						'js_auto_view'		=> [],
						'css_dynamic'		=> [],
						'js_dynamic'		=> [],
						'scripts'			=> [],
						'meta'				=> []
					],
					'after' => [
						'css_require'		=> [],
						'js_require'		=> [],
						'css_auto_view'		=> [],
						'js_auto_view'		=> [],
						'css_dynamic'		=> [],
						'js_dynamic'		=> [],
						'scripts'			=> [],
						'meta'				=> []
					]
				],
				'items'						=> [],
			]
		);
		$this->set('pistirio', 'html');
		$this->set('include', []);
		$this->set('unique_ids', []);
		$this->set('new', $this->get('this'));
		$this->new_tag();
	}

	public function new_tag() {
		$this->set('tag', $this->get('new'));
		$this->set('this.prev_index', $this->get('this.index'));
		$this->set('this.prev_level', $this->get('this.level'));
		$this->set('this.index', $this->get('this.index') + 1);
		$this->set('this.has_open_tag', 0);
	}

	public function request() {

		$uri = $this->get('request.uri');

		$this->set('request.library', 'desktop');
		$this->set('request.show', 'index');
		$this->set('request.dynamic_params', []);

		if($this->get('request.uri') != $this->fslash()) {
			$uri = $this->explode($this->fslash(), $this->get('request.uri'));
			if(isset($uri[0])) {
				$this->set('request.library', $uri[0]);
				unset($uri[0]);
				if(isset($uri[1])) {
					$this->set('request.show', $uri[1]);
					unset($uri[1]);
					if(isset($uri[2])) {
						$this->set('request.uri_params', $this->is_empty($uri) ? false : $this->array_fix($uri));
					}
				}
			}
		}
	}

	public function to_fslash($str = '') {
		return $this->trim($this->replace(['/', '\\'], '/', $str), '/');
	}

	public function fslash() {
		return '/';
	}

	public function document() {
		if($this->get('this.has_open_tag')) {
			if($this->pistirio($this->get('pistirio'))) {

				$this->set('this.document.'.$this->get('this.index'), [
				    'doctype'					=> $this->get('tag.doctype'),
					'tag'						=> $this->get('tag.tag'),
				    'attr'						=> $this->get('tag.attr'),
				    'text'						=> $this->get('tag.text'),
				    'append_before_tag' 		=> $this->get('tag.append_before_tag'),
				    'append_after_tag'			=> $this->get('tag.append_after_tag'),
				    'append_before_text'		=> $this->get('tag.append_before_text'),
				    'append_after_text'			=> $this->get('tag.append_after_text'),
					'nested'					=> $this->get('tag.nested'),
					'contents'					=> $this->get('tag.contents'),
					'open_tag'					=> $this->get('tag.open_tag'),
					'close_tag'					=> $this->get('tag.close_tag'),
					'tag_after'					=> $this->get('tag.tag_after'),
					'tag_before'				=> $this->get('tag.tag_before'),

				    'index'						=> $this->get('this.index'),
				    'level'						=> $this->get('this.level'),
				    'prev_level'				=> $this->get('this.prev_level'),
				    'next_level'				=> $this->get('this.next_level'),
				    'prev_index'				=> $this->get('this.prev_index'),
				    'next_index'				=> $this->get('this.next_index'),
					'start_code_space_level'	=> $this->get('this.start_code_space_level'),
				]);

				$this->new_tag();
			} else {
				$this->fail('Security error: Pistirio has fail to process your request.');
			}
		}
	}

	public function as_html() {
		if($this->get('tag.tag') !== 'untaged') {
			if($this->get('tag.tag', 'form')) {
				$this->set('this.form', [
					'name' => $this->get('tag.attr.name'),
					'index' => $this->get('this.index'),
					'level' => $this->get('this.level')
				]);
			}

			if($this->isset('tag.attr.name')) {
				$this->set('keep.attr.name', $this->replace('[]', '', $this->get('tag.attr.name')));
				$this->set('keep.attr.level', $this->get('this.level'));
				$this->set('keep.attr.type', $this->isset('tag.attr.type') ? $this->get('tag.attr.type') : false);
				$this->set('keep.attr.defineds', $this->get('tag.defineds'));
			}

			if($this->isset('keep.attr.name')) {

				$posted = $this->isset('keep.attr.defineds.posted');
				$type = $this->lower($this->get('keep.attr.type'));

				$default = [];

				if($this->isset('old') && $this->get('keep.attr.name') && $this->isset('old.'.$this->get('keep.attr.name'))) {
					$default[$this->get('keep.attr.name')] = $this->get('old.'.$this->get('keep.attr.name'));
				} else {
					if($this->key_exists('default_checked', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_checked');
					}
					elseif($this->key_exists('default_selected', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_selected');
					}
					elseif($this->key_exists('default_value', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_value');
					}
					elseif($this->key_exists('default_text', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_text');
					}
				}

				if(!$posted && !$this->get('keep.attr.name')) {
					if($this->key_exists($this->get('keep.attr.name'), $default)) {
						unset($default[$this->get('keep.attr.name')]);
					}
				}

				if($this->key_exists($this->get('keep.attr.name'), $default)) {
					if($this->key_exists('value', $this->get('tag.attr'))) {
						if($this->is_array($default[$this->get('keep.attr.name')])) {
							if($this->inArray($this->get('tag.attr.value'), $default[$this->get('keep.attr.name')])) {
								if($this->get('tag.tag') == 'input') {
									if($type == 'checkbox' || $type == 'radio') {
										$this->checked();
									}
								}
								if($this->get('tag.tag') == 'option') {
									$this->selected();
								}
							}
						} else {
							if($this->get('tag.tag') == 'input') {
								if($type == 'checkbox' || $type == 'radio') {
									if($this->get('tag.attr.value') == $default[$this->get('keep.attr.name')]) {
										$this->checked();
									}
								} else {
									$this->fds = 1;
									$this->set('tag.attr.value', $default[$this->get('keep.attr.name')]);
								}
							}
							if($this->get('tag.tag') == 'option') {
								if($this->get('tag.attr.value') == $default[$this->get('keep.attr.name')]) {
									$this->selected();
								}
							}
						}
					} else {
						if($this->get('tag.tag') == 'input') {
							if($type == 'checkbox' || $type == 'radio') {
								if($this->acceptable($default[$this->get('keep.attr.name')])) {
									$this->checked();
								}
							}
							elseif($type == 'text') {
								$this->set('tag.attr.value', $default[$this->get('keep.attr.name')]);
							} else {
								
							}
						} else {
							if($this->get('tag.tag') == 'option') {
								if($this->acceptable($default[$this->get('keep.attr.name')])) {
									$this->selected();
								}
							} else {
								if($this->get('tag.tag') == 'textarea') {
									$this->set('tag.text', $default[$this->get('keep.attr.name')]);
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	public function as_php() {
		return 1;
	}


	public function remove_spaces(string $str) {
		return preg_replace('/\s+/', '', $str);
	}

	public function pistirio($as) {
		$res = $this->{'as_'.$as}();
		return $res;

	}

	public function is_object($element) {
		return is_object($element);
	}

	public function doctype($type) {
		$this->set('doctype', $type);
	}

	public function finalize() {

		if($this->get('this.unclosed_tags') > 0) {
			echo('You have more opened tags than you have closed.');
			die();
		}

		if($this->get('this.unclosed_tags') < 0) {
			echo('You have more closed tags than you have opened.');
			die();
		}

		$nested = $this->nested($this->get('this.document'));
		$document = $this->build_document($nested);

		if($document) {
			$this->cache($this->get('view'), $document);
		}

		$this->reset();
		return $document;
	}

	public function nested(array $data = []) {

	    $level = 0;
	    $prev = 0;

	    foreach($data as $key => $item) {

			if($level && $item['level'] > $level) {
				$data[$prev]['items'][$item['index']] = $data[$item['index']];
				unset($data[$item['index']]);
			} else {
				$prev = $item['index'];
				$level = $item['level'];
			}

	        if(isset($data[$prev]['items'])) {
	            $data[$prev]['items'] = $this->nested($data[$prev]['items']);
	        }

	    }

	    return $data;
	}

	public function build_document($data, $level = 0) {

		$contents = '';

		foreach($data as $key => $item) {

			if(isset($item['items'])) {
				$item['nested'] = $this->build_document($item['items'], $item['level']);
			}

			$item = $this->item_defaults($item);

			$item['space'] = $this->space_like_tab($level + $item['start_code_space_level']);
			$item['attr_string'] = "";
			$item['lower_tag'] = $this->lower($item['tag']);
			$this->set('doctype', $item['doctype']);

			if(!$this->is_empty($item['attr'])) {
				foreach($item['attr'] as $attr_name => $attr_value) {

					if($this->is_array($attr_value) || $this->is_object($attr_value)) {
						$attr_value = '';
					}
					if($this->is_null($attr_value) || $this->trim($attr_value) == '') {
						$attr_value = '';
					}

					if($attr_value) {
						if($attr_name == 'style') {
							if(!$this->last_in($this->trim($attr_value), ';')) {
								$attr_value.=';';
							}
							if($this->key_exists('class', $item['attr'])) {
								$this->style_to_file($attr_value, $item['attr']['class']);
							}
						}
					}

					if($item['attr_string']) {
						$item['attr_string'].=' ';
					}

					if($this->isNumeric($attr_name)) {
						$item['attr_string'].=$attr_value;
					} else {
						if($this->lower($item['tag']) == 'doctype') {
							if($this->trim($attr_value) == '') {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'=""';
								}
							} else {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'="'.(string)$attr_value.'"';
								}
							}
						} else {
							$item['attr_string'].=$attr_name.'="'.(string)$attr_value.'"';
						}
					}
				}
			}

			$item['attr_string'] = $this->trim($item['attr_string']);
			$item['attr_string'] = $item['attr_string'] ? ' '.$item['attr_string'] : '';

			switch($item['lower_tag'])
			{
				case'untaged':

				break;
				case'doctype':
					$item['tag_before'] = $item['open_tag'].'!'.$this->upper($item['tag']).$item['attr_string'].$item['close_tag'];
				break;
				case'title':
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
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
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].' /'.$item['close_tag'];
					if($item['nested']) {
						$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
						$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
					}
				break;
				default:
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
				break;
			}

			if($item['tag_before'] && $item['lower_tag'] != 'untaged') {
				$contents.=$this->new_line();
				$item['contents'].=$this->new_line();
			}

			if($item['nested'] && $item['tag_after'] && $item['lower_tag'] != 'untaged') {
				$item['tag_after'] = $this->new_line().$item['space'].$item['tag_after'];
			}

			$contents.=$item['space'];
			$contents.=$this->implode('', $item['append_before_tag']);
			$contents.=$item['tag_before'];
			$contents.=$this->implode('', $item['append_before_text']);
			$contents.=$item['text'];
			$contents.=$this->implode('', $item['append_after_text']);
			$contents.=$item['nested'];
			$contents.=$item['tag_after'];
			$contents.=$this->implode('', $item['append_after_tag']);

		}

		return $contents;
	}

	public function item_defaults($item) {
		return [
		    'doctype'					=> $this->key_exists('doctype', $item) ? $item['doctype'] : 'html',
			'tag'						=> $this->key_exists('tag', $item) ? $item['tag'] : '',
			'lower_tag'					=> $this->key_exists('tag', $item) ? $this->lower($item['tag']) : '',
		    'attr'						=> $this->key_exists('attr', $item) ? $item['attr'] : [],
		    'text'						=> $this->key_exists('text', $item) ? $item['text'] : '',
		    'append_before_tag' 		=> $this->key_exists('append_before_tag', $item) ? $item['append_before_tag'] : [],
		    'append_after_tag'			=> $this->key_exists('append_after_tag', $item) ? $item['append_after_tag'] : [],
		    'append_before_text'		=> $this->key_exists('append_before_text', $item) ? $item['append_before_text'] : [],
		    'append_after_text'			=> $this->key_exists('append_after_text', $item) ? $item['append_after_text'] : [],
			'nested'					=> $this->key_exists('nested', $item) ? $item['nested'] : '',
			'contents'					=> $this->key_exists('contents', $item) ? $item['contents'] : '',
			'open_tag'					=> $this->key_exists('open_tag', $item) ? $item['open_tag'] : '',
			'close_tag'					=> $this->key_exists('close_tag', $item) ? $item['close_tag'] : '',
			'tag_after'					=> $this->key_exists('tag_after', $item) ? $item['tag_after'] : '',
			'tag_before'				=> $this->key_exists('tag_before', $item) ? $item['tag_before'] : '',
		    'index'						=> $this->key_exists('index', $item) ? $item['index'] : 0,
		    'level'						=> $this->key_exists('level', $item) ? $item['level'] : 0,
		    'prev_level'				=> $this->key_exists('prev_level', $item) ? $item['prev_level'] : 0,
		    'next_level'				=> $this->key_exists('next_level', $item) ? $item['next_level'] : 0,
		    'prev_index'				=> $this->key_exists('prev_index', $item) ? $item['prev_index'] : 0,
		    'next_index'				=> $this->key_exists('next_index', $item) ? $item['next_index'] : 0,
			'start_code_space_level'	=> $this->key_exists('start_code_space_level', $item) ? $item['start_code_space_level'] : 0,
		];
	}

	public function fail($error) {
		$this->echo($error);
		$this->exit();
	}

	public function syntax_error($error, $line = 0) {
		$this->fail('SYNTAX ERROR: '.$error.' on line '.$line);
	}

	public function redirect($url) {
		$this->header("Location: ".$this->url($url));
	}

	public function send() {

		foreach($this->get('this.on') as $callback) {
			$callback();
		}

		if($this->is_string($this->get('text'))) {
			$this->echo($this->get('text'));
		} else {
			$this->echo($this->get('Only string content you can send'));
		}

	}

	public function function_exists($name) {
		return $this->isset('functions.'.$name);
	}

	public function setGlobalVar($method, $parameters) {
		$this->global->{$method} = $parameters;
		return $this;
	}

	public function tag($tag = null) {

		$this->document();

		if(!$tag) {
			$this->set('this.unclosed_tags', $this->get('this.unclosed_tags') - 1);
			if($this->get('keep.attr.name') && $this->get('keep.attr.level', $this->get('this.level'))) {
				$this->set('keep', [
					'attr' => [
						'name' => '',
						'level' => '',
						'type' => '',
						'defineds' => []
					]
				]);
			}
			if($this->isset('this.form.index') && $this->get('this.form.level', $this->get('this.level'))) {
				$this->set('this.form', []);
			}
			if($this->get('this.level') > 0) {
				$this->set('this.level', $this->get('this.level') - 1);
			}
		} else {
			$this->set('this.unclosed_tags', $this->get('this.unclosed_tags') + 1);
			if($this->get('this.level') >= 0) {
				$this->set('this.level', $this->get('this.level') + 1);
			}
			$this->set('this.has_open_tag', 1);
			$this->set('tag.tag', $this->remove_spaces($tag));
			$this->set('tag.doctype', $this->get('doctype'));
		}
		return $this;
	}

    public function style_to_file($style, $class)
    {
    	$data = $style;

    	$contents = '';
		if($this->file_not_exists($this->assets_path('views/'.$this->get('view').'/hand/hand.css'))) {
			$this->make_file_and_folder_force($this->assets_path('views/'.$this->get('view').'/hand/hand.css'));
			$this->style_to_file($style, $class);
		} else {

			$style = $this->trim($data);
			$style = $this->replace_spaces_with_one($this->replace($this->new_line(), ' ', $style));
			$style = $this->trim($style);

			if(!$this->last_in($style, '}') && !$this->last_in($style, ';')) {
				$style.=';';
			}
			if(!$this->contains_in($style, '{') && !$this->contains_in($style, '}')) {
				$style = $this->explode(';', $style);
				foreach($style as $key => $value) {
					if($contents) {
						$contents.=$this->new_line();
					}
					$contents.=$this->space(1).$value;
				}
				$contents = $this->new_line().$this->space_to_dash($this->slash_and_dot_to_space($this->get('view')))." .".$this->implode(". ", $this->explode(" ", $this->replace_spaces_with_one($this->trim($class))))." {".$this->new_line().$this->space(1).$this->trim($contents).$this->new_line()."}".$this->new_line();

				if(!$this->contains_in($this->file_contents($this->assets_path('views/'.$this->get('view').'/hand/hand.css')), $contents)) {
					$this->file_append($this->assets_path('views/'.$this->get('view').'/hand/hand.css'), $contents);
				}
			}
			return $contents;
		}
    }

    public function get_body_class()
    {
        return $this->implode(' ', $this->get('this.bodyclass'));
    }

    public function implode($separator, $array)
    {
        return implode($separator, $array);
    }

	public function assets_img($img) {
		return '';
	}

    public function default_value($data = '') {
    	$this->set('tag.defineds.default_value', $data);
        return $this;
    }

    public function default_checked($data = '') {
    	$this->set('tag.defineds.default_checked', $data);
        return $this;
    }

    public function default_selected($data = '') {
    	$this->set('tag.defineds.default_selected', $data);
        return $this;
    }

    public function default_text($data = '') {
    	$this->set('tag.defineds.default_text', $data);
        return $this;
    }

    public function posted() {
    	$this->set('tag.defineds.posted', 1);
        return $this;
    }

	public function addClass($data = null) {
		if(!$data) {
			return $this;
		}
		$this->set('tag.attr.class', $this->get('tag.attr.class') ? $this->set('tag.attr.class', $this->get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	public function checked() {
		$this->set('tag.attr.checked', 'checked');
		return $this;
	}

	public function class($data = '') {
		$this->set('tag.attr.class', $data);
		return $this;
	}

	public function text($text = '') {
		if($this->is_object($text)) {
			return $this;
		}
		if($text !== '') {
			if($this->get('tag.tag')) {
				$this->set('tag.text', $this->get('tag.text').$text);
			} else {
				$this->tag('untaged');
					$this->set('tag.text', $text);
				$this->tag();
			}
		}
		return $this;
	}

	public function escapeString($str) {
        $js_escape = array(
            "\r" => '\r',
            "\n" => '\n',
            "\t" => '\t',
            "'" => "\\'",
            '"' => '\"',
            '\\' => '\\\\'
        );
        return $this->str_trans($str, $js_escape);
    }

	public function attr($attr, $data = null)
	{
		$this->set('tag.attr.'.$attr, $data);
		return $this;
	}

	public function append_before_tag($data = null)
	{
		$this->push('tag.append_before_tag', $data);
		return $this;
	}

	public function append_after_tag($data = null)
	{
		$this->push('tag.append_after_tag', $data);
		return $this;
	}

	public function append_before_text($data = null)
	{
		$this->push('tag.append_before_text', $data);
		return $this;
	}

	public function append_after_text($data = null)
	{
		$this->push('tag.append_after_text', $data);
		return $this;
	}

	public function bodyClass($classes = '') {
		$classes = $this->replace_spaces_with_one($classes);
		$classes = $this->explode(' ', $classes);
		foreach($classes as $class) {
			if(!$this->inArray($class, $this->get('this.bodyclass'))) {
				$this->push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	public function make_include($file) {
		return $this->make_file($this->project($file.'.php'));
	}

	public function on($event, $callback) {
		return $this->push('this.on', [
			'event_name' => $event,
			'callback' => $callback
		]);
	}

	public function require($file, $position = 'require') {
		if($this->file_exists($this->assets_path($file))) {
			$url = $this->assets_url($file);
			$ext = $this->lower($this->file_extention($url));
			if(!$this->get('settings.'.$ext.'_'.$position)) {
				if(!$this->inArray($url, $this->get('this.resources.after.'.$ext.'_'.$position)) && !$this->inArray($url, $this->get('this.resources.before.'.$ext.'_'.$position))) {
					$this->push('this.resources.'.$this->get('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		}
	}

	public function space($number) {
		return $this->get_spaces_by_level($number, " ");
	}

	public function space_like_tab($number) {
		return $this->get_spaces_by_level($number, "	");
	}

	public function tab_space($number) {
		return $this->get_spaces_by_level($number, "\t");
	}

	public function get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	public function resources() {

		if($this->get('view')) {
			$css = 'views/'.$this->get('view').'.css';
			$js = 'views/'.$this->get('view').'.js';

			if(!$this->file_exists($this->assets_path($css))) {
				if($this->make_file_and_folder_force($this->assets_path($css))) {
					$this->require($css, 'auto_view');
				}
			} else {
				$this->require($css, 'auto_view');
			}
			if(!$this->file_exists($this->assets_path($js))) {
				if($this->make_file_and_folder_force($this->assets_path($js))) {
					$this->require($js, 'auto_view');
				}
			} else {
				$this->require($js, 'auto_view');
			}
		}

	}

	public function loadComponents(array $types) {
		foreach($types as $type) {
			foreach([$type.'_require', $type.'_dynamic', $type.'_auto_view'] as $setch) {
				if(!$this->get('settings.'.$setch)) {
					foreach(['before', 'after'] as $place) {
						foreach($this->get('this.resources.'.$place.'.'.$setch) as $comp) {
							if($type == 'css') {
								$this->tag('link')->attr('data-preload', true)->attr('href', $comp)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
							}
							elseif($type == 'js') {
								$this->tag('script')->attr('data-preload', true)->attr('src', $comp)->attr('type', 'text/javascript')->tag();
							}
						}
					}
				}
			}
		}
		return $this;
	}

	public function loadTheScriptsComponents() {
		if(!$this->get('settings.scripts')) {
			foreach($this->get('this.resources.after.scripts') as $ajavascript) {
				$this->tag('script')->data('preload', true)->text($ajavascript)->tag();
			}
			foreach($this->get('this.resources.before.scripts') as $bjavascript) {
				$this->tag('script')->data('preload', true)->text($bjavascript)->tag();
			}
		}
		return $this;
	}

    public function make_file_and_folder_force($file, $contents = '') {
    	if($this->file_not_exists($file)) {
    		if(!$this->isDir($this->getDirFile($file))) {
    			if($this->make_dir($this->getDirFile($file))) {
    				$this->make_file($file, $contents);
    			}
    		} else {
    			$this->make_file($file, $contents);
    		}
    	} else {
    		if($this->delete_file($file)) {
    			$this->make_file($file, $contents);
    		}
    	}
    	return 1;
	}

    public function make_dir_force($file, $mode = 0755, $recursive = true) {
        return @mkdir($file, $mode, $recursive);
	}

    public function make_file($file, $contents = '', $lock = false) {
    	if($this->file_not_exists($file)) {
    		if($this->write_file($file, $contents, $lock)) {
    			$this->set('make_file.success', 1);
    		} else {
    			$this->set('make_file.success', 0);
    		}
    	} else {
    		$this->set('make_file.already_exists', 1);
    		$this->set('make_file.file_name', $file);
    	}
	}

	public function storage($str = '') {
		return $this->disk('storage/'.$str);
	}

	public function storage_copy($source, $destination) {
		return $this->copy_dir(
			$this->storage($source), $destination
		);
	}

    public function project($str = '') {
        return $this->disk('views/default/'.$str);
	}

    public function public_path($str = '') {
        return $this->disk('public/'.$str);
	}

    public function assets_path($str = '') {
        return $this->disk('public/assets/'.$str);
	}

    public function views_path($str = '') {
        return $this->disk('views/'.$str);
	}

    public function http_is_secure() {
        return $this->http_scheme() == 'https';
	}

    public function http_scheme() {
        return $this->get('request.scheme');
	}

    public function http_host() {
        return $this->get('http.host');
	}

    public function url($extend = '', $parameters = []) {

    	if($this->http_is_secure()) {
	        $url = 'https://';
    	} else {
	        $url = 'http://';
    	}

		$url.=$this->http_host().($extend ? $this->fslash().$this->to_fslash($extend) : '');

    	if(!$this->is_empty($parameters)) {
    		$url.='?'.$this->build_query($parameters);
    	}

		return $url;
	}

    public function build_query($params) {
    	$query = '';
		foreach($params as $key => $param) {
			if($query) {
				$query.='&';
			}
			$query.=$key.'='.$param;
		}
		return $query;
	}

    public function assets_url($url = '') {
        return $this->url('assets/'.$this->to_fslash($url));
	}

    public function assets_images_url($url = '') {
        return $this->url('assets/img/'.$this->to_fslash($url));
	}

    public function delete_path($directory, $preserve = false) {
        if(!$this->isDir($directory)) {
            return false;
        }
        $items = new FilesystemIterator($directory);
        foreach ($items as $item) {
            if ($item->is_dir() && ! $item->isLink()) {
                $this->delete_path($item->getPathname());
            }
            else {
                $this->delete_file($item->getPathname());
            }
        }
        if (! $preserve) {
            @rmdir($directory);
        }
        return true;
    }

    public function copy_dir($directory, $destination, $options = null) {
        if(!$this->isDir($directory)) {
            return false;
        }
        $options = $options ?: FilesystemIterator::SKIP_DOTS;
        if (!$this->isDir($destination)) {
            $this->make_dir($destination, 0777, true);
        }
        $items = new FilesystemIterator($directory, $options);
        foreach ($items as $item) {
            $target = $destination.'/'.$item->getBasename();
            if ($item->isDir()) {
                $path = $item->getPathname();
                if(!$this->copy_dir($path, $target, $options)) {
                    return false;
                }
            }
            else {
                if (!$this->copy_file($item->getPathname(), $target)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function copy_file($path, $target) {
        return copy($path, $target);
    }

	public function cache($file, $contents = null) {

    	$file = $this->project('_common/cache/'.$this->to_bslash($file.'.'.$this->get('doctype')));

    	if(!$this->is_null($contents)) {
    		if(!$this->isDir($dir = $this->getDirFile($file))) {
    			$this->make_dir($dir);
    		}
    		if($this->file_exists($file)) {
    			$this->delete_file($file);
    		}
			return $this->make_file($file, $contents);
		}

		if($this->file_exists($file)) {
			return $this->file_contents($file);
		}

		return '';
	}

	public function is_cached($file) {
		return $this->file_exists($this->project('_common/cache/'.$this->to_bslash($file.'.'.$this->get('doctype'))));
	}

    public function delete_file($files) {
        $success = true;
        foreach($this->is_array($files) ? $files : [$files] as $file) {
            if(!@unlink($file)) {
				$success = false;
			}
        }
        return $success;
	}

	public function slash_and_dot_to_bslash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], $this->bslash(), $str), $this->bslash());
	}

	public function slash_and_dot_to_fslash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], $this->fslash(), $str), $this->fslash());
	}

	public function slash_and_dot_to_space($str) {
		return $this->trim($this->replace(['/', '\\', '.'], ' ', $str), $this->fslash());
	}

	public function slash_and_dot_to_dash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], '_', $str), $this->fslash());
	}

	public function space_to_dash($str) {
		return $this->replace(' ', '-', $str);
	}

	public function dot_to_bslash($str) {
		return $this->trim($this->replace('.', $this->bslash(), $str), $this->bslash());
	}

	public function dot_to_fslash($str) {
		return $this->trim($this->replace('.', $this->fslash(), $str), $this->fslash());
	}

	public function slash_to_dot($str) {
		return $this->trim($this->replace(['/', '\\'], '.', $str), '.');
	}

	public function class_separator_fix(string $class) {
		 return $this->trim($this->replace(['/', '.'], '\\', $class), '\\');
	}

	public function bslash() {
		return DIRECTORY_SEPARATOR;
	}

	public function new_line() {
		return "\n";
	}

	public function all() {
		return $this->memory;
	}

	public function console_log(string $log) {
		$this->echo($log);
	}

	public function set($key, $value = null) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'set'
		]);
	}

    public function isset($key) {
		return $this->find([
			'key' => $key,
			'method' => 'isset'
		]);
	}

    public function undefine($key) {
		return $this->find([
			'key' => $key,
			'method' => 'undefine'
		]);
	}

	public function push($key, $value) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'push'
		]);
	}

	public function get($key, $default = null) {
		return $this->find([
			'key' => $key,
			'default' => $default,
			'method' => 'get'
		]);

	}

	public function normalize($options) {

		if($options['method'] == 'set') {
			if(!$this->key_exists($options['table'], $this->memory)) {
				$this->memory[$options['table']] = [];
			}
			if($options['count'] > 0) {
				$options['array_captions'][$options['count']] = $options['value'];
				$data = $this->array_single_to_multidimentional($options['array_captions'], $options['count'] + 1);
				$this->memory[$options['table']] = $this->merge($this->memory[$options['table']], $data);
			} else {
				$this->memory[$options['table']] = $options['value'];
			}
			$options['results'] = $options['value'];
			return $options['results'];
		}
		elseif($options['method'] == 'isset') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		elseif($options['method'] == 'get') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		elseif($options['method'] == 'push') {
			if($options['count'] > 0) {
				$data = $this->find($options, $this->memory[$options['table']]);
				$data[] = $options['value'];
				$options['value'] = $data;
				$options['array_captions'][$options['count']] = $options['value'];
				$data = $this->array_single_to_multidimentional($options['array_captions'], $options['count'] + 1);
				$this->memory[$options['table']] = $this->merge($this->memory[$options['table']], $data);
			} else {
				$this->memory[$options['table']][] = $options['value'];
			}
			$options['results'] = $options['value'];
			return $options['results'];
		}
		elseif($options['method'] == 'undefine') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		return $options;
	}

    public function find($options, $something = []) {

    	if($this->key_exists('i', $options)) {

	    	if($options['i'] == $options['count']) {
	    		if($options['method'] == 'isset') {
					return 1;
				}
	    		elseif($options['method'] == 'get') {
	    			if($this->is_null($options['default'])) {
	    				return $something;
	    			} else {
						return $this->lower($options['default']) == $this->lower($something);
					}
				}
	    		elseif($options['method'] == 'set') {
					return $something;
				}
	    		elseif($options['method'] == 'push') {
					return $something;
				}
	    		elseif($options['method'] == 'unset') {
					return $something;
				}
	    		elseif($options['method'] == 'update') {
					return $something;
				} else {

				}
	    	} else {
				if($this->key_exists($options['array_captions'][$options['i']], $something)) {
					$options['i'] = $options['i'] + 1;
					return $this->find($options, $something[$options['array_captions'][$options['i'] - 1]]);
				} else {
		    		if($options['method'] == 'isset') {
						return 0;
					}
		    		elseif($options['method'] == 'get') {
						return 0;
					}
		    		elseif($options['method'] == 'set') {
						return 0;
					}
		    		elseif($options['method'] == 'push') {
						return 0;
					}
		    		elseif($options['method'] == 'unset') {
						return 0;
					}
		    		elseif($options['method'] == 'update') {
						return 0;
					} else {

					}
				}
			}
			return '';
		} else {

			$options = $this->merge([
				'key' => null,
				'value' => null,
				'method' => null
			], $options);

			if($this->first_in($options['key'], '.') || $this->last_in($options['key'], '.') || $this->contains_in($options['key'], '..')) {
		    	$this->fail('FATAL ERROR: WRONG COLLECTION KEY SKELETON');
	    	}

	    	$options['caption_key'] = $this->lower($options['key']);
	    	$options['array_captions'] = $this->explode('.', $options['caption_key']);
			$options['table'] = $options['array_captions'][0];
			unset($options['array_captions'][0]);
			$options['array_captions'] = $this->array_fix($options['array_captions']);
			$options['count'] = $this->count($options['array_captions']);
			$options['results'] = '';
			$options['i'] = 0;
	    	return $this->normalize($options);

		}
	}

	public function inArray($key, $element) {
		return in_array($key, $element);
	}

	public function acceptable($value) {
		return $this->inArray($value, ['yes', 'on', '1', 1, true, 'true'], true) ? 1 : 0;
	}

	public function getNonAlphaNumericCharacters() {
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

	public function mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->inArray($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

    public function underscoreToUpercase($name) {
    	$names = $this->trim($name, '_');
    	$names = $this->explode('_', $name);
    	$newName = '';
    	foreach($names as $name) {
    		$newName.=$this->upper_first($name);
    	}
    	return $newName;
    }

    public function upperToUnderscore($string) {
	    return $this->lower(
	    	$this->preg_replace('/(.)([A-Z)/', '$1_$2', $string)
	    );
	}

    public function preg_replace($regex, $expresion, $string) {
	    return preg_replace($regex, $expresion, $string);
	}

	public function is_int($element) {
		return $this->isInteger($element);
	}

	public function header($str) {
		return header($str);
	}

	public function isInteger($element) {
		return is_integer($element);
	}

	public function get_type($element) {
		return gettype($element);
	}

	public function is_string($element) {
		return is_string($element);
	}

	public function isNotNull($element) {
		return !$this->is_null($element);
	}

	public function isDouble($element) {
		return is_double($element);
	}

	public function isFloat($element) {
		return is_float($element);
	}

	public function isNumeric($element) {
		return is_numeric($element);
	}

	public function is_empty($something) {
		return empty($something);
	}

	public function array_fix($array) {
		return array_values($array);
	}

	public function stringlength($str) {
		return strlen($str);
	}

	public function sub($string, $start = 0, $length = null) {
		if($this->is_null($length)) {
			return mb_substr($string, $start);
		} else {
			return mb_substr($string, $start, $length);
		}
	}

	public function file_extention($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

    public function count($str) {
		return count($str);
	}

	public function replace_spaces_with_one($str = '') {
		return preg_replace('/\s+/', '', $str);
	}

	public function decode($data) {
		return json_decode($data);
	}

	public function encode($data) {
		return json_encode($data);
	}

    public function isDir($str) {
		return is_dir($str);
	}

    public function getDirFile($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

    public function file_contents($file, $lock = false) {
		return file_get_contents($file);
	}

    public function file_append($file, $contents) {
		return file_put_contents($file, $contents, FILE_APPEND);
	}

    public function write_file($file, $contents, $lock = false) {
		return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
	}

    public function trimleft($source, $sym) {
    	if($this->is_null($sym)) {
    		return ltrim($source);
    	} else {
			return ltrim($source, $sym);
    	}
	}

	public function str_trans($one, $two) {
		return strtr($one, $two);
	}

	public function pos($haystack, $needle) {
		return mb_strpos($haystack, $needle);
	}
	public function length($str) {
		return mb_strlen($str);
	}


	public function file_not_exists($file) {
		return $this->file_exists($file) == 0;
	}

	public function echo($string) {
		echo($string);
	}

	public function upper($str) {
		return strtoupper($str);
	}

	public function upper_first($str) {
		return ucfirst($str);
	}

	public function exit() {
		exit;
	}

	public function call($method, $arguments) {
		if(method_exists($this, $method)) {
			return call_user_func_array([$this, $method], $arguments);
		} else {
			$this->fail('Method "'.$method.'" not exists.');
		}
	}

	public function getParsedApplication() {
		$project = $this->file_contents($this->disk('vendor\brosta\interframework\src\Interframework\signals.php'));
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$project = $parser->parse($project);
		$project = json_decode(json_encode($project), true);
		return $this->is_empty($project) ? false : $project;
	}

	public function updated_application() {
		if($this->file_exists($this->storage('updates/application.php'))) {
			return require($this->storage('updates/application.php'));
		}
		return false;
	}

	public function save_application($contents) {
		return $this->make_file_and_folder_force($this->storage('application.php'), $contents);
	}

	public function array_to_string($frontend = []) {

		$backend = [
			's' => "",
			'ss' => "",
			'var' => "",
			'with_numeric_keys' => 1,
			'count' => array_key_exists('value', $frontend) ? count($frontend['value']) : 0,
			'index' => array_key_exists('index', $frontend) ? $frontend['index'] : 0,
			'level' => array_key_exists('level', $frontend) ? $frontend['level'] : 0,
			'space' => array_key_exists('space', $frontend) ? $frontend['space'] : 0,
			'value' => array_key_exists('value', $frontend) ? $frontend['value'] : 0,
			'caption' => array_key_exists('caption', $frontend) ? $frontend['caption'] : '',
			'keytype' => "",
			'newline' => "",
			'spacetab' => "\t",
			'newspace' => "",
			'valuetype' => 'array',
			'index_prev' => 0,
			'rowseparator' => ",",
			'opentagsymbol' => "[",
			'closetagsymbol' => "]",
			'key_separator_value' => " => "
		];

		$export = $backend;

		foreach($backend['value'] as $key => $value) {
			$export['value'] = $value;
			$export['newline'] = "\n";
			$export['keytype'] = gettype($key);
			$export['valuetype'] = gettype($value);
			if($export['keytype'] == 'integer') {
				if($export['valuetype'] != 'array') {
					$export['key'] = $key;
				} else {
					if($export['with_numeric_keys']) {
						$export['key'] = $key;
					} else {
						$export['key'] = '';
						$export['key_separator_value'] = '';
					}
				}
			} else {
				$export['key'] = "'".$key."'";
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
					$export['rowseparator']=$backend['rowseparator'].$backend['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$backend['newline'].$export['newline'];
				}
				$export['nested'] = $this->array_to_string([
			    	'space' => $export['space'],
			    	'caption' => $export['caption'],
			    	'value' => $export['value'],
			    	'level' => $export['level'],
			    	'count' => count($export['value']),
			    	'index' => $export['index'],
			    	'index_prev' => 0,
			    ]);
				if(!$export['nested']) {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['key'];
					$export['var'].=$export['key_separator_value'];
					$export['var'].=$export['opentagsymbol'];
					$export['var'].=$export['closetagsymbol'];
					$export['var'].=$export['rowseparator'];
				} else {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['key'];
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
		            case 'double':
		            	$export['value'] = $export['value'];
		            break;
		            case 'string':
		            	$export['value'] = "'".$this->str_trans($export['value'], array(
				            "\r" => '\r',
				            "\n" => '\n',
				            "\t" => '\t',
				            "'" => "\\'",
				            '"' => '\"',
				            '\\' => '\\\\'
				        ))."'";
		            break;
		            case 'NULL':
		            	$export['value'] = 'null';
		            break;
		        }
				if($export['count'] > 1 && $export['index_prev'] != $export['count'] - 1) {
					$export['rowseparator']=$backend['rowseparator'].$backend['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$backend['newline'].$export['newline'];
				}
				$export['var'].=$export['newspace'];
				$export['var'].=$export['ss'];
				$export['var'].=$export['key'];
				$export['var'].=$export['key_separator_value'];
				$export['var'].=$export['value'];
				$export['var'].=$export['rowseparator'];
			}
		}
		return $export['var'];
	}

	public function chars_control($code = null, $type = 'symbol') {
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

	public function chars_symbols($code = null, $type = 'symbol') {
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

	public function chars_alpha_upper_from_lower($code = null, $type = 'symbol') {
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

	public function chars_alpha_lower_from_upper($code = null, $type = 'symbol') {
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
	public function chars_alpha_lower($code = null, $type = 'symbol') {
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

	public function chars_alpha_upper($code = null, $type = 'symbol') {
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

	public function chars_numbers($code = null, $type = 'symbol') {
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

	public function process_text_to_ascii($string, &$offset) {
	    $code = ord(substr($string, $offset,1)); 
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

	public function text_to_ascii($text) {
		$results = "";
		$offset = 0;
		while($offset >= 0) {
			if($results) {
				$results.=" ";
			}
		    $results.=$this->process_text_to_ascii($text, $offset);
		}
		return $results;
	}

	private function gg_symbol_string($output) {
		if($output->a == '"' || $output->a == "'") {
			if($output->quote == '"' && $output->a == '"' || $output->quote == "'" && $output->a == "'") {
				if($output->hstring) {
					$this->syntax_error('unexpected string', $output->line);
				} else {
					$output->lstring = "";
					$output->lstring = $output->quote.$output->string.$output->quote;
					$output->lstring_control = $output->lcontrol;
					$output->hstring = 1;
					$output->string = "";
					$output->is_string = 0;
					$output->quote = '';
					$output->lcontrol = '';
				}
			} else {
				$output->string.=$output->a_o;
			}
		} else {
			$output->string.=$output->a_o;
		}
		return $output;
	}

	private function gg_alpha_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function gg_number_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function gg_control_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function ident_exists($output, $key) {
		return isset($output->ident[$key]['default']);
	}

	private function ex_change_ident($output) {

		if($output->tmp_ident !== '') {
			if($output->hident == 0) {
				$output->ident = [];
				$output->hident = 1;
			}

			$output->ident[] = [
				'hstring' => $output->hstring,
				'string_control' => $output->hstring ? $output->lstring_control : '',
				'string' => $output->hstring ? $output->lstring : '',
				'control' => $output->lcontrol,
				'default' => $output->tmp_ident,
				'original' => $output->tmp_ident_o,
			];

			$output->hstring = 0;
			$output->lstring_control = '';
			$output->lcontrol = '';
			$output->tmp_ident = '';
			$output->tmp_ident_o = '';

		}

		return $output;
	}

	private function build_code($output) {
		$name = '';
		if($output->hident) {
			$output->hident = 0;
			foreach($output->ident as $row) {
				$output->contents.=$row['string_control'].$row['control'].$row['original'].$row['string'];
			}
		}
		if($output->hstring) {
			$output->hstring = 0;
			$output->contents.=$output->lstring_control.$output->lstring.$output->lcontrol.$output->a_o;
		} else {
			$output->contents.=$output->lcontrol.$output->a_o;
		}
		$output->lcontrol = '';
		return $output;
	}

	private function gg_symbol($output) {
		if($output->a == '"' || $output->a == "'") {
			$output->is_string = 1;
			$output->quote = $output->a_o;
		} else {
			if($output->a == '_') {
				$output->tmp_ident.=$output->a;
				$output->tmp_ident_o.=$output->a_o;
			} else {
				$output = $this->ex_change_ident($output);
				if($output->may['assign']) {
					$output->may['assign'] = 0;
					if($output->a == '=') {
						$output->may['equal'] = 1;
					} else {
						$this->tag('assign');
						if($output->a == '$') {
							$output->may['var'] = 1;
						}
					}
				}
				elseif($output->may['var']) {
					$output->may['var'] = 0;
					if($output->hident) {
						$output->hident = 0;
						$this->tag('var')->attr('name', $output->ident[0]['original']);
						if($output->a == '=') {
							$output->may['assign'] = 1;
						}
						elseif($output->a == ';') {
							$this->tag()->tag()->tag();
							$output->is['end'] = 1;
						}
					} else {
						$this->fail(3412);
					}
				} else {
					if($output->a == '$') {
						$output->may['var'] = 1;
					}
				}
			}
		}
		return $output;
	}

	private function gg_alpha($output) {
		$output->tmp_ident.=$output->a;
		$output->tmp_ident_o.=$output->a_o;
		return $output;
	}

	private function gg_number($output) {
		$output->tmp_ident.=$output->a;
		$output->tmp_ident_o.=$output->a_o;
		return $output;
	}

	private function gg_control($output) {
		$output = $this->ex_change_ident($output);
		$output->lcontrol.=$output->a_o;
		return $output;
	}

	private function parse($output) {
		if($output->is_string) {
			$output = $this->{'gg_'.$output->type.'_string'}($output);
		} else {
			$output = $this->{'gg_'.$output->type}($output);
		}
		return $output;
	}

	private function character($output) {

		$output->b = $output->a;
		$output->b_o = $output->a_o;

		if($output->decimal >= 0 && $output->decimal <= 127) {

			if($output->decimal >= 0 && $output->decimal <= 32 || $output->decimal == 127) {
				if($output->chars_control == null) {
					$output->chars_control = $this->chars_control();
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
						$output->chars_alpha_upper = $this->chars_alpha_upper();
					}
					$output->a_o = $output->chars_alpha_upper[$output->decimal]['symbol'];
					$output->a = $this->chars_alpha_lower_from_upper($output->decimal);
					$output->is_lower = 0;
					$output->is_upper = 1;
				}
				elseif($output->decimal >= 97 && $output->decimal <= 122) {
					if($output->chars_alpha_lower == null) {
						$output->chars_alpha_lower = $this->chars_alpha_lower();
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
					$output->chars_numbers = $this->chars_numbers();
				}
				$output->a = $output->chars_numbers[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'number';
			}
			elseif($output->decimal >= 33 && $output->decimal <= 47 || $output->decimal >= 58 && $output->decimal <= 64 || $output->decimal >= 91 && $output->decimal <= 96 || $output->decimal >= 123 && $output->decimal <= 126) {
				if($output->chars_symbols == null) {
					$output->chars_symbols = $this->chars_symbols();
				}
				$output->a = $output->chars_symbols[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'symbol';
			}
		}
		return $output;
	}

	private function get_exe_options($input) {
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
		$output->is_string										= 0;
		$output->ident											= [];
		$output->ident_o										= "";
		$output->tmp_ident										= "";
		$output->tmp_ident_o									= "";
		$output->quote											= null;
		$output->contents										= '';
		$output->a												= null;
		$output->a_o											= null;
		$output->decimal										= null;
		$output->max											= count($input);
		$output->input											= $input;
		$output->chars_alpha_lower								= null;
		$output->chars_alpha_upper								= null;
		$output->chars_numbers									= null;
		$output->chars_control									= null;
		$output->chars_symbols									= null;
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

	public function exe($contents) {

		if(!isset($project)) {
			$project = new stdClass;
		}

		if(!isset($project->ram)) {
			$project->ram = [];
		}

		if(!isset($project->ram['this'])) {
			$this->tag('div')->attr('style', 'background-color:#222222;padding:30px;text-align:center;color:#b35807;font-size:22px;font-weight:bold');
				$this->text('Welcome to an fresh installation of Brostά Interframework!');
			$this->tag();
		}


		$contents = $this->text_to_ascii($contents);
		$contents = $this->explode(' ', $this->trim($contents));

		$this->set('pistirio', 'php');
		$results = $this->results($this->get_exe_options($contents));
		$this->set('pistirio', 'html');

		return $results;
	}

	private function results($output) {
		if($output->input) {
			$output->decimal = $output->input[$output->current];
			unset($output->input[$output->current]);
			$output = $this->character($output);
			$output = $this->parse($output);
			$output->current++;
			$output = $this->results($output);
		}
		return $output;
	}

}E
<?php

namespace Brosta;

use FilesystemIterator;
use PhpParser\Error;
use PhpParser\NodeDumper;
use PhpParser\ParserFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Function_;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\PrettyPrinter;

use Closure;
use stdClass;

ini_set('max_execution_time', 2000);
ini_set('memory_limit', '-1');

class Signal {

	private $memory = [];
	private $unique_ids = [];

	public function construct($root, $server = null, $install = '/') {

		$this->reset();

		if($server['uri']) {
			if($this->sub($server['uri'], 0, 1) == $this->fslash()) {
				if($server['uri'] == $this->fslash()) {
					$this->set('request.uri', $server['uri']);
				} else {
					if($this->uri_is_safe($server['uri'])) {
						$this->set('request.uri', $this->sub($server['uri'], 1));
					} else {
						$this->fail('Unsafe uri');
					}
				}
			} else {
				$this->fail('Incorrect uri');
			}
		} else {
			$this->fail('Request uri is empty');
		}

		$this->set('request.docs', $server['docs']);
		$this->set('request.scheme', $server['scheme']);
		$this->set('request.query', $server['query']);
		$this->set('server.gateway', $server['gateway']);
		$this->set('server.server_ip', $server['server_ip']);
		$this->set('server.remote_ip', $server['remote_ip']);
		$this->set('server.server_port', $server['server_port']);
		$this->set('server.server_protocol', $server['server_protocol']);
		$this->set('server.server_software', $server['server_software']);

		$this->set('http.host', $server['host']);
		$this->set('http.agent', $server['agent']);
		$this->set('http.accept', $server['accept']);
		$this->set('http.connection', $server['connection']);
		$this->set('http.accept_encodidng', $server['accept_encodidng']);
		$this->set('http.accept_languange', $server['accept_languange']);

		$this->set('request.get', $server['get']);
		$this->set('request.post', $server['post']);
		$this->set('request.files', $server['files']);
		$this->set('request.cookies', $server['cookies']);

		if($this->undefined('disk.local')) {
			$this->set('disk.local', $root);
			$this->set('install.redirect', $install);
		}

		if($this->get('disk.local')) {

			if($this->include_exists('_common/config/settings')) {
				$this->memory = $this->merge($this->memory, $this->include('_common/config/settings'));
				$this->signal();
			} else {
				if($this->isDir($this->storage('manufacturer/_common'))) {
					if($this->isset('install.redirect') && $this->get('install.redirect')) {
						if($this->copy_dir($this->storage('manufacturer/_common'), $this->project('_common'))) {
							$this->redirect($this->get('install.redirect'));
						}
					}
					$this->Fail('Fattal error: System can not installed. The argument 3 are missing to redirect after instalation from construct');
				} else {
					$this->Fail('Server is down');
				}
			}
		}

	}

	public function signal() {

		$this->request();
		$this->setView($this->to_bslash($this->get('request.library').'/'.$this->get('request.show')));

		$this->setStartCodeSpaceLevel(2);
		$this->setAfterOrBefore('after');
		$this->include($this->get('view'));
		$this->setText($this->finalize());

		$this->setAfterOrBefore('before');
		$this->setStartCodeSpaceLevel(0);
		$this->include('_common/resources');
		$this->setAfterOrBefore('after');
		$this->resources();
		$this->setAfterOrBefore('before');
		$this->monitor();
		$this->setText($this->finalize());
		$this->send();
	}

	public function setView($view) {
		$this->set('view', $view);
	}

	public function setStartCodeSpaceLevel($level) {
		$this->set('this.start_code_space_level', $level);
	}

	public function setAfterOrBefore($switch) {
		$this->set('after_or_before', $switch);
	}

	public function setText($text) {
		$this->set('text', $text);
	}

	public function monitor() {
		if($this->isset('monitor.info') && $this->get('monitor.info')) {
			$this->text($this->get('monitor.info'));
		}
		if($this->isset('monitor.doctype')) {
			if($this->get('monitor.doctype', 'html')) {
				$this->tag('doctype')->attr('html')->tag();
				$this->tag('html');
					if($this->isset('monitor.doctype_lang')) {
						$this->attr('lang', $this->get('monitor.doctype_lang'));
					}
					$this->tag('head');
						$this->tag('meta')->attr('charset', 'utf-8')->tag();
						$this->tag('meta')->attr('name', 'viewport')->attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->tag();
						$this->tag('meta')->attr('httpequiv', 'Content-Type')->attr('content', 'text/html; charset=UTF-8')->tag();
						$this->tag('title')->attr('id', 'pageTitle')->text($this->get('page.title'))->tag();
						$this->tag('meta')->attr('name', 'John Stamoutsos')->attr('content', 'Brosta')->tag();
						$this->tag('meta')->attr('id', 'domain')->attr('content', 'My domain')->tag();
						$this->loadComponents(['css']);
					$this->tag();
					$this->tag('body');

						$this->tag('div')->attr('style', 'position:absolute;top:0;left:0;bottom:0;right:0;width:100%;height:100%;z-index:1;');
							$this->tag('img')->attr('style', 'width:100%;height:100%')->attr('src', 'http://localhost/assets/img/brosta-words-colored-spray.png')->tag();
						$this->tag();

						$this->tag('div')->attr('style', 'position:absolute;top:0;left:0;bottom:0;right:0;width:100%;height:100%;z-index:2;');
							$this->text($this->get('text'));
						$this->tag();

						$this->loadComponents(['js']);
						$this->loadTheScriptsComponents();
					$this->tag();
				$this->tag();
			}
		}
	}

	public function unique_id($str = '', $id = null, $length = 5) {
		$crypt = ['A','a','B','b','C','c','D','d','E','e','F','f','G','g','H','h','I','i','J','j','K','k','L','l','M','m','N','n','O','o','P','p','Q','q','R','r','S','s','T','t','U','u','V','v','W','w','X','x','Y','y','Z','z','1','2','3','4','5','6','7','8','9','0'];
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
				return $this->unique_id($str, $id, $length);
			}
			if(in_array($id, $this->unique_ids)) {
				return $this->unique_id($str, $id, $length);
			}

			$this->unique_ids[] = $id;

			if($str) {
				$id = $str.$id;
			}
			return $id;
		}
	}

    public function uri_is_safe($uri) {
		return true;
    }

    public function login_ok()
    {
		return $this->license_id == $this->licenses['BROSTA'];
    }

    public function lisense_ok($lisense) {
		return $this->value('this.license_id', $lisense);
    }

    public function first_in($haystack, $needle) {
		if($needle !== '' && $this->pos($haystack, 0, $this->length($needle)) === (string) $needle) {
			return true;
		}
        return false;
    }

    public function last_in($haystack, $needle) {
		if($this->sub($haystack, -$this->length($needle)) === (string)$needle) {
			return true;
		}
        return false;
    }

    public function contains_in($haystack, $needle) {
		if($needle !== '' && $this->pos($haystack, $needle) !== false) {
			return true;
		}
        return false;
    }

    public function lower($str) {
		return strtolower($str);
	}

    public function explode($separator, $string) {
    	return explode($separator, $string);
	}

	public function key_exists($name, $array) {
		foreach($array as $key => $value) {
			if($key == $name) {
				return true;
			}
		}
		return false;
	}

	public function isMethodPost() {
		return $this->get('request.method', 'post');
	}

	public function isMethodGet() {
		return $this->get('request.method', 'get');
	}

	public function array_single_to_multidimentional($array, $count = 0, $current = 0) {
	    if($count - 1 === $current) {
	        $array = $array[$current];
	    } else {
	    	$array = [$array[$current] => $this->array_single_to_multidimentional($array, $count, $current + 1)];
	    }
	    return $array;
	}

	public function merge($defaults, $replaces) {

		if(!$this->is_array($defaults)) {
			return $defaults;
		}

		foreach($replaces as $key => $value) {
			if(!isset($defaults[$key]) || (isset($defaults[$key]) && !$this->is_array($defaults[$key]))) {
				$defaults[$key] = [];
			}
			if($this->is_array($value)) {
				$value = $this->merge($defaults[$key], $value);
			}
			$defaults[$key] = $value;
		}

		return $defaults;
	}

	public function is_array($element) {
		return is_array($element) ? 1 : 0;
	}

    public function undefined($key) {
		return $this->isset($key) == 0;
	}

	public function to_bslash($str) {
		return $this->trim($this->replace(['/', '\\'], $this->bslash(), $str), $this->bslash());
	}

	public function replace($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}

	public function trim($str, $character_mask = null) {
		if($this->is_null($character_mask)) {
			return trim($str);
		} else {
			return trim($str, $character_mask);
		}
	}

	public function is_null($element) {
		return is_null($element);
	}

	public function disk($str = '') {
		$path = $this->trimright($this->get('disk.local'), $this->bslash());
		if($str) {
			$path = $path.$this->bslash().$this->to_bslash($str);
		}
		return $path;
	}

    public function trimright($source, $sym = null) {
    	if($this->is_null($sym)) {
    		return rtrim($source);
    	} else {
			return rtrim($source, $sym);
    	}
	}

    public function make_dir($dir, $mode = 0755, $recursive = true) {
    	if(!$this->isDir($dir)) {
        	if(mkdir($dir, $mode, $recursive)) {
				$this->set('make_dir.success', 1);
        	} else {
    			$this->set('make_dir.success', 0);
    		}
        } else {
        	$this->set('make_dir.already_exists', 1);
        }
	}

	public function include_exists($file) {
		return $this->file_exists($this->project($file.'.php'));
	}

	public function file_exists($file) {
		return file_exists($file);
	}

	public function get_include_contents($file) {
		return $this->file_contents($this->project($file.'.php'));
	}

	public function include($file, $brosta = 0) {
		$file = $this->project($file.'.php');
		if(!$this->inArray($file, $this->get('include'))) {
			$this->push('include', $file);
		}
		return require($file);
	}

	public function reset() {

		$this->set(
			'this', [
				'license_id'				=> 'GDFS-HSKA-OLEK-OWTD',
				'on'						=> [],
				'document'					=> [],
				'bodyclass'					=> [],
				'index'						=> 0,
				'prev_index'				=> 0,
				'next_index'				=> 0,
				'level'						=> 0,
				'prev_level'				=> 0,
				'next_level'				=> 0,
				'unclosed_tags'				=> 0,
				'scripts'					=> [],
				'form'						=> [],
				'has_open_tag'				=> 0,
			    'doctype'					=> 'html',
				'tag'						=> '',
				'text'						=> '',
				'attr'						=> [],
				'defineds'					=> [],
				'append_before_tag'			=> [],
				'append_after_tag'			=> [],
				'append_before_text'		=> [],
				'append_after_text'			=> [],
				'nested'					=> '',
				'contents'					=> '',
				'open_tag'					=> '<',
				'close_tag'					=> '>',
				'tag_after'					=> '',
				'tag_before'				=> '',
				'start_code_space_level'	=> 0,
				'resources'	=> [
					'before' => [
						'css_require'		=> [],
						'js_require'		=> [],
						'css_auto_view'		=> [],
						'js_auto_view'		=> [],
						'css_dynamic'		=> [],
						'js_dynamic'		=> [],
						'scripts'			=> [],
						'meta'				=> []
					],
					'after' => [
						'css_require'		=> [],
						'js_require'		=> [],
						'css_auto_view'		=> [],
						'js_auto_view'		=> [],
						'css_dynamic'		=> [],
						'js_dynamic'		=> [],
						'scripts'			=> [],
						'meta'				=> []
					]
				],
				'items'						=> [],
			]
		);
		$this->set('pistirio', 'html');
		$this->set('include', []);
		$this->set('unique_ids', []);
		$this->set('new', $this->get('this'));
		$this->new_tag();
	}

	public function new_tag() {
		$this->set('tag', $this->get('new'));
		$this->set('this.prev_index', $this->get('this.index'));
		$this->set('this.prev_level', $this->get('this.level'));
		$this->set('this.index', $this->get('this.index') + 1);
		$this->set('this.has_open_tag', 0);
	}

	public function request() {

		$uri = $this->get('request.uri');

		$this->set('request.library', 'desktop');
		$this->set('request.show', 'index');
		$this->set('request.dynamic_params', []);

		if($this->get('request.uri') != $this->fslash()) {
			$uri = $this->explode($this->fslash(), $this->get('request.uri'));
			if(isset($uri[0])) {
				$this->set('request.library', $uri[0]);
				unset($uri[0]);
				if(isset($uri[1])) {
					$this->set('request.show', $uri[1]);
					unset($uri[1]);
					if(isset($uri[2])) {
						$this->set('request.uri_params', $this->is_empty($uri) ? false : $this->array_fix($uri));
					}
				}
			}
		}
	}

	public function to_fslash($str = '') {
		return $this->trim($this->replace(['/', '\\'], '/', $str), '/');
	}

	public function fslash() {
		return '/';
	}

	public function document() {
		if($this->get('this.has_open_tag')) {
			if($this->pistirio($this->get('pistirio'))) {

				$this->set('this.document.'.$this->get('this.index'), [
				    'doctype'					=> $this->get('tag.doctype'),
					'tag'						=> $this->get('tag.tag'),
				    'attr'						=> $this->get('tag.attr'),
				    'text'						=> $this->get('tag.text'),
				    'append_before_tag' 		=> $this->get('tag.append_before_tag'),
				    'append_after_tag'			=> $this->get('tag.append_after_tag'),
				    'append_before_text'		=> $this->get('tag.append_before_text'),
				    'append_after_text'			=> $this->get('tag.append_after_text'),
					'nested'					=> $this->get('tag.nested'),
					'contents'					=> $this->get('tag.contents'),
					'open_tag'					=> $this->get('tag.open_tag'),
					'close_tag'					=> $this->get('tag.close_tag'),
					'tag_after'					=> $this->get('tag.tag_after'),
					'tag_before'				=> $this->get('tag.tag_before'),

				    'index'						=> $this->get('this.index'),
				    'level'						=> $this->get('this.level'),
				    'prev_level'				=> $this->get('this.prev_level'),
				    'next_level'				=> $this->get('this.next_level'),
				    'prev_index'				=> $this->get('this.prev_index'),
				    'next_index'				=> $this->get('this.next_index'),
					'start_code_space_level'	=> $this->get('this.start_code_space_level'),
				]);

				$this->new_tag();
			} else {
				$this->fail('Security error: Pistirio has fail to process your request.');
			}
		}
	}

	public function as_html() {
		if($this->get('tag.tag') !== 'untaged') {
			if($this->get('tag.tag', 'form')) {
				$this->set('this.form', [
					'name' => $this->get('tag.attr.name'),
					'index' => $this->get('this.index'),
					'level' => $this->get('this.level')
				]);
			}

			if($this->isset('tag.attr.name')) {
				$this->set('keep.attr.name', $this->replace('[]', '', $this->get('tag.attr.name')));
				$this->set('keep.attr.level', $this->get('this.level'));
				$this->set('keep.attr.type', $this->isset('tag.attr.type') ? $this->get('tag.attr.type') : false);
				$this->set('keep.attr.defineds', $this->get('tag.defineds'));
			}

			if($this->isset('keep.attr.name')) {

				$posted = $this->isset('keep.attr.defineds.posted');
				$type = $this->lower($this->get('keep.attr.type'));

				$default = [];

				if($this->isset('old') && $this->get('keep.attr.name') && $this->isset('old.'.$this->get('keep.attr.name'))) {
					$default[$this->get('keep.attr.name')] = $this->get('old.'.$this->get('keep.attr.name'));
				} else {
					if($this->key_exists('default_checked', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_checked');
					}
					elseif($this->key_exists('default_selected', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_selected');
					}
					elseif($this->key_exists('default_value', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_value');
					}
					elseif($this->key_exists('default_text', $this->get('keep.attr.defineds'))) {
						$default[$this->get('keep.attr.name')] = $this->get('keep.attr.defineds.default_text');
					}
				}

				if(!$posted && !$this->get('keep.attr.name')) {
					if($this->key_exists($this->get('keep.attr.name'), $default)) {
						unset($default[$this->get('keep.attr.name')]);
					}
				}

				if($this->key_exists($this->get('keep.attr.name'), $default)) {
					if($this->key_exists('value', $this->get('tag.attr'))) {
						if($this->is_array($default[$this->get('keep.attr.name')])) {
							if($this->inArray($this->get('tag.attr.value'), $default[$this->get('keep.attr.name')])) {
								if($this->get('tag.tag') == 'input') {
									if($type == 'checkbox' || $type == 'radio') {
										$this->checked();
									}
								}
								if($this->get('tag.tag') == 'option') {
									$this->selected();
								}
							}
						} else {
							if($this->get('tag.tag') == 'input') {
								if($type == 'checkbox' || $type == 'radio') {
									if($this->get('tag.attr.value') == $default[$this->get('keep.attr.name')]) {
										$this->checked();
									}
								} else {
									$this->fds = 1;
									$this->set('tag.attr.value', $default[$this->get('keep.attr.name')]);
								}
							}
							if($this->get('tag.tag') == 'option') {
								if($this->get('tag.attr.value') == $default[$this->get('keep.attr.name')]) {
									$this->selected();
								}
							}
						}
					} else {
						if($this->get('tag.tag') == 'input') {
							if($type == 'checkbox' || $type == 'radio') {
								if($this->acceptable($default[$this->get('keep.attr.name')])) {
									$this->checked();
								}
							}
							elseif($type == 'text') {
								$this->set('tag.attr.value', $default[$this->get('keep.attr.name')]);
							} else {
								
							}
						} else {
							if($this->get('tag.tag') == 'option') {
								if($this->acceptable($default[$this->get('keep.attr.name')])) {
									$this->selected();
								}
							} else {
								if($this->get('tag.tag') == 'textarea') {
									$this->set('tag.text', $default[$this->get('keep.attr.name')]);
								}
							}
						}
					}
				}
			}
		}
		return 1;
	}

	public function as_php() {
		return 1;
	}


	public function remove_spaces(string $str) {
		return preg_replace('/\s+/', '', $str);
	}

	public function pistirio($as) {
		$res = $this->{'as_'.$as}();
		return $res;

	}

	public function is_object($element) {
		return is_object($element);
	}

	public function doctype($type) {
		$this->set('doctype', $type);
	}

	public function finalize() {

		if($this->get('this.unclosed_tags') > 0) {
			echo('You have more opened tags than you have closed.');
			die();
		}

		if($this->get('this.unclosed_tags') < 0) {
			echo('You have more closed tags than you have opened.');
			die();
		}

		$nested = $this->nested($this->get('this.document'));
		$document = $this->build_document($nested);

		if($document) {
			$this->cache($this->get('view'), $document);
		}

		$this->reset();
		return $document;
	}

	public function nested(array $data = []) {

	    $level = 0;
	    $prev = 0;

	    foreach($data as $key => $item) {

			if($level && $item['level'] > $level) {
				$data[$prev]['items'][$item['index']] = $data[$item['index']];
				unset($data[$item['index']]);
			} else {
				$prev = $item['index'];
				$level = $item['level'];
			}

	        if(isset($data[$prev]['items'])) {
	            $data[$prev]['items'] = $this->nested($data[$prev]['items']);
	        }

	    }

	    return $data;
	}

	public function build_document($data, $level = 0) {

		$contents = '';

		foreach($data as $key => $item) {

			if(isset($item['items'])) {
				$item['nested'] = $this->build_document($item['items'], $item['level']);
			}

			$item = $this->item_defaults($item);

			$item['space'] = $this->space_like_tab($level + $item['start_code_space_level']);
			$item['attr_string'] = "";
			$item['lower_tag'] = $this->lower($item['tag']);
			$this->set('doctype', $item['doctype']);

			if(!$this->is_empty($item['attr'])) {
				foreach($item['attr'] as $attr_name => $attr_value) {

					if($this->is_array($attr_value) || $this->is_object($attr_value)) {
						$attr_value = '';
					}
					if($this->is_null($attr_value) || $this->trim($attr_value) == '') {
						$attr_value = '';
					}

					if($attr_value) {
						if($attr_name == 'style') {
							if(!$this->last_in($this->trim($attr_value), ';')) {
								$attr_value.=';';
							}
							if($this->key_exists('class', $item['attr'])) {
								$this->style_to_file($attr_value, $item['attr']['class']);
							}
						}
					}

					if($item['attr_string']) {
						$item['attr_string'].=' ';
					}

					if($this->isNumeric($attr_name)) {
						$item['attr_string'].=$attr_value;
					} else {
						if($this->lower($item['tag']) == 'doctype') {
							if($this->trim($attr_value) == '') {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'=""';
								}
							} else {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'="'.(string)$attr_value.'"';
								}
							}
						} else {
							$item['attr_string'].=$attr_name.'="'.(string)$attr_value.'"';
						}
					}
				}
			}

			$item['attr_string'] = $this->trim($item['attr_string']);
			$item['attr_string'] = $item['attr_string'] ? ' '.$item['attr_string'] : '';

			switch($item['lower_tag'])
			{
				case'untaged':

				break;
				case'doctype':
					$item['tag_before'] = $item['open_tag'].'!'.$this->upper($item['tag']).$item['attr_string'].$item['close_tag'];
				break;
				case'title':
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
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
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].' /'.$item['close_tag'];
					if($item['nested']) {
						$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
						$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
					}
				break;
				default:
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].'/'.$item['tag'].$item['close_tag'];
				break;
			}

			if($item['tag_before'] && $item['lower_tag'] != 'untaged') {
				$contents.=$this->new_line();
				$item['contents'].=$this->new_line();
			}

			if($item['nested'] && $item['tag_after'] && $item['lower_tag'] != 'untaged') {
				$item['tag_after'] = $this->new_line().$item['space'].$item['tag_after'];
			}

			$contents.=$item['space'];
			$contents.=$this->implode('', $item['append_before_tag']);
			$contents.=$item['tag_before'];
			$contents.=$this->implode('', $item['append_before_text']);
			$contents.=$item['text'];
			$contents.=$this->implode('', $item['append_after_text']);
			$contents.=$item['nested'];
			$contents.=$item['tag_after'];
			$contents.=$this->implode('', $item['append_after_tag']);

		}

		return $contents;
	}

	public function item_defaults($item) {
		return [
		    'doctype'					=> $this->key_exists('doctype', $item) ? $item['doctype'] : 'html',
			'tag'						=> $this->key_exists('tag', $item) ? $item['tag'] : '',
			'lower_tag'					=> $this->key_exists('tag', $item) ? $this->lower($item['tag']) : '',
		    'attr'						=> $this->key_exists('attr', $item) ? $item['attr'] : [],
		    'text'						=> $this->key_exists('text', $item) ? $item['text'] : '',
		    'append_before_tag' 		=> $this->key_exists('append_before_tag', $item) ? $item['append_before_tag'] : [],
		    'append_after_tag'			=> $this->key_exists('append_after_tag', $item) ? $item['append_after_tag'] : [],
		    'append_before_text'		=> $this->key_exists('append_before_text', $item) ? $item['append_before_text'] : [],
		    'append_after_text'			=> $this->key_exists('append_after_text', $item) ? $item['append_after_text'] : [],
			'nested'					=> $this->key_exists('nested', $item) ? $item['nested'] : '',
			'contents'					=> $this->key_exists('contents', $item) ? $item['contents'] : '',
			'open_tag'					=> $this->key_exists('open_tag', $item) ? $item['open_tag'] : '',
			'close_tag'					=> $this->key_exists('close_tag', $item) ? $item['close_tag'] : '',
			'tag_after'					=> $this->key_exists('tag_after', $item) ? $item['tag_after'] : '',
			'tag_before'				=> $this->key_exists('tag_before', $item) ? $item['tag_before'] : '',
		    'index'						=> $this->key_exists('index', $item) ? $item['index'] : 0,
		    'level'						=> $this->key_exists('level', $item) ? $item['level'] : 0,
		    'prev_level'				=> $this->key_exists('prev_level', $item) ? $item['prev_level'] : 0,
		    'next_level'				=> $this->key_exists('next_level', $item) ? $item['next_level'] : 0,
		    'prev_index'				=> $this->key_exists('prev_index', $item) ? $item['prev_index'] : 0,
		    'next_index'				=> $this->key_exists('next_index', $item) ? $item['next_index'] : 0,
			'start_code_space_level'	=> $this->key_exists('start_code_space_level', $item) ? $item['start_code_space_level'] : 0,
		];
	}

	public function fail($error) {
		$this->echo($error);
		$this->exit();
	}

	public function syntax_error($error, $line = 0) {
		$this->fail('SYNTAX ERROR: '.$error.' on line '.$line);
	}

	public function redirect($url) {
		$this->header("Location: ".$this->url($url));
	}

	public function send() {

		foreach($this->get('this.on') as $callback) {
			$callback();
		}

		if($this->is_string($this->get('text'))) {
			$this->echo($this->get('text'));
		} else {
			$this->echo($this->get('Only string content you can send'));
		}

	}

	public function function_exists($name) {
		return $this->isset('functions.'.$name);
	}

	public function setGlobalVar($method, $parameters) {
		$this->global->{$method} = $parameters;
		return $this;
	}

	public function tag($tag = null) {

		$this->document();

		if(!$tag) {
			$this->set('this.unclosed_tags', $this->get('this.unclosed_tags') - 1);
			if($this->get('keep.attr.name') && $this->get('keep.attr.level', $this->get('this.level'))) {
				$this->set('keep', [
					'attr' => [
						'name' => '',
						'level' => '',
						'type' => '',
						'defineds' => []
					]
				]);
			}
			if($this->isset('this.form.index') && $this->get('this.form.level', $this->get('this.level'))) {
				$this->set('this.form', []);
			}
			if($this->get('this.level') > 0) {
				$this->set('this.level', $this->get('this.level') - 1);
			}
		} else {
			$this->set('this.unclosed_tags', $this->get('this.unclosed_tags') + 1);
			if($this->get('this.level') >= 0) {
				$this->set('this.level', $this->get('this.level') + 1);
			}
			$this->set('this.has_open_tag', 1);
			$this->set('tag.tag', $this->remove_spaces($tag));
			$this->set('tag.doctype', $this->get('doctype'));
		}
		return $this;
	}

    public function style_to_file($style, $class)
    {
    	$data = $style;

    	$contents = '';
		if($this->file_not_exists($this->assets_path('views/'.$this->get('view').'/hand/hand.css'))) {
			$this->make_file_and_folder_force($this->assets_path('views/'.$this->get('view').'/hand/hand.css'));
			$this->style_to_file($style, $class);
		} else {

			$style = $this->trim($data);
			$style = $this->replace_spaces_with_one($this->replace($this->new_line(), ' ', $style));
			$style = $this->trim($style);

			if(!$this->last_in($style, '}') && !$this->last_in($style, ';')) {
				$style.=';';
			}
			if(!$this->contains_in($style, '{') && !$this->contains_in($style, '}')) {
				$style = $this->explode(';', $style);
				foreach($style as $key => $value) {
					if($contents) {
						$contents.=$this->new_line();
					}
					$contents.=$this->space(1).$value;
				}
				$contents = $this->new_line().$this->space_to_dash($this->slash_and_dot_to_space($this->get('view')))." .".$this->implode(". ", $this->explode(" ", $this->replace_spaces_with_one($this->trim($class))))." {".$this->new_line().$this->space(1).$this->trim($contents).$this->new_line()."}".$this->new_line();

				if(!$this->contains_in($this->file_contents($this->assets_path('views/'.$this->get('view').'/hand/hand.css')), $contents)) {
					$this->file_append($this->assets_path('views/'.$this->get('view').'/hand/hand.css'), $contents);
				}
			}
			return $contents;
		}
    }

    public function get_body_class()
    {
        return $this->implode(' ', $this->get('this.bodyclass'));
    }

    public function implode($separator, $array)
    {
        return implode($separator, $array);
    }

	public function assets_img($img) {
		return '';
	}

    public function default_value($data = '') {
    	$this->set('tag.defineds.default_value', $data);
        return $this;
    }

    public function default_checked($data = '') {
    	$this->set('tag.defineds.default_checked', $data);
        return $this;
    }

    public function default_selected($data = '') {
    	$this->set('tag.defineds.default_selected', $data);
        return $this;
    }

    public function default_text($data = '') {
    	$this->set('tag.defineds.default_text', $data);
        return $this;
    }

    public function posted() {
    	$this->set('tag.defineds.posted', 1);
        return $this;
    }

	public function addClass($data = null) {
		if(!$data) {
			return $this;
		}
		$this->set('tag.attr.class', $this->get('tag.attr.class') ? $this->set('tag.attr.class', $this->get('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	public function checked() {
		$this->set('tag.attr.checked', 'checked');
		return $this;
	}

	public function class($data = '') {
		$this->set('tag.attr.class', $data);
		return $this;
	}

	public function text($text = '') {
		if($this->is_object($text)) {
			return $this;
		}
		if($text !== '') {
			if($this->get('tag.tag')) {
				$this->set('tag.text', $this->get('tag.text').$text);
			} else {
				$this->tag('untaged');
					$this->set('tag.text', $text);
				$this->tag();
			}
		}
		return $this;
	}

	public function escapeString($str) {
        $js_escape = array(
            "\r" => '\r',
            "\n" => '\n',
            "\t" => '\t',
            "'" => "\\'",
            '"' => '\"',
            '\\' => '\\\\'
        );
        return $this->str_trans($str, $js_escape);
    }

	public function attr($attr, $data = null)
	{
		$this->set('tag.attr.'.$attr, $data);
		return $this;
	}

	public function append_before_tag($data = null)
	{
		$this->push('tag.append_before_tag', $data);
		return $this;
	}

	public function append_after_tag($data = null)
	{
		$this->push('tag.append_after_tag', $data);
		return $this;
	}

	public function append_before_text($data = null)
	{
		$this->push('tag.append_before_text', $data);
		return $this;
	}

	public function append_after_text($data = null)
	{
		$this->push('tag.append_after_text', $data);
		return $this;
	}

	public function bodyClass($classes = '') {
		$classes = $this->replace_spaces_with_one($classes);
		$classes = $this->explode(' ', $classes);
		foreach($classes as $class) {
			if(!$this->inArray($class, $this->get('this.bodyclass'))) {
				$this->push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	public function make_include($file) {
		return $this->make_file($this->project($file.'.php'));
	}

	public function on($event, $callback) {
		return $this->push('this.on', [
			'event_name' => $event,
			'callback' => $callback
		]);
	}

	public function require($file, $position = 'require') {
		if($this->file_exists($this->assets_path($file))) {
			$url = $this->assets_url($file);
			$ext = $this->lower($this->file_extention($url));
			if(!$this->get('settings.'.$ext.'_'.$position)) {
				if(!$this->inArray($url, $this->get('this.resources.after.'.$ext.'_'.$position)) && !$this->inArray($url, $this->get('this.resources.before.'.$ext.'_'.$position))) {
					$this->push('this.resources.'.$this->get('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		}
	}

	public function space($number) {
		return $this->get_spaces_by_level($number, " ");
	}

	public function space_like_tab($number) {
		return $this->get_spaces_by_level($number, "	");
	}

	public function tab_space($number) {
		return $this->get_spaces_by_level($number, "\t");
	}

	public function get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	public function resources() {

		if($this->get('view')) {
			$css = 'views/'.$this->get('view').'.css';
			$js = 'views/'.$this->get('view').'.js';

			if(!$this->file_exists($this->assets_path($css))) {
				if($this->make_file_and_folder_force($this->assets_path($css))) {
					$this->require($css, 'auto_view');
				}
			} else {
				$this->require($css, 'auto_view');
			}
			if(!$this->file_exists($this->assets_path($js))) {
				if($this->make_file_and_folder_force($this->assets_path($js))) {
					$this->require($js, 'auto_view');
				}
			} else {
				$this->require($js, 'auto_view');
			}
		}

	}

	public function loadComponents(array $types) {
		foreach($types as $type) {
			foreach([$type.'_require', $type.'_dynamic', $type.'_auto_view'] as $setch) {
				if(!$this->get('settings.'.$setch)) {
					foreach(['before', 'after'] as $place) {
						foreach($this->get('this.resources.'.$place.'.'.$setch) as $comp) {
							if($type == 'css') {
								$this->tag('link')->attr('data-preload', true)->attr('href', $comp)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
							}
							elseif($type == 'js') {
								$this->tag('script')->attr('data-preload', true)->attr('src', $comp)->attr('type', 'text/javascript')->tag();
							}
						}
					}
				}
			}
		}
		return $this;
	}

	public function loadTheScriptsComponents() {
		if(!$this->get('settings.scripts')) {
			foreach($this->get('this.resources.after.scripts') as $ajavascript) {
				$this->tag('script')->data('preload', true)->text($ajavascript)->tag();
			}
			foreach($this->get('this.resources.before.scripts') as $bjavascript) {
				$this->tag('script')->data('preload', true)->text($bjavascript)->tag();
			}
		}
		return $this;
	}

    public function make_file_and_folder_force($file, $contents = '') {
    	if($this->file_not_exists($file)) {
    		if(!$this->isDir($this->getDirFile($file))) {
    			if($this->make_dir($this->getDirFile($file))) {
    				$this->make_file($file, $contents);
    			}
    		} else {
    			$this->make_file($file, $contents);
    		}
    	} else {
    		if($this->delete_file($file)) {
    			$this->make_file($file, $contents);
    		}
    	}
    	return 1;
	}

    public function make_dir_force($file, $mode = 0755, $recursive = true) {
        return @mkdir($file, $mode, $recursive);
	}

    public function make_file($file, $contents = '', $lock = false) {
    	if($this->file_not_exists($file)) {
    		if($this->write_file($file, $contents, $lock)) {
    			$this->set('make_file.success', 1);
    		} else {
    			$this->set('make_file.success', 0);
    		}
    	} else {
    		$this->set('make_file.already_exists', 1);
    		$this->set('make_file.file_name', $file);
    	}
	}

	public function storage($str = '') {
		return $this->disk('storage/'.$str);
	}

	public function storage_copy($source, $destination) {
		return $this->copy_dir(
			$this->storage($source), $destination
		);
	}

    public function project($str = '') {
        return $this->disk('views/default/'.$str);
	}

    public function public_path($str = '') {
        return $this->disk('public/'.$str);
	}

    public function assets_path($str = '') {
        return $this->disk('public/assets/'.$str);
	}

    public function views_path($str = '') {
        return $this->disk('views/'.$str);
	}

    public function http_is_secure() {
        return $this->http_scheme() == 'https';
	}

    public function http_scheme() {
        return $this->get('request.scheme');
	}

    public function http_host() {
        return $this->get('http.host');
	}

    public function url($extend = '', $parameters = []) {

    	if($this->http_is_secure()) {
	        $url = 'https://';
    	} else {
	        $url = 'http://';
    	}

		$url.=$this->http_host().($extend ? $this->fslash().$this->to_fslash($extend) : '');

    	if(!$this->is_empty($parameters)) {
    		$url.='?'.$this->build_query($parameters);
    	}

		return $url;
	}

    public function build_query($params) {
    	$query = '';
		foreach($params as $key => $param) {
			if($query) {
				$query.='&';
			}
			$query.=$key.'='.$param;
		}
		return $query;
	}

    public function assets_url($url = '') {
        return $this->url('assets/'.$this->to_fslash($url));
	}

    public function assets_images_url($url = '') {
        return $this->url('assets/img/'.$this->to_fslash($url));
	}

    public function delete_path($directory, $preserve = false) {
        if(!$this->isDir($directory)) {
            return false;
        }
        $items = new FilesystemIterator($directory);
        foreach ($items as $item) {
            if ($item->is_dir() && ! $item->isLink()) {
                $this->delete_path($item->getPathname());
            }
            else {
                $this->delete_file($item->getPathname());
            }
        }
        if (! $preserve) {
            @rmdir($directory);
        }
        return true;
    }

    public function copy_dir($directory, $destination, $options = null) {
        if(!$this->isDir($directory)) {
            return false;
        }
        $options = $options ?: FilesystemIterator::SKIP_DOTS;
        if (!$this->isDir($destination)) {
            $this->make_dir($destination, 0777, true);
        }
        $items = new FilesystemIterator($directory, $options);
        foreach ($items as $item) {
            $target = $destination.'/'.$item->getBasename();
            if ($item->isDir()) {
                $path = $item->getPathname();
                if(!$this->copy_dir($path, $target, $options)) {
                    return false;
                }
            }
            else {
                if (!$this->copy_file($item->getPathname(), $target)) {
                    return false;
                }
            }
        }
        return true;
    }

    public function copy_file($path, $target) {
        return copy($path, $target);
    }

	public function cache($file, $contents = null) {

    	$file = $this->project('_common/cache/'.$this->to_bslash($file.'.'.$this->get('doctype')));

    	if(!$this->is_null($contents)) {
    		if(!$this->isDir($dir = $this->getDirFile($file))) {
    			$this->make_dir($dir);
    		}
    		if($this->file_exists($file)) {
    			$this->delete_file($file);
    		}
			return $this->make_file($file, $contents);
		}

		if($this->file_exists($file)) {
			return $this->file_contents($file);
		}

		return '';
	}

	public function is_cached($file) {
		return $this->file_exists($this->project('_common/cache/'.$this->to_bslash($file.'.'.$this->get('doctype'))));
	}

    public function delete_file($files) {
        $success = true;
        foreach($this->is_array($files) ? $files : [$files] as $file) {
            if(!@unlink($file)) {
				$success = false;
			}
        }
        return $success;
	}

	public function slash_and_dot_to_bslash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], $this->bslash(), $str), $this->bslash());
	}

	public function slash_and_dot_to_fslash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], $this->fslash(), $str), $this->fslash());
	}

	public function slash_and_dot_to_space($str) {
		return $this->trim($this->replace(['/', '\\', '.'], ' ', $str), $this->fslash());
	}

	public function slash_and_dot_to_dash($str) {
		return $this->trim($this->replace(['/', '\\', '.'], '_', $str), $this->fslash());
	}

	public function space_to_dash($str) {
		return $this->replace(' ', '-', $str);
	}

	public function dot_to_bslash($str) {
		return $this->trim($this->replace('.', $this->bslash(), $str), $this->bslash());
	}

	public function dot_to_fslash($str) {
		return $this->trim($this->replace('.', $this->fslash(), $str), $this->fslash());
	}

	public function slash_to_dot($str) {
		return $this->trim($this->replace(['/', '\\'], '.', $str), '.');
	}

	public function class_separator_fix(string $class) {
		 return $this->trim($this->replace(['/', '.'], '\\', $class), '\\');
	}

	public function bslash() {
		return DIRECTORY_SEPARATOR;
	}

	public function new_line() {
		return "\n";
	}

	public function all() {
		return $this->memory;
	}

	public function console_log(string $log) {
		$this->echo($log);
	}

	public function set($key, $value = null) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'set'
		]);
	}

    public function isset($key) {
		return $this->find([
			'key' => $key,
			'method' => 'isset'
		]);
	}

    public function undefine($key) {
		return $this->find([
			'key' => $key,
			'method' => 'undefine'
		]);
	}

	public function push($key, $value) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'push'
		]);
	}

	public function get($key, $default = null) {
		return $this->find([
			'key' => $key,
			'default' => $default,
			'method' => 'get'
		]);

	}

	public function normalize($options) {

		if($options['method'] == 'set') {
			if(!$this->key_exists($options['table'], $this->memory)) {
				$this->memory[$options['table']] = [];
			}
			if($options['count'] > 0) {
				$options['array_captions'][$options['count']] = $options['value'];
				$data = $this->array_single_to_multidimentional($options['array_captions'], $options['count'] + 1);
				$this->memory[$options['table']] = $this->merge($this->memory[$options['table']], $data);
			} else {
				$this->memory[$options['table']] = $options['value'];
			}
			$options['results'] = $options['value'];
			return $options['results'];
		}
		elseif($options['method'] == 'isset') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		elseif($options['method'] == 'get') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		elseif($options['method'] == 'push') {
			if($options['count'] > 0) {
				$data = $this->find($options, $this->memory[$options['table']]);
				$data[] = $options['value'];
				$options['value'] = $data;
				$options['array_captions'][$options['count']] = $options['value'];
				$data = $this->array_single_to_multidimentional($options['array_captions'], $options['count'] + 1);
				$this->memory[$options['table']] = $this->merge($this->memory[$options['table']], $data);
			} else {
				$this->memory[$options['table']][] = $options['value'];
			}
			$options['results'] = $options['value'];
			return $options['results'];
		}
		elseif($options['method'] == 'undefine') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		return $options;
	}

    public function find($options, $something = []) {

    	if($this->key_exists('i', $options)) {

	    	if($options['i'] == $options['count']) {
	    		if($options['method'] == 'isset') {
					return 1;
				}
	    		elseif($options['method'] == 'get') {
	    			if($this->is_null($options['default'])) {
	    				return $something;
	    			} else {
						return $this->lower($options['default']) == $this->lower($something);
					}
				}
	    		elseif($options['method'] == 'set') {
					return $something;
				}
	    		elseif($options['method'] == 'push') {
					return $something;
				}
	    		elseif($options['method'] == 'unset') {
					return $something;
				}
	    		elseif($options['method'] == 'update') {
					return $something;
				} else {

				}
	    	} else {
				if($this->key_exists($options['array_captions'][$options['i']], $something)) {
					$options['i'] = $options['i'] + 1;
					return $this->find($options, $something[$options['array_captions'][$options['i'] - 1]]);
				} else {
		    		if($options['method'] == 'isset') {
						return 0;
					}
		    		elseif($options['method'] == 'get') {
						return 0;
					}
		    		elseif($options['method'] == 'set') {
						return 0;
					}
		    		elseif($options['method'] == 'push') {
						return 0;
					}
		    		elseif($options['method'] == 'unset') {
						return 0;
					}
		    		elseif($options['method'] == 'update') {
						return 0;
					} else {

					}
				}
			}
			return '';
		} else {

			$options = $this->merge([
				'key' => null,
				'value' => null,
				'method' => null
			], $options);

			if($this->first_in($options['key'], '.') || $this->last_in($options['key'], '.') || $this->contains_in($options['key'], '..')) {
		    	$this->fail('FATAL ERROR: WRONG COLLECTION KEY SKELETON');
	    	}

	    	$options['caption_key'] = $this->lower($options['key']);
	    	$options['array_captions'] = $this->explode('.', $options['caption_key']);
			$options['table'] = $options['array_captions'][0];
			unset($options['array_captions'][0]);
			$options['array_captions'] = $this->array_fix($options['array_captions']);
			$options['count'] = $this->count($options['array_captions']);
			$options['results'] = '';
			$options['i'] = 0;
	    	return $this->normalize($options);

		}
	}

	public function inArray($key, $element) {
		return in_array($key, $element);
	}

	public function acceptable($value) {
		return $this->inArray($value, ['yes', 'on', '1', 1, true, 'true'], true) ? 1 : 0;
	}

	public function getNonAlphaNumericCharacters() {
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

	public function mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->inArray($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

    public function underscoreToUpercase($name) {
    	$names = $this->trim($name, '_');
    	$names = $this->explode('_', $name);
    	$newName = '';
    	foreach($names as $name) {
    		$newName.=$this->upper_first($name);
    	}
    	return $newName;
    }

    public function upperToUnderscore($string) {
	    return $this->lower(
	    	$this->preg_replace('/(.)([A-Z)/', '$1_$2', $string)
	    );
	}

    public function preg_replace($regex, $expresion, $string) {
	    return preg_replace($regex, $expresion, $string);
	}

	public function is_int($element) {
		return $this->isInteger($element);
	}

	public function header($str) {
		return header($str);
	}

	public function isInteger($element) {
		return is_integer($element);
	}

	public function get_type($element) {
		return gettype($element);
	}

	public function is_string($element) {
		return is_string($element);
	}

	public function isNotNull($element) {
		return !$this->is_null($element);
	}

	public function isDouble($element) {
		return is_double($element);
	}

	public function isFloat($element) {
		return is_float($element);
	}

	public function isNumeric($element) {
		return is_numeric($element);
	}

	public function is_empty($something) {
		return empty($something);
	}

	public function array_fix($array) {
		return array_values($array);
	}

	public function stringlength($str) {
		return strlen($str);
	}

	public function sub($string, $start = 0, $length = null) {
		if($this->is_null($length)) {
			return mb_substr($string, $start);
		} else {
			return mb_substr($string, $start, $length);
		}
	}

	public function file_extention($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

    public function count($str) {
		return count($str);
	}

	public function replace_spaces_with_one($str = '') {
		return preg_replace('/\s+/', '', $str);
	}

	public function decode($data) {
		return json_decode($data);
	}

	public function encode($data) {
		return json_encode($data);
	}

    public function isDir($str) {
		return is_dir($str);
	}

    public function getDirFile($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

    public function file_contents($file, $lock = false) {
		return file_get_contents($file);
	}

    public function file_append($file, $contents) {
		return file_put_contents($file, $contents, FILE_APPEND);
	}

    public function write_file($file, $contents, $lock = false) {
		return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
	}

    public function trimleft($source, $sym) {
    	if($this->is_null($sym)) {
    		return ltrim($source);
    	} else {
			return ltrim($source, $sym);
    	}
	}

	public function str_trans($one, $two) {
		return strtr($one, $two);
	}

	public function pos($haystack, $needle) {
		return mb_strpos($haystack, $needle);
	}
	public function length($str) {
		return mb_strlen($str);
	}


	public function file_not_exists($file) {
		return $this->file_exists($file) == 0;
	}

	public function echo($string) {
		echo($string);
	}

	public function upper($str) {
		return strtoupper($str);
	}

	public function upper_first($str) {
		return ucfirst($str);
	}

	public function exit() {
		exit;
	}

	public function call($method, $arguments) {
		if(method_exists($this, $method)) {
			return call_user_func_array([$this, $method], $arguments);
		} else {
			$this->fail('Method "'.$method.'" not exists.');
		}
	}

	public function getParsedApplication() {
		$project = $this->file_contents($this->disk('vendor\brosta\interframework\src\Interframework\signals.php'));
		$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
		$project = $parser->parse($project);
		$project = json_decode(json_encode($project), true);
		return $this->is_empty($project) ? false : $project;
	}

	public function updated_application() {
		if($this->file_exists($this->storage('updates/application.php'))) {
			return require($this->storage('updates/application.php'));
		}
		return false;
	}

	public function save_application($contents) {
		return $this->make_file_and_folder_force($this->storage('application.php'), $contents);
	}

	public function array_to_string($frontend = []) {

		$backend = [
			's' => "",
			'ss' => "",
			'var' => "",
			'with_numeric_keys' => 1,
			'count' => array_key_exists('value', $frontend) ? count($frontend['value']) : 0,
			'index' => array_key_exists('index', $frontend) ? $frontend['index'] : 0,
			'level' => array_key_exists('level', $frontend) ? $frontend['level'] : 0,
			'space' => array_key_exists('space', $frontend) ? $frontend['space'] : 0,
			'value' => array_key_exists('value', $frontend) ? $frontend['value'] : 0,
			'caption' => array_key_exists('caption', $frontend) ? $frontend['caption'] : '',
			'keytype' => "",
			'newline' => "",
			'spacetab' => "\t",
			'newspace' => "",
			'valuetype' => 'array',
			'index_prev' => 0,
			'rowseparator' => ",",
			'opentagsymbol' => "[",
			'closetagsymbol' => "]",
			'key_separator_value' => " => "
		];

		$export = $backend;

		foreach($backend['value'] as $key => $value) {
			$export['value'] = $value;
			$export['newline'] = "\n";
			$export['keytype'] = gettype($key);
			$export['valuetype'] = gettype($value);
			if($export['keytype'] == 'integer') {
				if($export['valuetype'] != 'array') {
					$export['key'] = $key;
				} else {
					if($export['with_numeric_keys']) {
						$export['key'] = $key;
					} else {
						$export['key'] = '';
						$export['key_separator_value'] = '';
					}
				}
			} else {
				$export['key'] = "'".$key."'";
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
					$export['rowseparator']=$backend['rowseparator'].$backend['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$backend['newline'].$export['newline'];
				}
				$export['nested'] = $this->array_to_string([
			    	'space' => $export['space'],
			    	'caption' => $export['caption'],
			    	'value' => $export['value'],
			    	'level' => $export['level'],
			    	'count' => count($export['value']),
			    	'index' => $export['index'],
			    	'index_prev' => 0,
			    ]);
				if(!$export['nested']) {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['key'];
					$export['var'].=$export['key_separator_value'];
					$export['var'].=$export['opentagsymbol'];
					$export['var'].=$export['closetagsymbol'];
					$export['var'].=$export['rowseparator'];
				} else {
					$export['var'].=$export['newspace'];
					$export['var'].=$export['s'];
					$export['var'].=$export['key'];
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
		            case 'double':
		            	$export['value'] = $export['value'];
		            break;
		            case 'string':
		            	$export['value'] = "'".$this->str_trans($export['value'], array(
				            "\r" => '\r',
				            "\n" => '\n',
				            "\t" => '\t',
				            "'" => "\\'",
				            '"' => '\"',
				            '\\' => '\\\\'
				        ))."'";
		            break;
		            case 'NULL':
		            	$export['value'] = 'null';
		            break;
		        }
				if($export['count'] > 1 && $export['index_prev'] != $export['count'] - 1) {
					$export['rowseparator']=$backend['rowseparator'].$backend['newline'].$export['newline'];
				} else {
					$export['rowseparator']=$backend['newline'].$export['newline'];
				}
				$export['var'].=$export['newspace'];
				$export['var'].=$export['ss'];
				$export['var'].=$export['key'];
				$export['var'].=$export['key_separator_value'];
				$export['var'].=$export['value'];
				$export['var'].=$export['rowseparator'];
			}
		}
		return $export['var'];
	}

	public function chars_control($code = null, $type = 'symbol') {
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

	public function chars_symbols($code = null, $type = 'symbol') {
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

	public function chars_alpha_upper_from_lower($code = null, $type = 'symbol') {
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

	public function chars_alpha_lower_from_upper($code = null, $type = 'symbol') {
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
	public function chars_alpha_lower($code = null, $type = 'symbol') {
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

	public function chars_alpha_upper($code = null, $type = 'symbol') {
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

	public function chars_numbers($code = null, $type = 'symbol') {
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

	public function process_text_to_ascii($string, &$offset) {
	    $code = ord(substr($string, $offset,1)); 
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

	public function text_to_ascii($text) {
		$results = "";
		$offset = 0;
		while($offset >= 0) {
			if($results) {
				$results.=" ";
			}
		    $results.=$this->process_text_to_ascii($text, $offset);
		}
		return $results;
	}

	private function gg_symbol_string($output) {
		if($output->a == '"' || $output->a == "'") {
			if($output->quote == '"' && $output->a == '"' || $output->quote == "'" && $output->a == "'") {
				if($output->hstring) {
					$this->syntax_error('unexpected string', $output->line);
				} else {
					$output->lstring = "";
					$output->lstring = $output->quote.$output->string.$output->quote;
					$output->lstring_control = $output->lcontrol;
					$output->hstring = 1;
					$output->string = "";
					$output->is_string = 0;
					$output->quote = '';
					$output->lcontrol = '';
				}
			} else {
				$output->string.=$output->a_o;
			}
		} else {
			$output->string.=$output->a_o;
		}
		return $output;
	}

	private function gg_alpha_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function gg_number_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function gg_control_string($output) {
		$output->string.=$output->a_o;
		return $output;
	}

	private function ident_exists($output, $key) {
		return isset($output->ident[$key]['default']);
	}

	private function ex_change_ident($output) {

		if($output->tmp_ident !== '') {
			if($output->hident == 0) {
				$output->ident = [];
				$output->hident = 1;
			}

			$output->ident[] = [
				'hstring' => $output->hstring,
				'string_control' => $output->hstring ? $output->lstring_control : '',
				'string' => $output->hstring ? $output->lstring : '',
				'control' => $output->lcontrol,
				'default' => $output->tmp_ident,
				'original' => $output->tmp_ident_o,
			];

			$output->hstring = 0;
			$output->lstring_control = '';
			$output->lcontrol = '';
			$output->tmp_ident = '';
			$output->tmp_ident_o = '';

		}

		return $output;
	}

	private function build_code($output) {
		$name = '';
		if($output->hident) {
			$output->hident = 0;
			foreach($output->ident as $row) {
				$output->contents.=$row['string_control'].$row['control'].$row['original'].$row['string'];
			}
		}
		if($output->hstring) {
			$output->hstring = 0;
			$output->contents.=$output->lstring_control.$output->lstring.$output->lcontrol.$output->a_o;
		} else {
			$output->contents.=$output->lcontrol.$output->a_o;
		}
		$output->lcontrol = '';
		return $output;
	}

	private function gg_symbol($output) {
		if($output->a == '"' || $output->a == "'") {
			$output->is_string = 1;
			$output->quote = $output->a_o;
		} else {
			if($output->a == '_') {
				$output->tmp_ident.=$output->a;
				$output->tmp_ident_o.=$output->a_o;
			} else {
				$output = $this->ex_change_ident($output);
				if($output->may['assign']) {
					$output->may['assign'] = 0;
					if($output->a == '=') {
						$output->may['equal'] = 1;
					} else {
						$this->tag('assign');
						if($output->a == '$') {
							$output->may['var'] = 1;
						}
					}
				}
				elseif($output->may['var']) {
					$output->may['var'] = 0;
					if($output->hident) {
						$output->hident = 0;
						$this->tag('var')->attr('name', $output->ident[0]['original']);
						if($output->a == '=') {
							$output->may['assign'] = 1;
						}
						elseif($output->a == ';') {
							$this->tag()->tag()->tag();
							$output->is['end'] = 1;
						}
					} else {
						$this->fail(3412);
					}
				} else {
					if($output->a == '$') {
						$output->may['var'] = 1;
					}
				}
			}
		}
		return $output;
	}

	private function gg_alpha($output) {
		$output->tmp_ident.=$output->a;
		$output->tmp_ident_o.=$output->a_o;
		return $output;
	}

	private function gg_number($output) {
		$output->tmp_ident.=$output->a;
		$output->tmp_ident_o.=$output->a_o;
		return $output;
	}

	private function gg_control($output) {
		$output = $this->ex_change_ident($output);
		$output->lcontrol.=$output->a_o;
		return $output;
	}

	private function parse($output) {
		if($output->is_string) {
			$output = $this->{'gg_'.$output->type.'_string'}($output);
		} else {
			$output = $this->{'gg_'.$output->type}($output);
		}
		return $output;
	}

	private function character($output) {

		$output->b = $output->a;
		$output->b_o = $output->a_o;

		if($output->decimal >= 0 && $output->decimal <= 127) {

			if($output->decimal >= 0 && $output->decimal <= 32 || $output->decimal == 127) {
				if($output->chars_control == null) {
					$output->chars_control = $this->chars_control();
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
						$output->chars_alpha_upper = $this->chars_alpha_upper();
					}
					$output->a_o = $output->chars_alpha_upper[$output->decimal]['symbol'];
					$output->a = $this->chars_alpha_lower_from_upper($output->decimal);
					$output->is_lower = 0;
					$output->is_upper = 1;
				}
				elseif($output->decimal >= 97 && $output->decimal <= 122) {
					if($output->chars_alpha_lower == null) {
						$output->chars_alpha_lower = $this->chars_alpha_lower();
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
					$output->chars_numbers = $this->chars_numbers();
				}
				$output->a = $output->chars_numbers[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'number';
			}
			elseif($output->decimal >= 33 && $output->decimal <= 47 || $output->decimal >= 58 && $output->decimal <= 64 || $output->decimal >= 91 && $output->decimal <= 96 || $output->decimal >= 123 && $output->decimal <= 126) {
				if($output->chars_symbols == null) {
					$output->chars_symbols = $this->chars_symbols();
				}
				$output->a = $output->chars_symbols[$output->decimal]['symbol'];
				$output->a_o = $output->a;
				$output->type = 'symbol';
			}
		}
		return $output;
	}

	private function get_exe_options($input) {
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
		$output->is_string										= 0;
		$output->ident											= [];
		$output->ident_o										= "";
		$output->tmp_ident										= "";
		$output->tmp_ident_o									= "";
		$output->quote											= null;
		$output->contents										= '';
		$output->a												= null;
		$output->a_o											= null;
		$output->decimal										= null;
		$output->max											= count($input);
		$output->input											= $input;
		$output->chars_alpha_lower								= null;
		$output->chars_alpha_upper								= null;
		$output->chars_numbers									= null;
		$output->chars_control									= null;
		$output->chars_symbols									= null;
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

	public function exe($contents) {

		if(!isset($project)) {
			$project = new stdClass;
		}

		if(!isset($project->ram)) {
			$project->ram = [];
		}

		if(!isset($project->ram['this'])) {
			$this->tag('div')->attr('style', 'background-color:#222222;padding:30px;text-align:center;color:#b35807;font-size:22px;font-weight:bold');
				$this->text('Welcome to an fresh installation of Brostά Interframework!');
			$this->tag();
		}


		$contents = $this->text_to_ascii($contents);
		$contents = $this->explode(' ', $this->trim($contents));

		$this->set('pistirio', 'php');
		$results = $this->results($this->get_exe_options($contents));
		$this->set('pistirio', 'html');

		return $results;
	}

	private function results($output) {
		if($output->input) {
			$output->decimal = $output->input[$output->current];
			unset($output->input[$output->current]);
			$output = $this->character($output);
			$output = $this->parse($output);
			$output->current++;
			$output = $this->results($output);
		}
		return $output;
	}

}