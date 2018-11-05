<?php

namespace Brosta;

class Signal {

	private $memory = [];

	public function construct($local = '', $install = '/') {

		$this->reset();

		if($this->undefined('disk.local')) {
			$this->define('disk.local', $this->to_back_slash($local));
			$this->define('install.redirect', $install);
		}

		if($this->value('disk.local')) {
			if($this->include_exists('_common/config/server')) {
				if($this->undefined('this.include') || $this->is_not_array($this->value('this.include'))) {
					$this->define('this.include', []);
				}
				$this->include('_common/config/server');
			} else {
				if($this->is_dir($this->storage('manufacturer/_common'))) {
					if($this->defined('install.redirect') && $this->value('install.redirect')) {
						if($this->copy_dir($this->storage('manufacturer/_common'), $this->project('_common'))) {
							$this->redirect($this->value('install.redirect'));
						}
					}
					$this->fail('Fattal error: System can not installed. The argument 2 are missing to redirect after instalation from construct');
					$this->exit();
				}
			}
		}
	}

    private function first_in($haystack, $needle)
    {
		if($needle !== '' && mb_substr($haystack, 0, mb_strlen($needle)) === (string) $needle) {
			return true;
		}
        return false;
    }

    private function last_in($haystack, $needle) {
		if(mb_substr($haystack, -mb_strlen($needle)) === (string)$needle) {
			return true;
		}
        return false;
    }

    private function contains_in($haystack, $needle) {
		if($needle !== '' && mb_strpos($haystack, $needle) !== false) {
			return true;
		}
        return false;
    }

    private function lower($str) {
		return strtolower($str);
	}

    private function explode($separator, $string) {
    	return explode($separator, $string);
	}

	private function key_exists($name, $array) {
		foreach($array as $key => $value) {
			if($key == $name) {
				return true;
			}
		}
		return false;
	}

	private function array_single_to_multidimentional($array, $count = 0, $current = 0) {
	    if($count - 1 === $current) {
	        $array = $array[$current];
	    } else {
	    	$array = [$array[$current] => $this->array_single_to_multidimentional($array, $count, $current + 1)];
	    }
	    return $array;
	}

	private function merge($defaults, $replaces) {

		if($this->is_not_array($defaults)) {
			return $defaults;
		}

		foreach($replaces as $key => $value) {
			if(!isset($defaults[$key]) || (isset($defaults[$key]) && $this->is_not_array($defaults[$key]))) {
				$defaults[$key] = [];
			}
			if($this->is_array($value)) {
				$value = $this->merge($defaults[$key], $value);
			}
			$defaults[$key] = $value;
		}

		return $defaults;
	}

	private function is_not_array($element) {
		return $this->is_array($element) == 0;
	}

	private function is_array($element) {
		return is_array($element) ? 1 : 0;
	}

    private function undefined($key) {
		return $this->defined($key) == 0;
	}

	private function to_back_slash($str) {
		return $this->trim($this->string_replace(['/', '\\'], $this->back_slash(), $str), $this->back_slash());
	}

	private function string_replace($search, $replace, $subject) {
		return str_replace($search, $replace, $subject);
	}

	private function trim($str, $character_mask = null) {
		if($this->is_null($character_mask)) {
			return trim($str);
		} else {
			return trim($str, $character_mask);
		}
	}

	private function is_null($element) {
		return is_null($element);
	}

	private function disk($str = '') {
		$path = $this->trimright($this->value('disk.local'), $this->back_slash());
		if($str) {
			$path = $path.$this->back_slash().$this->to_back_slash($str);
		}
		return $path;
	}

    private function trimright($source, $symbol = null) {
    	if($this->is_null($symbol)) {
    		return rtrim($source);
    	} else {
			return rtrim($source, $symbol);
    	}
	}

    private function make_dir($dir, $mode = 0755, $recursive = true) {
    	if($this->is_not_dir($dir)) {
        	if(mkdir($dir, $mode, $recursive)) {
				$this->define('make_dir.success', 1);
        	} else {
    			$this->define('make_dir.success', 0);
    		}
        } else {
        	$this->define('make_dir.already_exists', 1);
        }
	}

    private function is_not_dir($str) {
		return $this->is_dir($str) == 0;
	}

	private function include_exists($file) {
		return $this->file_exists($this->project($file.'.php'));
	}

	private function file_exists($file) {
		return file_exists($file);
	}

	private function get_include_contents($file) {
		return $this->file_contents($this->project($file.'.php'));
	}

	private function include($file, $brosta = 0) {

		if($brosta) {
			$contents = $this->get_include_contents($file);
			$contents =  $this->ascii($contents);
			$this->text($contents);
		} else {
			$file = $this->project($file.'.php');
			if(!$this->in_array($file, $this->value('this.include'))) {
				$this->push('this.include', $file);
				require($file);
			}
		}

		return $this;

	}

	private function reset() {
		$this->define(
			'this', [
				'include'					=> [],
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
		$this->define('new', $this->value('this'));
		$this->new_tag();
	}

	private function new_tag() {
		$this->define(
			'tag', $this->value('new')
		);
	}

	private function http() {

		$this->define('request.uri', $this->lower($this->to_forward_slash($this->value('request.uri'))));

		if(!$this->value('request.uri')) {
			$this->define('request.uri', $this->forward_slash());
		}

		$uri = $this->value('request.uri');

		$this->define('request.uri', $uri);
		$this->define('request.library', 'desktop');
		$this->define('request.show', 'index');
		$this->define('request.dynamic_params', []);

		if($this->value('request.uri') != $this->forward_slash()) {
			$uri = $this->explode($this->forward_slash(), $this->value('request.uri'));
			if(isset($uri[0])) {
				$this->define('request.library', $uri[0]);
				unset($uri[0]);
				if(isset($uri[1])) {
					$this->define('request.show', $uri[1]);
					unset($uri[1]);
					if(isset($uri[2])) {
						$this->define('request.uri_params', $this->isEmpty($uri) ? false : $this->array_fix_key_numbers($uri));
					}
				}
			}
		}
	}

	private function to_forward_slash($str = '') {
		return $this->trim($this->string_replace(['/', '\\'], '/', $str), '/');
	}

	private function forward_slash() {
		return '/';
	}

	private function document(array $data) {
		if($this->value('this.has_open_tag')) {
			$this->pistirio();
			$this->define('this.document.'.$this->value('this.index'), $data);
			$this->new_tag();
			$this->define('this.prev_index', $this->value('this.index'));
			$this->define('this.prev_level', $this->value('this.level'));
			$this->define('this.index', $this->value('this.index') + 1);
			$this->define('this.has_open_tag', 0);
		}
	}

	private function remove_spaces(string $str) {
		return preg_replace('/\s+/', '', $str);
	}

	private function pistirio() {

		if($this->value('tag.tag') !== 'untaged') {
			if($this->value('tag.tag', 'form')) {
				$this->define('this.form', [
						'name' => $this->value('tag.attr.name'),
						'index' => $this->value('this.index'),
						'level' => $this->value('this.level')
					]
				);
			}

			if($this->defined('tag.attr.name')) {
				$this->define('keep.attr.name', $this->string_replace('[]', '', $this->value('tag.attr.name')));
				$this->define('keep.attr.level', $this->value('this.level'));
				$this->define('keep.attr.type', $this->defined('tag.attr.type') ? $this->value('tag.attr.type') : false);
				$this->define('keep.attr.defineds', $this->value('tag.defineds'));
			}

			if($this->defined('keep.attr.name')) {
				$this->define('default', []);
			}
			return 1;
		}
		return 0;
	}

	private function is_object($element) {
		return is_object($element);
	}

	private function doctype($type) {
		$this->define('doctype', $type);
	}

	private function finalize() {

		$document = $this->value('this.document');

		if($document) {

			if($this->value('this.unclosed_tags') > 0) {
				echo('You have more opened tags than you have closed.');
				die();
			}

			if($this->value('this.unclosed_tags') < 0) {
				echo('You have more closed tags than you have opened.');
				die();
			}

			$nested = $this->nested($document);
			$document = $this->build($nested);

			if($document) {
				$this->cache($this->value('view'), $document);
			}

			$this->reset();
			return $document;
		}
	}

	private function nested(array $data = []) {

	    $level = 0;
	    $prev = 0;

	    foreach($data as $key => $item) {

	    	if(!$data[$item['index']]['contents']) {
	    		$data[$item['index']]['contents'] = $this->build([$data[$item['index']]]);
	    	}

	        if(isset($data[$prev]['items'])) {
	            $data[$prev]['items'] = $this->nested($data[$prev]['items']);
	        }

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

	        // here

	    }

	    return $data;
	}

	private function build($data, $level = 0) {

		$contents = '';

		foreach($data as $key => $item)
		{

			if(isset($item['items'])) {
				$item['nested'] = $this->build($item['items'], $item['level']);
			}

			$item['space'] = $this->tab_space($level + $item['start_code_space_level']);

			$item['attr_string'] = "";

			$item['lower_tag'] = $this->lower($item['tag']);

			$this->define('doctype', $item['doctype']);

			if(!$this->isEmpty($item['attr'])) {
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
					//
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

// -----------------------------------------------------------
// -----------------------------------------------------------
// -----------------------------------------------------------
// -----------------------------------------------------------
// -----------------------------------------------------------

	private function fail($error) {
		$this->echo($error);
		$this->exit();
	}

	private function redirect($url) {
		$this->header("Location: ".$this->url($url));
		$this->exit();
	}

	private function monitor() {
		if($this->defined('monitor.info') && $this->value('monitor.info')) {
			$this->text($this->value('monitor.info'));
		}

		if($this->value('monitor.doctype', 'html')) {
			$this->tag('doctype')->attr('html')->tag();
			$this->tag('html')->attr('lang', 'en');
				$this->tag('head');
					$this->tag('meta')->attr('charset', 'utf-8')->tag();
					$this->tag('meta')->attr('name', 'viewport')->attr('content', 'width=device-width, initial-scale=1, maximum-scale=1.0')->tag();
					$this->tag('meta')->attr('httpequiv', 'Content-Type')->attr('content', 'text/html; charset=UTF-8')->tag();
					$this->tag('title')->attr('id', 'pageTitle')->text($this->value('page.title'))->tag();
					$this->tag('meta')->attr('name', 'John Stamoutsos')->attr('content', 'Brosta')->tag();
					$this->tag('meta')->attr('id', 'domain')->attr('content', 'My domain')->tag();
					$this->loadTheCssComponents();
				$this->tag();
				$this->tag('body');
					$this->tag('div')->attr('class', 'container-fluid');
						$this->tag('nav')->attr('class', 'navbar navbar-dark bg-dark');
							$this->tag('a')->attr('class', 'navbar-brand')->attr('href', '/');
								$this->tag('img')->attr('class', 'd-inline-block align-top')->attr('src', '/assets/img/brosta-words-colored-spray-logo.png')->attr('style', 'width:120px;height:34px;')->tag();
							$this->tag();
						$this->tag();
						$this->tag('div')->attr('class', 'sidebar-left');
							$this->tag('ul')->attr('class', 'navbar-nav');
								$this->tag('li');
									$this->tag('a')->attr('href', '/')->text('Desktop')->tag();
								$this->tag();
								$this->tag('li');
									$this->tag('a')->attr('href', '/install')->text('Install')->tag();
								$this->tag();
								$this->tag('li');
									$this->tag('a')->attr('href', '/system/settings')->text('Settings')->tag();
								$this->tag();
								$this->tag('li');
									$this->tag('a')->attr('href', '/database')->text('Database')->tag();
								$this->tag();
							$this->tag();
						$this->tag();
						$this->tag('div')->attr('class', 'sidebar-center');
							$this->tag('div')->attr('class', 'box grey')->attr('style', 'padding:10px');
								$this->tag('textarea')->attr('style', 'padding:20px;width:100%;height:851px');
									$this->text($this->value('text'));
								$this->tag();
							$this->tag();
						$this->tag();
					$this->tag();
					$this->loadTheJsComponents();
					$this->loadTheScriptsComponents();
				$this->tag();
			$this->tag();
		}
	}

	private function signal($signal) {
		$this->memory = $this->merge($this->memory, $signal);
		$this->http();
		$this->define('view', $this->to_back_slash($this->value('request.library').'/'.$this->value('request.show')));
		$this->define('this.start_code_space_level', 0);
		$this->define('after_or_before', 'after');
		if($this->include_exists($this->value('view'))) {
			$this->include($this->value('view'));
			$this->doctype($this->memory['doctype']);
			$this->define('text', $this->finalize());
		}
		$this->define('after_or_before', 'before');
		$this->define('this.start_code_space_level', 0);
		$this->include('_common/resources');
		$this->define('after_or_before', 'after');
		$this->resources();
		$this->define('after_or_before', 'before');
		$this->monitor();
		$this->define('text', $this->finalize());
		$this->send();
	}

	private function send() {
		if($this->isString($this->value('text'))) {
			$this->echo($this->value('text'));
		} else {
			$this->echo($this->value('Only string content you can send'));
		}
		//print_r(self::$memory);
	}

	private function function_exists($name) {
		return $this->defined('functions.'.$name);
	}

	private function tag($tag = null) {

		$this->document(
			[
				// From new
			    'doctype'					=> $this->value('tag.doctype'),
				'tag'						=> $this->value('tag.tag'),
			    'attr'						=> $this->value('tag.attr'),
			    'text'						=> $this->value('tag.text'),
			    'append_before_tag' 		=> $this->value('tag.append_before_tag'),
			    'append_after_tag'			=> $this->value('tag.append_after_tag'),
			    'append_before_text'		=> $this->value('tag.append_before_text'),
			    'append_after_text'			=> $this->value('tag.append_after_text'),
				'nested'					=> $this->value('tag.nested'),
				'contents'					=> $this->value('tag.contents'),
				'open_tag'					=> $this->value('tag.open_tag'),
				'close_tag'					=> $this->value('tag.close_tag'),
				'tag_after'					=> $this->value('tag.tag_after'),
				'tag_before'				=> $this->value('tag.tag_before'),
				// From live
			    'index'						=> $this->value('this.index'),
			    'level'						=> $this->value('this.level'),
			    'prev_level'				=> $this->value('this.prev_level'),
			    'next_level'				=> $this->value('this.next_level'),
			    'prev_index'				=> $this->value('this.prev_index'),
			    'next_index'				=> $this->value('this.next_index'),
				'start_code_space_level'	=> $this->value('this.start_code_space_level'),
			]
		);

		if(!$tag) {
			$this->define('this.unclosed_tags', $this->value('this.unclosed_tags') - 1);
			if($this->value('keep.attr.name') && $this->value('keep.attr.level', $this->value('this.level'))) {
				$this->define('keep', [
					'attr' => [
						'name' => '',
						'level' => '',
						'type' => '',
						'defineds' => []
					]
				]);
			}
			if($this->defined('this.form.index') && $this->value('this.form.level', $this->value('this.level'))) {
				$this->define('this.form', []);
			}
			if($this->value('this.level') > 0) {
				$this->define('this.level', $this->value('this.level') - 1);
			}
		} else {
			$this->define('this.unclosed_tags', $this->value('this.unclosed_tags') + 1);
			if($this->value('this.level') >= 0) {
				$this->define('this.level', $this->value('this.level') + 1);
			}
			$this->define('this.has_open_tag', 1);
			$this->define('tag.tag', $this->remove_spaces($tag));
			$this->define('tag.doctype', $this->value('doctype'));
		}
		return $this;
	}

    private function style_to_file($style, $class)
    {
    	$data = $style;

    	$contents = '';
		if($this->file_not_exists($this->assets_path('views/'.$this->value('view').'/hand/hand.css'))) {
			$this->make_file_and_folder_force($this->assets_path('views/'.$this->value('view').'/hand/hand.css'));
			$this->style_to_file($style, $class);
		} else {

			$style = $this->trim($data);
			$style = $this->replace_spaces_with_one($this->string_replace($this->new_line(), ' ', $style));
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
				$contents = $this->new_line().$this->space_to_dash($this->slash_and_dot_to_space($this->value('view')))." .".$this->implode(". ", $this->explode(" ", $this->replace_spaces_with_one($this->trim($class))))." {".$this->new_line().$this->space(1).$this->trim($contents).$this->new_line()."}".$this->new_line();

				if(!$this->contains_in($this->file_contents($this->assets_path('views/'.$this->value('view').'/hand/hand.css')), $contents)) {
					$this->file_append($this->assets_path('views/'.$this->value('view').'/hand/hand.css'), $contents);
				}
			}
			return $contents;
		}
    }

    private function get_body_class()
    {
        return $this->implode(' ', $this->value('this.bodyclass'));
    }

    private function implode($separator, $array)
    {
        return implode($separator, $array);
    }

	private function assets_img($img) {
		return '';
	}

    private function default_value($data = '')
    {
    	$this->define('tag.defineds.default_value', $data);
        return $this;
    }

    private function default_checked($data = '')
    {
    	$this->define('tag.defineds.default_checked', $data);
        return $this;
    }

    private function default_selected($data = '')
    {
    	$this->define('tag.defineds.default_selected', $data);
        return $this;
    }

    private function default_text($data = '')
    {
    	$this->define('tag.defineds.default_text', $data);
        return $this;
    }

    private function posted()
    {
    	$this->define('tag.defineds.posted', 1);
        return $this;
    }

	private function addClass($data = null) {
		if(!$data) {
			return $this;
		}
		$this->define('tag.attr.class', $this->value('tag.attr.class') ? $this->define('tag.attr.class', $this->value('tag.attr.class').' '.$data) : $data);
		return $this;
	}

	private function class($data = '') {
		$this->define('tag.attr.class', $data);
		return $this;
	}

	private function text($text = '') {
		if($this->is_object($text)) {
			return $this;
		}
		if($text !== '') {
			if($this->value('tag.tag')) {
				$this->define('tag.text', $this->value('tag.text').$text);
			} else {
				$this->tag('untaged');
					$this->define('tag.text', $text);
				$this->tag();
			}
		}
		return $this;
	}

	private function escapeString($str) {
        $js_escape = array(
            "\r" => '\r',
            "\n" => '\n',
            "\t" => '\t',
            "'" => "\\'",
            '"' => '\"',
            '\\' => '\\\\'
        );
        return strtr($str, $js_escape);
    }

	private function get_string_between($string, $start, $end = 0){
		$string = " ".$string; 
		$ini = strpos($string, $start);
		if($ini == 0) {
			return "";
		}
		$ini += strlen($start);
		$len = strpos($string, $end, $ini) - $ini;
		return $this->substring($string, $ini, $len);
	}

	private function attr($attr, $data = null)
	{
		$this->define('tag.attr.'.$attr, $data);
		return $this;
	}

	private function append_before_tag($data = null)
	{
		$this->push('tag.append_before_tag', $data);
		return $this;
	}

	private function append_after_tag($data = null)
	{
		$this->push('tag.append_after_tag', $data);
		return $this;
	}

	private function append_before_text($data = null)
	{
		$this->push('tag.append_before_text', $data);
		return $this;
	}

	private function append_after_text($data = null)
	{
		$this->push('tag.append_after_text', $data);
		return $this;
	}

	private function bodyClass($classes = '') {
		$classes = $this->replace_spaces_with_one($classes);
		$classes = $this->explode(' ', $classes);
		foreach($classes as $class) {
			if(!$this->in_array($class, $this->value('this.bodyclass'))) {
				$this->push('this.bodyclass', $class);
			}
		}
		return $this;
	}

	private function make_include($file) {
		return $this->make_file($this->project($file.'.php'));
	}

	private function require($file, $position = 'require') {
		if($this->file_exists($this->assets_path($file))) {
			$url = $this->assets_url($file);
			$ext = $this->lower($this->file_extention($url));
			if(!$this->value('settings.'.$ext.'_'.$position)) {
				if(!$this->in_array($url, $this->value('this.resources.after.'.$ext.'_'.$position)) && !$this->in_array($url, $this->value('this.resources.before.'.$ext.'_'.$position))) {
					$this->push('this.resources.'.$this->value('after_or_before').'.'.$ext.'_'.$position, $url);
				}
			}
		}
	}

	private function space($number) {
		return $this->get_spaces_by_level($number, " ");
	}

	private function tab_space($number) {
		return $this->get_spaces_by_level($number, "\t");
	}

	private function get_spaces_by_level(int $number, string $operator) {
		$results = '';
		if($number > 0) {
			for($i=0; $i < $number; $i++) {
				$results.=$operator;
			}
		}
		return $results;
	}

	private function resources() {

		if($this->value('view')) {
			$css = 'views/'.$this->value('view').'.css';
			$js = 'views/'.$this->value('view').'.js';

			if($this->file_exists($this->assets_path($css))) {
				$this->require($css, 'auto_view');
			} else {
				$this->make_file_and_folder_force($this->assets_path($css));
			}
			if($this->file_exists($this->assets_path($js))) {
				$this->require($js, 'auto_view');
			} else {
				$this->make_file_and_folder_force($this->assets_path($js));
			}
		}

	}

	private function loadTheCssComponents() {
		if(!$this->value('settings.css_require')) {
			foreach($this->value('this.resources.before.css_require') as $css_require) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_require)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		if(!$this->value('settings.css_dynamic')) {
			foreach($this->value('this.resources.before.css_dynamic') as $css_dynamic) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_dynamic)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		if(!$this->value('settings.css_auto_view')) {
			foreach($this->value('this.resources.before.css_auto_view') as $css_auto_view) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_auto_view)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		if(!$this->value('settings.css_require')) {
			foreach($this->value('this.resources.after.css_require') as $css_require) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_require)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		if(!$this->value('settings.css_dynamic')) {
			foreach($this->value('this.resources.after.css_dynamic') as $css_dynamic) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_dynamic)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		if(!$this->value('settings.css_auto_view')) {
			foreach($this->value('this.resources.after.css_auto_view') as $css_auto_view) {
				$this->tag('link')->attr('data-preload', true)->attr('href', $css_auto_view)->attr('rel', 'stylesheet')->attr('type', 'text/css')->tag();
			}
		}
		return $this;
	}

	private function loadTheJsComponents() {
		if(!$this->value('settings.js_require')) {
			foreach($this->value('this.resources.before.js_require') as $js_require) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_require)->attr('type', 'text/javascript')->tag();
			}
		}
		if(!$this->value('settings.js_dynamic')) {
			foreach($this->value('this.resources.before.js_dynamic') as $js_dynamic) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_dynamic)->attr('type', 'text/javascript')->tag();
			}
		}
		if(!$this->value('settings.js_auto_view')) {
			foreach($this->value('this.resources.before.js_auto_view') as $js_auto_view) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_auto_view)->attr('type', 'text/javascript')->tag();
			}
		}
		if(!$this->value('settings.js_require')) {
			foreach($this->value('this.resources.after.js_require') as $js_require) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_require)->attr('type', 'text/javascript')->tag();
			}
		}
		if(!$this->value('settings.js_dynamic')) {
			foreach($this->value('this.resources.after.js_dynamic') as $js_dynamic) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_dynamic)->attr('type', 'text/javascript')->tag();
			}
		}
		if(!$this->value('settings.js_auto_view')) {
			foreach($this->value('this.resources.after.js_auto_view') as $js_auto_view) {
				$this->tag('script')->attr('data-preload', true)->attr('src', $js_auto_view)->attr('type', 'text/javascript')->tag();
			}
		}
		return $this;
	}

	private function loadTheScriptsComponents() {
		if(!$this->value('settings.scripts')) {
			foreach($this->value('this.resources.after.scripts') as $ajavascript) {
				$this->tag('script')->data('preload', true)->text($ajavascript)->tag();
			}
			foreach($this->value('this.resources.before.scripts') as $bjavascript) {
				$this->tag('script')->data('preload', true)->text($bjavascript)->tag();
			}
		}
		return $this;
	}

    private function make_file_and_folder_force($file, $contents = '') {
    	if($this->file_not_exists($file)) {
    		if($this->is_not_dir($this->file_dir($file))) {
    			if($this->make_dir($this->file_dir($file))) {
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

    private function make_dir_force($file, $mode = 0755, $recursive = true) {
        return @mkdir($file, $mode, $recursive);
	}

    private function make_file($file, $contents = '', $lock = false) {
    	if($this->file_not_exists($file)) {
    		if($this->write_file($file, $contents, $lock)) {
    			$this->define('make_file.success', 1);
    		} else {
    			$this->define('make_file.success', 0);
    		}
    	} else {
    		$this->define('make_file.already_exists', 1);
    		$this->define('make_file.file_name', $file);
    	}
	}

	private function storage($str = '') {
		return $this->disk('storage/'.$str);
	}

	private function storage_copy($source, $destination) {
		return $this->copy_dir(
			$this->storage($source), $destination
		);
	}

    private function project($str = '') {
        return $this->disk('views/default/'.$str);
	}

    private function public_path($str = '') {
        return $this->disk('public/'.$str);
	}

    private function assets_path($str = '') {
        return $this->disk('public/assets/'.$str);
	}

    private function views_path($str = '') {
        return $this->disk('views/'.$str);
	}

    private function url($url = '') {
    	if($this->define('request.scheme')) {
	        $url = $this->value('request.scheme').':'.$this->forward_slash().$this->forward_slash().$this->value('url').$this->forward_slash().$this->to_forward_slash($url);
    	} else {
	        $url = $this->forward_slash().$this->to_forward_slash($url);
    	}
		return $url;
	}

    private function assets_url($url = '') {
        return $this->url('assets/'.$this->to_forward_slash($url));
	}

    private function assets_images_url($url = '') {
        return $this->url('assets/img/'.$this->to_forward_slash($url));
	}

    private function delete_path($directory, $preserve = false) {
        if(!$this->is_dir($directory)) {
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

    private function copy_dir($directory, $destination, $options = null) {
        if(!$this->is_dir($directory)) {
            return false;
        }
        $options = $options ?: FilesystemIterator::SKIP_DOTS;
        if (!$this->is_dir($destination)) {
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

    private function copy_file($path, $target) {
        return copy($path, $target);
    }

	private function cache($file, $contents = null) {

    	$file = $this->project('_common/cache/'.$this->to_back_slash($file.'.'.$this->value('doctype')));

    	if($this->isNotNull($contents)) {
    		$dir = $this->file_dir($file);
    		if(!$this->is_dir($dir)) {
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

	private function is_cached($file) {
		return $this->file_exists($this->project('_common/cache/'.$this->to_back_slash($file.'.'.$this->value('doctype'))));
	}

    private function delete_file($files) {

        $files = $this->is_array($files) ? $files : [$files];

        $success = true;
        foreach($files as $path) {
            try {
                if(!@unlink($path)) {
                    $success = false;
                }
            } catch (ErrorException $e) {
                $success = false;
            }
        }

        return $success;
	}

	private function slash_and_dot_to_back_slash($str) {
		return $this->trim($this->string_replace(['/', '\\', '.'], $this->back_slash(), $str), $this->back_slash());
	}

	private function slash_and_dot_to_forward_slash($str) {
		return $this->trim($this->string_replace(['/', '\\', '.'], $this->forward_slash(), $str), $this->forward_slash());
	}

	private function slash_and_dot_to_space($str) {
		return $this->trim($this->string_replace(['/', '\\', '.'], ' ', $str), $this->forward_slash());
	}

	private function slash_and_dot_to_dash($str) {
		return $this->trim($this->string_replace(['/', '\\', '.'], '_', $str), $this->forward_slash());
	}

	private function space_to_dash($str) {
		return $this->string_replace(' ', '-', $str);
	}

	private function dot_to_back_slash($str) {
		return $this->trim($this->string_replace('.', $this->back_slash(), $str), $this->back_slash());
	}

	private function dot_to_forward_slash($str) {
		return $this->trim($this->string_replace('.', $this->forward_slash(), $str), $this->forward_slash());
	}

	private function slash_to_dot($str) {
		return $this->trim($this->string_replace(['/', '\\'], '.', $str), '.');
	}

	private function class_separator_fix(string $class) {
		 return $this->trim($this->string_replace(['/', '.'], '\\', $class), '\\');
	}

	private function back_slash() {
		return DIRECTORY_SEPARATOR;
	}

	private function new_line() {
		return "\n";
	}

	private function all() {
		return $this->memory;
	}

	private function console_log(string $log) {
		$this->echo($log);
	}

	private function define($key, $value = null) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'define'
		]);
	}

    private function defined($key) {
		return $this->find([
			'key' => $key,
			'method' => 'defined'
		]);
	}

    private function undefine($key) {
		return $this->find([
			'key' => $key,
			'method' => 'undefine'
		]);
	}

	private function push($key, $value) {
		return $this->find([
			'key' => $key,
			'value' => $value,
			'method' => 'push'
		]);
	}

	private function value($key, $default = null) {
		return $this->find([
			'key' => $key,
			'default' => $default,
			'method' => 'value'
		]);

	}

	private function normalize($options) {

		if($options['method'] == 'define') {
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
		elseif($options['method'] == 'defined') {
			if($this->key_exists($options['table'], $this->memory)) {
				if($options['count'] > 0) {
					$options['results'] = $this->find($options, $this->memory[$options['table']]);
				} else {
		    		$options['results'] = $this->memory[$options['table']];
				}
			}
			return $options['results'];
		}
		elseif($options['method'] == 'value') {
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

    private function find($options, $something = []) {

    	if(array_key_exists('i', $options)) {

	    	if($options['i'] == $options['count']) {
	    		if($options['method'] == 'defined') {
					return 1;
				}
	    		elseif($options['method'] == 'value') {
	    			if($this->is_null($options['default'])) {
	    				return $something;
	    			} else {
						return $options['default'] == $something;
					}
				}
	    		elseif($options['method'] == 'define') {
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
					//;
				}
	    	} else {
				if($this->key_exists($options['array_captions'][$options['i']], $something)) {
					$options['i'] = $options['i'] + 1;
					return $this->find($options, $something[$options['array_captions'][$options['i'] - 1]]);
				} else {
		    		if($options['method'] == 'defined') {
						return 0;
					}
		    		elseif($options['method'] == 'value') {
						return 0;
					}
		    		elseif($options['method'] == 'define') {
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
						//
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
		    	$this->echo('FATAL ERROR: WRONG COLLECTION KEY SKELETON');
		    	$this->exit();
	    	}

	    	$options['caption_key'] = $this->lower($options['key']);
	    	$options['array_captions'] = $this->explode('.', $options['caption_key']);
			$options['table'] = $options['array_captions'][0];
			unset($options['array_captions'][0]);
			$options['array_captions'] = $this->array_fix_key_numbers($options['array_captions']);
			$options['count'] = $this->count($options['array_captions']);
			$options['results'] = '';
			$options['i'] = 0;
	    	return $this->normalize($options);

		}
	}

	private function in_array($key, $element) {
		return in_array($key, $element);
	}

	private function acceptable($value) {
		return $this->in_array($value, ['yes', 'on', '1', 1, true, 'true'], true) ? 1 : 0;
	}

	private function getNonAlphaNumericCharacters() {
		return [
			"!",
			"@",
			"#",
			"&",
			"(",
			")",
			"–",
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

	private function mb_str_split($str) {
		$results = [];
	    foreach(str_split($str) as $char) {
	    	if(!$this->in_array($char, $results)) {
	    		$results[] = $char;
	    	}
	    }
	    return $results;
	}

    private function underscoreToUpercase($name) {
    	$names = $this->trim($name, '_');
    	$names = $this->explode('_', $name);
    	$newName = '';
    	foreach($names as $name) {
    		$newName.=$this->upper_first($name);
    	}
    	return $newName;
    }

    private function upperToUnderscore($string) {
	    return $this->lower(
	    	$this->preg_replace('/(.)([A-Z)/', '$1_$2', $string)
	    );
	}

    private function preg_replace($regex, $expresion, $string) {
	    return preg_replace($regex, $expresion, $string);
	}

	private function is_int($element) {
		return $this->isInteger($element);
	}

	private function header($str) {
		return header($str);
	}

	private function isInteger($element) {
		return is_integer($element);
	}

	private function get_type($element) {
		return gettype($element);
	}

	private function isString($element) {
		return is_string($element);
	}

	private function isNotNull($element) {
		return !$this->is_null($element);
	}

	private function isDouble($element) {
		return is_double($element);
	}

	private function isFloat($element) {
		return is_float($element);
	}

	private function isNumeric($element) {
		return is_numeric($element);
	}

	private function isEmpty($something) {
		return empty($something);
	}

	private function array_fix_key_numbers($array) {
		return array_values($array);
	}

	private function stringlength($str) {
		return strlen($str);
	}

	private function substring($string, $start = 0, $length = null) {
		if($this->is_null($length)) {
			return substr($string, $start);
		} else {
			return substr($string, $start, $length);
		}
	}

	private function file_extention($file) {
		return pathinfo($file, PATHINFO_EXTENSION);
	}

    private function count($str) {
		return count($str);
	}

	private function replace_spaces_with_one($str = '') {
		return preg_replace('/\s+/', '', $str);
	}

	private function decode($data) {
		return json_decode($data);
	}

	private function encode($data) {
		return json_encode($data);
	}

    private function is_dir($str) {
		return is_dir($str);
	}

    private function file_dir($file) {
		return pathinfo($file, PATHINFO_DIRNAME);
	}

    private function file_contents($file, $lock = false) {
		return file_get_contents($file);
	}

    private function file_append($file, $contents) {
		return file_put_contents($file, $contents, FILE_APPEND);
	}

    private function write_file($file, $contents, $lock = false) {
		return file_put_contents($file, $contents, $lock ? LOCK_EX : 0);
	}

    private function trimleft($source, $symbol) {
    	if($this->is_null($symbol)) {
    		return ltrim($source);
    	} else {
			return ltrim($source, $symbol);
    	}
	}

	private function file_not_exists($file) {
		return $this->file_exists($file) == 0;
	}

	private function echo($string) {
		echo($string);
	}

	private function upper($str) {
		return strtoupper($str);
	}

	private function upper_first($str) {
		return ucfirst($str);
	}

	private function exit() {
		exit;
	}

	public function call($method, $arguments) {
		if(method_exists($this, $method)) {
			return call_user_func_array([$this, $method], $arguments);
		} else {
			$this->fail('Method "'.$method.'" not exists.');
		}
	}

	public function unicode_characters() {
		return [
			'U+0000' => '[NUL]',
			'U+0001' => '[SOH]',
			'U+0002' => '[STX]',
			'U+0003' => '[ETX]',
			'U+0004' => '[EOT]',
			'U+0005' => '[ENQ]',
			'U+0006' => '[ACK]',
			'U+0007' => '[BEL]',
			'U+0008' => '[BS]',
			'U+0009' => '[HT]',
			'U+000A' => '[LF]',
			'U+000B' => '[VT]',
			'U+000C' => '[FF]',
			'U+000D' => '[CR]',
			'U+000E' => '[SO]',
			'U+000F' => '[SI]',
			'U+0010' => '[DLE]',
			'U+0011' => '[DC1]',
			'U+0012' => '[DC2]',
			'U+0013' => '[DC3]',
			'U+0014' => '[DC4]',
			'U+0015' => '[NAK]',
			'U+0016' => '[SYN]',
			'U+0017' => '[ETB]',
			'U+0018' => '[CAN]',
			'U+0019' => '[EM]',
			'U+001A' => '[SUB]',
			'U+001B' => '[ESC]',
			'U+001C' => '[FS]',
			'U+001D' => '[GS]',
			'U+001E' => '[RS]',
			'U+001F' => '[US]',
			'U+0020' => '[SP]',
			'U+0021' => '!',
			'U+0022' => '"',
			'U+0023' => '#',
			'U+0024' => '$',
			'U+0025' => '%',
			'U+0026' => '&',
			'U+0027' => "'",
			'U+0028' => '(',
			'U+0029' => ')',
			'U+002A' => '*',
			'U+002B' => '+',
			'U+002C' => ',',
			'U+002D' => '-',
			'U+002E' => '.',
			'U+002F' => '/',
			'U+0030' => '0',
			'U+0031' => '1',
			'U+0032' => '2',
			'U+0033' => '3',
			'U+0034' => '4',
			'U+0035' => '5',
			'U+0036' => '6',
			'U+0037' => '7',
			'U+0038' => '8',
			'U+0039' => '9',
			'U+003A' => ':',
			'U+003B' => ';',
			'U+003C' => '<',
			'U+003D' => '=',
			'U+003E' => '>',
			'U+003F' => '?',
			'U+0040' => '@',
			'U+0041' => 'A',
			'U+0042' => 'B',
			'U+0043' => 'C',
			'U+0044' => 'D',
			'U+0045' => 'E',
			'U+0046' => 'F',
			'U+0047' => 'G',
			'U+0048' => 'H',
			'U+0049' => 'I',
			'U+004A' => 'J',
			'U+004B' => 'K',
			'U+004C' => 'L',
			'U+004D' => 'M',
			'U+004E' => 'N',
			'U+004F' => 'O',
			'U+0050' => 'P',
			'U+0051' => 'Q',
			'U+0052' => 'R',
			'U+0053' => 'S',
			'U+0054' => 'T',
			'U+0055' => 'U',
			'U+0056' => 'V',
			'U+0057' => 'W',
			'U+0058' => 'X',
			'U+0059' => 'Y',
			'U+005A' => 'Z',
			'U+005B' => '[',
			'U+005C' => '\\',
			'U+005D' => ']',
			'U+005E' => '^',
			'U+005F' => '_',
			'U+0060' => '`',
			'U+0061' => 'a',
			'U+0062' => 'b',
			'U+0063' => 'c',
			'U+0064' => 'd',
			'U+0065' => 'e',
			'U+0066' => 'f',
			'U+0067' => 'g',
			'U+0068' => 'h',
			'U+0069' => 'i',
			'U+006A' => 'j',
			'U+006B' => 'k',
			'U+006C' => 'l',
			'U+006D' => 'm',
			'U+006E' => 'n',
			'U+006F' => 'o',
			'U+0070' => 'p',
			'U+0071' => 'q',
			'U+0072' => 'r',
			'U+0073' => 's',
			'U+0074' => 't',
			'U+0075' => 'u',
			'U+0076' => 'v',
			'U+0077' => 'w',
			'U+0078' => 'x',
			'U+0079' => 'y',
			'U+007A' => 'z',
			'U+007B' => '{',
			'U+007C' => '|',
			'U+007D' => '}',
			'U+007E' => '~',
			'U+007F' => '[DEL]',
			'U+0080' => '[XXX]',
			'U+0081' => '[XXX]',
			'U+0082' => '[BPH]',
			'U+0083' => '[NBH]',
			'U+0084' => '[IND]',
			'U+0085' => '[NEL]',
			'U+0086' => '[SSA]',
			'U+0087' => '[ESA]',
			'U+0088' => '[HTS]',
			'U+0089' => '[HTJ]',
			'U+008A' => '[VTS]',
			'U+008B' => '[PLD]',
			'U+008C' => '[PLU]',
			'U+008D' => '[RI]',
			'U+008E' => '[SS2]',
			'U+008F' => '[SS3]',
			'U+0090' => '[DCS]',
			'U+0091' => '[PU1]',
			'U+0092' => '[PU2]',
			'U+0093' => '[STS]',
			'U+0094' => '[CCH]',
			'U+0095' => '[MW]',
			'U+0096' => '[SPA]',
			'U+0097' => '[EPA]',
			'U+0098' => '[SOS]',
			'U+0099' => '[XXX]',
			'U+009A' => '[SCI]',
			'U+009B' => '[CSI]',
			'U+009C' => '[ST]',
			'U+009D' => '[OSC]',
			'U+009E' => '[PM]',
			'U+009F' => '[APC]',
			'U+00A0' => '[NB SP]',
			'U+00A1' => '¡',
			'U+00A2' => '¢',
			'U+00A3' => '£',
			'U+00A4' => '¤',
			'U+00A5' => '¥',
			'U+00A6' => '¦',
			'U+00A7' => '§',
			'U+00A8' => '¨',
			'U+00A9' => '©',
			'U+00AA' => 'ª',
			'U+00AB' => '«',
			'U+00AC' => '¬',
			'U+00AD' => '[SHY -]',
			'U+00AE' => '®',
			'U+00AF' => '¯',
			'U+00B0' => '°',
			'U+00B1' => '±',
			'U+00B2' => '²',
			'U+00B3' => '³',
			'U+00B4' => '´',
			'U+00B5' => 'µ',
			'U+00B6' => '¶',
			'U+00B7' => '·',
			'U+00B8' => '¸',
			'U+00B9' => '¹',
			'U+00BA' => 'º',
			'U+00BB' => '»',
			'U+00BC' => '¼',
			'U+00BD' => '½',
			'U+00BE' => '¾',
			'U+00BF' => '¿',
			'U+00C0' => 'À',
			'U+00C1' => 'Á',
			'U+00C2' => 'Â',
			'U+00C3' => 'Ã',
			'U+00C4' => 'Ä',
			'U+00C5' => 'Å',
			'U+00C6' => 'Æ',
			'U+00C7' => 'Ç',
			'U+00C8' => 'È',
			'U+00C9' => 'É',
			'U+00CA' => 'Ê',
			'U+00CB' => 'Ë',
			'U+00CC' => 'Ì',
			'U+00CD' => 'Í',
			'U+00CE' => 'Î',
			'U+00CF' => 'Ï',
			'U+00D0' => 'Ð',
			'U+00D1' => 'Ñ',
			'U+00D2' => 'Ò',
			'U+00D3' => 'Ó',
			'U+00D4' => 'Ô',
			'U+00D5' => 'Õ',
			'U+00D6' => 'Ö',
			'U+00D7' => '×',
			'U+00D8' => 'Ø',
			'U+00D9' => 'Ù',
			'U+00DA' => 'Ú',
			'U+00DB' => 'Û',
			'U+00DC' => 'Ü',
			'U+00DD' => 'Ý',
			'U+00DE' => 'Þ',
			'U+00DF' => 'ß',
			'U+00E0' => 'à',
			'U+00E1' => 'á',
			'U+00E2' => 'â',
			'U+00E3' => 'ã',
			'U+00E4' => 'ä',
			'U+00E5' => 'å',
			'U+00E6' => 'æ',
			'U+00E7' => 'ç',
			'U+00E8' => 'è',
			'U+00E9' => 'é',
			'U+00EA' => 'ê',
			'U+00EB' => 'ë',
			'U+00EC' => 'ì',
			'U+00ED' => 'í',
			'U+00EE' => 'î',
			'U+00EF' => 'ï',
			'U+00F0' => 'ð',
			'U+00F1' => 'ñ',
			'U+00F2' => 'ò',
			'U+00F3' => 'ó',
			'U+00F4' => 'ô',
			'U+00F5' => 'õ',
			'U+00F6' => 'ö',
			'U+00F7' => '÷',
			'U+00F8' => 'ø',
			'U+00F9' => 'ù',
			'U+00FA' => 'ú',
			'U+00FB' => 'û',
			'U+00FC' => 'ü',
			'U+00FD' => 'ý',
			'U+00FE' => 'þ',
			'U+00FF' => 'ÿ',
			'U+0100' => 'Ā',
			'U+0101' => 'ā',
			'U+0102' => 'Ă',
			'U+0103' => 'ă',
			'U+0104' => 'Ą',
			'U+0105' => 'ą',
			'U+0106' => 'Ć',
			'U+0107' => 'ć',
			'U+0108' => 'Ĉ',
			'U+0109' => 'ĉ',
			'U+010A' => 'Ċ',
			'U+010B' => 'ċ',
			'U+010C' => 'Č',
			'U+010D' => 'č',
			'U+010E' => 'Ď',
			'U+010F' => 'ď',
			'U+0110' => 'Đ',
			'U+0111' => 'đ',
			'U+0112' => 'Ē',
			'U+0113' => 'ē',
			'U+0114' => 'Ĕ',
			'U+0115' => 'ĕ',
			'U+0116' => 'Ė',
			'U+0117' => 'ė',
			'U+0118' => 'Ę',
			'U+0119' => 'ę',
			'U+011A' => 'Ě',
			'U+011B' => 'ě',
			'U+011C' => 'Ĝ',
			'U+011D' => 'ĝ',
			'U+011E' => 'Ğ',
			'U+011F' => 'ğ',
			'U+0120' => 'Ġ',
			'U+0121' => 'ġ',
			'U+0122' => 'Ģ',
			'U+0123' => 'ģ',
			'U+0124' => 'Ĥ',
			'U+0125' => 'ĥ',
			'U+0126' => 'Ħ',
			'U+0127' => 'ħ',
			'U+0128' => 'Ĩ',
			'U+0129' => 'ĩ',
			'U+012A' => 'Ī',
			'U+012B' => 'ī',
			'U+012C' => 'Ĭ',
			'U+012D' => 'ĭ',
			'U+012E' => 'Į',
			'U+012F' => 'į',
			'U+0130' => 'İ',
			'U+0131' => 'ı',
			'U+0132' => 'Ĳ',
			'U+0133' => 'ĳ',
			'U+0134' => 'Ĵ',
			'U+0135' => 'ĵ',
			'U+0136' => 'Ķ',
			'U+0137' => 'ķ',
			'U+0138' => 'ĸ',
			'U+0139' => 'Ĺ',
			'U+013A' => 'ĺ',
			'U+013B' => 'Ļ',
			'U+013C' => 'ļ',
			'U+013D' => 'Ľ',
			'U+013E' => 'ľ',
			'U+013F' => 'Ŀ',
			'U+0140' => 'ŀ',
			'U+0141' => 'Ł',
			'U+0142' => 'ł',
			'U+0143' => 'Ń',
			'U+0144' => 'ń',
			'U+0145' => 'Ņ',
			'U+0146' => 'ņ',
			'U+0147' => 'Ň',
			'U+0148' => 'ň',
			'U+0149' => 'ŉ',
			'U+014A' => 'Ŋ',
			'U+014B' => 'ŋ',
			'U+014C' => 'Ō',
			'U+014D' => 'ō',
			'U+014E' => 'Ŏ',
			'U+014F' => 'ŏ',
			'U+0150' => 'Ő',
			'U+0151' => 'ő',
			'U+0152' => 'Œ',
			'U+0153' => 'œ',
			'U+0154' => 'Ŕ',
			'U+0155' => 'ŕ',
			'U+0156' => 'Ŗ',
			'U+0157' => 'ŗ',
			'U+0158' => 'Ř',
			'U+0159' => 'ř',
			'U+015A' => 'Ś',
			'U+015B' => 'ś',
			'U+015C' => 'Ŝ',
			'U+015D' => 'ŝ',
			'U+015E' => 'Ş',
			'U+015F' => 'ş',
			'U+0160' => 'Š',
			'U+0161' => 'š',
			'U+0162' => 'Ţ',
			'U+0163' => 'ţ',
			'U+0164' => 'Ť',
			'U+0165' => 'ť',
			'U+0166' => 'Ŧ',
			'U+0167' => 'ŧ',
			'U+0168' => 'Ũ',
			'U+0169' => 'ũ',
			'U+016A' => 'Ū',
			'U+016B' => 'ū',
			'U+016C' => 'Ŭ',
			'U+016D' => 'ŭ',
			'U+016E' => 'Ů',
			'U+016F' => 'ů',
			'U+0170' => 'Ű',
			'U+0171' => 'ű',
			'U+0172' => 'Ų',
			'U+0173' => 'ų',
			'U+0174' => 'Ŵ',
			'U+0175' => 'ŵ',
			'U+0176' => 'Ŷ',
			'U+0177' => 'ŷ',
			'U+0178' => 'Ÿ',
			'U+0179' => 'Ź',
			'U+017A' => 'ź',
			'U+017B' => 'Ż',
			'U+017C' => 'ż',
			'U+017D' => 'Ž',
			'U+017E' => 'ž',
			'U+017F' => 'ſ',
			'U+0180' => 'ƀ',
			'U+0181' => 'Ɓ',
			'U+0182' => 'Ƃ',
			'U+0183' => 'ƃ',
			'U+0184' => 'Ƅ',
			'U+0185' => 'ƅ',
			'U+0186' => 'Ɔ',
			'U+0187' => 'Ƈ',
			'U+0188' => 'ƈ',
			'U+0189' => 'Ɖ',
			'U+018A' => 'Ɗ',
			'U+018B' => 'Ƌ',
			'U+018C' => 'ƌ',
			'U+018D' => 'ƍ',
			'U+018E' => 'Ǝ',
			'U+018F' => 'Ə',
			'U+0190' => 'Ɛ',
			'U+0191' => 'Ƒ',
			'U+0192' => 'ƒ',
			'U+0193' => 'Ɠ',
			'U+0194' => 'Ɣ',
			'U+0195' => 'ƕ',
			'U+0196' => 'Ɩ',
			'U+0197' => 'Ɨ',
			'U+0198' => 'Ƙ',
			'U+0199' => 'ƙ',
			'U+019A' => 'ƚ',
			'U+019B' => 'ƛ',
			'U+019C' => 'Ɯ',
			'U+019D' => 'Ɲ',
			'U+019E' => 'ƞ',
			'U+019F' => 'Ɵ',
			'U+01A0' => 'Ơ',
			'U+01A1' => 'ơ',
			'U+01A2' => 'Ƣ',
			'U+01A3' => 'ƣ',
			'U+01A4' => 'Ƥ',
			'U+01A5' => 'ƥ',
			'U+01A6' => 'Ʀ',
			'U+01A7' => 'Ƨ',
			'U+01A8' => 'ƨ',
			'U+01A9' => 'Ʃ',
			'U+01AA' => 'ƪ',
			'U+01AB' => 'ƫ',
			'U+01AC' => 'Ƭ',
			'U+01AD' => 'ƭ',
			'U+01AE' => 'Ʈ',
			'U+01AF' => 'Ư',
			'U+01B0' => 'ư',
			'U+01B1' => 'Ʊ',
			'U+01B2' => 'Ʋ',
			'U+01B3' => 'Ƴ',
			'U+01B4' => 'ƴ',
			'U+01B5' => 'Ƶ',
			'U+01B6' => 'ƶ',
			'U+01B7' => 'Ʒ',
			'U+01B8' => 'Ƹ',
			'U+01B9' => 'ƹ',
			'U+01BA' => 'ƺ',
			'U+01BB' => 'ƻ',
			'U+01BC' => 'Ƽ',
			'U+01BD' => 'ƽ',
			'U+01BE' => 'ƾ',
			'U+01BF' => 'ƿ',
			'U+01C0' => 'ǀ',
			'U+01C1' => 'ǁ',
			'U+01C2' => 'ǂ',
			'U+01C3' => 'ǃ',
			'U+01C4' => 'Ǆ',
			'U+01C5' => 'ǅ',
			'U+01C6' => 'ǆ',
			'U+01C7' => 'Ǉ',
			'U+01C8' => 'ǈ',
			'U+01C9' => 'ǉ',
			'U+01CA' => 'Ǌ',
			'U+01CB' => 'ǋ',
			'U+01CC' => 'ǌ',
			'U+01CD' => 'Ǎ',
			'U+01CE' => 'ǎ',
			'U+01CF' => 'Ǐ',
			'U+01D0' => 'ǐ',
			'U+01D1' => 'Ǒ',
			'U+01D2' => 'ǒ',
			'U+01D3' => 'Ǔ',
			'U+01D4' => 'ǔ',
			'U+01D5' => 'Ǖ',
			'U+01D6' => 'ǖ',
			'U+01D7' => 'Ǘ',
			'U+01D8' => 'ǘ',
			'U+01D9' => 'Ǚ',
			'U+01DA' => 'ǚ',
			'U+01DB' => 'Ǜ',
			'U+01DC' => 'ǜ',
			'U+01DD' => 'ǝ',
			'U+01DE' => 'Ǟ',
			'U+01DF' => 'ǟ',
			'U+01E0' => 'Ǡ',
			'U+01E1' => 'ǡ',
			'U+01E2' => 'Ǣ',
			'U+01E3' => 'ǣ',
			'U+01E4' => 'Ǥ',
			'U+01E5' => 'ǥ',
			'U+01E6' => 'Ǧ',
			'U+01E7' => 'ǧ',
			'U+01E8' => 'Ǩ',
			'U+01E9' => 'ǩ',
			'U+01EA' => 'Ǫ',
			'U+01EB' => 'ǫ',
			'U+01EC' => 'Ǭ',
			'U+01ED' => 'ǭ',
			'U+01EE' => 'Ǯ',
			'U+01EF' => 'ǯ',
			'U+01F0' => 'ǰ',
			'U+01F1' => 'Ǳ',
			'U+01F2' => 'ǲ',
			'U+01F3' => 'ǳ',
			'U+01F4' => 'Ǵ',
			'U+01F5' => 'ǵ',
			'U+01F6' => 'Ƕ',
			'U+01F7' => 'Ƿ',
			'U+01F8' => 'Ǹ',
			'U+01F9' => 'ǹ',
			'U+01FA' => 'Ǻ',
			'U+01FB' => 'ǻ',
			'U+01FC' => 'Ǽ',
			'U+01FD' => 'ǽ',
			'U+01FE' => 'Ǿ',
			'U+01FF' => 'ǿ',
			'U+0200' => 'Ȁ',
			'U+0201' => 'ȁ',
			'U+0202' => 'Ȃ',
			'U+0203' => 'ȃ',
			'U+0204' => 'Ȅ',
			'U+0205' => 'ȅ',
			'U+0206' => 'Ȇ',
			'U+0207' => 'ȇ',
			'U+0208' => 'Ȉ',
			'U+0209' => 'ȉ',
			'U+020A' => 'Ȋ',
			'U+020B' => 'ȋ',
			'U+020C' => 'Ȍ',
			'U+020D' => 'ȍ',
			'U+020E' => 'Ȏ',
			'U+020F' => 'ȏ',
			'U+0210' => 'Ȑ',
			'U+0211' => 'ȑ',
			'U+0212' => 'Ȓ',
			'U+0213' => 'ȓ',
			'U+0214' => 'Ȕ',
			'U+0215' => 'ȕ',
			'U+0216' => 'Ȗ',
			'U+0217' => 'ȗ',
			'U+0218' => 'Ș',
			'U+0219' => 'ș',
			'U+021A' => 'Ț',
			'U+021B' => 'ț',
			'U+021C' => 'Ȝ',
			'U+021D' => 'ȝ',
			'U+021E' => 'Ȟ',
			'U+021F' => 'ȟ',
			'U+0220' => 'Ƞ',
			'U+0221' => 'ȡ',
			'U+0222' => 'Ȣ',
			'U+0223' => 'ȣ',
			'U+0224' => 'Ȥ',
			'U+0225' => 'ȥ',
			'U+0226' => 'Ȧ',
			'U+0227' => 'ȧ',
			'U+0228' => 'Ȩ',
			'U+0229' => 'ȩ',
			'U+022A' => 'Ȫ',
			'U+022B' => 'ȫ',
			'U+022C' => 'Ȭ',
			'U+022D' => 'ȭ',
			'U+022E' => 'Ȯ',
			'U+022F' => 'ȯ',
			'U+0230' => 'Ȱ',
			'U+0231' => 'ȱ',
			'U+0232' => 'Ȳ',
			'U+0233' => 'ȳ',
			'U+0234' => 'ȴ',
			'U+0235' => 'ȵ',
			'U+0236' => 'ȶ',
			'U+0237' => 'ȷ',
			'U+0238' => 'ȸ',
			'U+0239' => 'ȹ',
			'U+023A' => 'Ⱥ',
			'U+023B' => 'Ȼ',
			'U+023C' => 'ȼ',
			'U+023D' => 'Ƚ',
			'U+023E' => 'Ⱦ',
			'U+023F' => 'ȿ',
			'U+0240' => 'ɀ',
			'U+0241' => 'Ɂ',
			'U+0242' => 'ɂ',
			'U+0243' => 'Ƀ',
			'U+0244' => 'Ʉ',
			'U+0245' => 'Ʌ',
			'U+0246' => 'Ɇ',
			'U+0247' => 'ɇ',
			'U+0248' => 'Ɉ',
			'U+0249' => 'ɉ',
			'U+024A' => 'Ɋ',
			'U+024B' => 'ɋ',
			'U+024C' => 'Ɍ',
			'U+024D' => 'ɍ',
			'U+024E' => 'Ɏ',
			'U+024F' => 'ɏ',
			'U+0250' => 'ɐ',
			'U+0251' => 'ɑ',
			'U+0252' => 'ɒ',
			'U+0253' => 'ɓ',
			'U+0254' => 'ɔ',
			'U+0255' => 'ɕ',
			'U+0256' => 'ɖ',
			'U+0257' => 'ɗ',
			'U+0258' => 'ɘ',
			'U+0259' => 'ə',
			'U+025A' => 'ɚ',
			'U+025B' => 'ɛ',
			'U+025C' => 'ɜ',
			'U+025D' => 'ɝ',
			'U+025E' => 'ɞ',
			'U+025F' => 'ɟ',
			'U+0260' => 'ɠ',
			'U+0261' => 'ɡ',
			'U+0262' => 'ɢ',
			'U+0263' => 'ɣ',
			'U+0264' => 'ɤ',
			'U+0265' => 'ɥ',
			'U+0266' => 'ɦ',
			'U+0267' => 'ɧ',
			'U+0268' => 'ɨ',
			'U+0269' => 'ɩ',
			'U+026A' => 'ɪ',
			'U+026B' => 'ɫ',
			'U+026C' => 'ɬ',
			'U+026D' => 'ɭ',
			'U+026E' => 'ɮ',
			'U+026F' => 'ɯ',
			'U+0270' => 'ɰ',
			'U+0271' => 'ɱ',
			'U+0272' => 'ɲ',
			'U+0273' => 'ɳ',
			'U+0274' => 'ɴ',
			'U+0275' => 'ɵ',
			'U+0276' => 'ɶ',
			'U+0277' => 'ɷ',
			'U+0278' => 'ɸ',
			'U+0279' => 'ɹ',
			'U+027A' => 'ɺ',
			'U+027B' => 'ɻ',
			'U+027C' => 'ɼ',
			'U+027D' => 'ɽ',
			'U+027E' => 'ɾ',
			'U+027F' => 'ɿ',
			'U+0280' => 'ʀ',
			'U+0281' => 'ʁ',
			'U+0282' => 'ʂ',
			'U+0283' => 'ʃ',
			'U+0284' => 'ʄ',
			'U+0285' => 'ʅ',
			'U+0286' => 'ʆ',
			'U+0287' => 'ʇ',
			'U+0288' => 'ʈ',
			'U+0289' => 'ʉ',
			'U+028A' => 'ʊ',
			'U+028B' => 'ʋ',
			'U+028C' => 'ʌ',
			'U+028D' => 'ʍ',
			'U+028E' => 'ʎ',
			'U+028F' => 'ʏ',
			'U+0290' => 'ʐ',
			'U+0291' => 'ʑ',
			'U+0292' => 'ʒ',
			'U+0293' => 'ʓ',
			'U+0294' => 'ʔ',
			'U+0295' => 'ʕ',
			'U+0296' => 'ʖ',
			'U+0297' => 'ʗ',
			'U+0298' => 'ʘ',
			'U+0299' => 'ʙ',
			'U+029A' => 'ʚ',
			'U+029B' => 'ʛ',
			'U+029C' => 'ʜ',
			'U+029D' => 'ʝ',
			'U+029E' => 'ʞ',
			'U+029F' => 'ʟ',
			'U+02A0' => 'ʠ',
			'U+02A1' => 'ʡ',
			'U+02A2' => 'ʢ',
			'U+02A3' => 'ʣ',
			'U+02A4' => 'ʤ',
			'U+02A5' => 'ʥ',
			'U+02A6' => 'ʦ',
			'U+02A7' => 'ʧ',
			'U+02A8' => 'ʨ',
			'U+02A9' => 'ʩ',
			'U+02AA' => 'ʪ',
			'U+02AB' => 'ʫ',
			'U+02AC' => 'ʬ',
			'U+02AD' => 'ʭ',
			'U+02AE' => 'ʮ',
			'U+02AF' => 'ʯ',
			'U+02B0' => 'ʰ',
			'U+02B1' => 'ʱ',
			'U+02B2' => 'ʲ',
			'U+02B3' => 'ʳ',
			'U+02B4' => 'ʴ',
			'U+02B5' => 'ʵ',
			'U+02B6' => 'ʶ',
			'U+02B7' => 'ʷ',
			'U+02B8' => 'ʸ',
			'U+02B9' => 'ʹ',
			'U+02BA' => 'ʺ',
			'U+02BB' => 'ʻ',
			'U+02BC' => 'ʼ',
			'U+02BD' => 'ʽ',
			'U+02BE' => 'ʾ',
			'U+02BF' => 'ʿ',
			'U+02C0' => 'ˀ',
			'U+02C1' => 'ˁ',
			'U+02C2' => '˂',
			'U+02C3' => '˃',
			'U+02C4' => '˄',
			'U+02C5' => '˅',
			'U+02C6' => 'ˆ',
			'U+02C7' => 'ˇ',
			'U+02C8' => 'ˈ',
			'U+02C9' => 'ˉ',
			'U+02CA' => 'ˊ',
			'U+02CB' => 'ˋ',
			'U+02CC' => 'ˌ',
			'U+02CD' => 'ˍ',
			'U+02CE' => 'ˎ',
			'U+02CF' => 'ˏ',
			'U+02D0' => 'ː',
			'U+02D1' => 'ˑ',
			'U+02D2' => '˒',
			'U+02D3' => '˓',
			'U+02D4' => '˔',
			'U+02D5' => '˕',
			'U+02D6' => '˖',
			'U+02D7' => '˗',
			'U+02D8' => '˘',
			'U+02D9' => '˙',
			'U+02DA' => '˚',
			'U+02DB' => '˛',
			'U+02DC' => '˜',
			'U+02DD' => '˝',
			'U+02DE' => ' ˞',
			'U+02DF' => '˟',
			'U+02E0' => 'ˠ',
			'U+02E1' => 'ˡ',
			'U+02E2' => 'ˢ',
			'U+02E3' => 'ˣ',
			'U+02E4' => 'ˤ',
			'U+02E5' => '˥',
			'U+02E6' => '˦',
			'U+02E7' => '˧',
			'U+02E8' => '˨',
			'U+02E9' => '˩',
			'U+02EA' => '˪',
			'U+02EB' => '˫',
			'U+02EC' => 'ˬ',
			'U+02ED' => '˭',
			'U+02EE' => 'ˮ',
			'U+02EF' => '˯',
			'U+02F0' => '˰',
			'U+02F1' => '˱',
			'U+02F2' => '˲',
			'U+02F3' => '˳',
			'U+02F4' => '˴',
			'U+02F5' => '˵',
			'U+02F6' => '˶',
			'U+02F7' => '˷',
			'U+02F8' => '˸',
			'U+02F9' => '˹',
			'U+02FA' => '˺',
			'U+02FB' => '˻',
			'U+02FC' => '˼',
			'U+02FD' => '˽',
			'U+02FE' => '˾',
			'U+02FF' => '˿',
			'U+0300' => ' ̀',
			'U+0301' => ' ́',
			'U+0302' => ' ̂',
			'U+0303' => ' ̃',
			'U+0304' => ' ̄',
			'U+0305' => ' ̅',
			'U+0306' => ' ̆',
			'U+0307' => ' ̇',
			'U+0308' => ' ̈',
			'U+0309' => ' ̉',
			'U+030A' => ' ̊',
			'U+030B' => ' ̋',
			'U+030C' => ' ̌',
			'U+030D' => ' ̍',
			'U+030E' => ' ̎',
			'U+030F' => ' ̏',
			'U+0310' => ' ̐',
			'U+0311' => ' ̑',
			'U+0312' => ' ̒',
			'U+0313' => ' ̓',
			'U+0314' => ' ̔',
			'U+0315' => ' ̕',
			'U+0316' => ' ̖',
			'U+0317' => ' ̗',
			'U+0318' => ' ̘',
			'U+0319' => ' ̙',
			'U+031A' => ' ̚',
			'U+031B' => ' ̛',
			'U+031C' => ' ̜',
			'U+031D' => ' ̝',
			'U+031E' => ' ̞',
			'U+031F' => ' ̟',
			'U+0320' => ' ̠',
			'U+0321' => ' ̡',
			'U+0322' => ' ̢',
			'U+0323' => ' ̣',
			'U+0324' => ' ̤',
			'U+0325' => ' ̥',
			'U+0326' => ' ̦',
			'U+0327' => ' ̧',
			'U+0328' => ' ̨',
			'U+0329' => ' ̩',
			'U+032A' => ' ̪',
			'U+032B' => ' ̫',
			'U+032C' => ' ̬',
			'U+032D' => ' ̭',
			'U+032E' => ' ̮',
			'U+032F' => ' ̯',
			'U+0330' => ' ̰',
			'U+0331' => ' ̱',
			'U+0332' => ' ̲',
			'U+0333' => ' ̳',
			'U+0334' => ' ̴',
			'U+0335' => ' ̵',
			'U+0336' => ' ̶',
			'U+0337' => ' ̷',
			'U+0338' => ' ̸',
			'U+0339' => ' ̹',
			'U+033A' => ' ̺',
			'U+033B' => ' ̻',
			'U+033C' => ' ̼',
			'U+033D' => ' ̽',
			'U+033E' => ' ̾',
			'U+033F' => ' ̿',
			'U+0340' => ' ̀',
			'U+0341' => ' ́',
			'U+0342' => ' ͂',
			'U+0343' => ' ̓',
			'U+0344' => ' ̈́',
			'U+0345' => ' ͅ',
			'U+0346' => ' ͆',
			'U+0347' => ' ͇',
			'U+0348' => ' ͈',
			'U+0349' => ' ͉',
			'U+034A' => ' ͊',
			'U+034B' => ' ͋',
			'U+034C' => ' ͌',
			'U+034D' => ' ͍',
			'U+034E' => ' ͎',
			'U+034F' => '[CGJ]',
			'U+0350' => ' ͐',
			'U+0351' => ' ͑',
			'U+0352' => ' ͒',
			'U+0353' => ' ͓',
			'U+0354' => ' ͔',
			'U+0355' => ' ͕',
			'U+0356' => ' ͖',
			'U+0357' => ' ͗',
			'U+0358' => ' ͘',
			'U+0359' => ' ͙',
			'U+035A' => ' ͚',
			'U+035B' => ' ͛',
			'U+035C' => ' ͜',
			'U+035D' => ' ͝',
			'U+035E' => ' ͞',
			'U+035F' => ' ͟',
			'U+0360' => ' ͠',
			'U+0361' => ' ͡',
			'U+0362' => ' ͢',
			'U+0363' => ' ͣ',
			'U+0364' => ' ͤ',
			'U+0365' => ' ͥ',
			'U+0366' => ' ͦ',
			'U+0367' => ' ͧ',
			'U+0368' => ' ͨ',
			'U+0369' => ' ͩ',
			'U+036A' => ' ͪ',
			'U+036B' => ' ͫ',
			'U+036C' => ' ͬ',
			'U+036D' => ' ͭ',
			'U+036E' => ' ͮ',
			'U+036F' => ' ͯ',
			'U+0370' => 'Ͱ',
			'U+0371' => 'ͱ',
			'U+0372' => 'Ͳ',
			'U+0373' => 'ͳ',
			'U+0374' => 'ʹ',
			'U+0375' => '͵',
			'U+0376' => 'Ͷ',
			'U+0377' => 'ͷ',
			'U+0378' => ' ',
			'U+0379' => ' ',
			'U+037A' => 'ͺ',
			'U+037B' => 'ͻ',
			'U+037C' => 'ͼ',
			'U+037D' => 'ͽ',
			'U+037E' => ';',
			'U+037F' => 'Ϳ',
			'U+0380' => ' ',
			'U+0381' => ' ',
			'U+0382' => ' ',
			'U+0383' => ' ',
			'U+0384' => '΄',
			'U+0385' => '΅',
			'U+0386' => 'Ά',
			'U+0387' => '·',
			'U+0388' => 'Έ',
			'U+0389' => 'Ή',
			'U+038A' => 'Ί',
			'U+038B' => ' ',
			'U+038C' => 'Ό',
			'U+038D' => ' ',
			'U+038E' => 'Ύ',
			'U+038F' => 'Ώ',
			'U+0390' => 'ΐ',
			'U+0391' => 'Α',
			'U+0392' => 'Β',
			'U+0393' => 'Γ',
			'U+0394' => 'Δ',
			'U+0395' => 'Ε',
			'U+0396' => 'Ζ',
			'U+0397' => 'Η',
			'U+0398' => 'Θ',
			'U+0399' => 'Ι',
			'U+039A' => 'Κ',
			'U+039B' => 'Λ',
			'U+039C' => 'Μ',
			'U+039D' => 'Ν',
			'U+039E' => 'Ξ',
			'U+039F' => 'Ο',
			'U+03A0' => 'Π',
			'U+03A1' => 'Ρ',
			'U+03A2' => ' ',
			'U+03A3' => 'Σ',
			'U+03A4' => 'Τ',
			'U+03A5' => 'Υ',
			'U+03A6' => 'Φ',
			'U+03A7' => 'Χ',
			'U+03A8' => 'Ψ',
			'U+03A9' => 'Ω',
			'U+03AA' => 'Ϊ',
			'U+03AB' => 'Ϋ',
			'U+03AC' => 'ά',
			'U+03AD' => 'έ',
			'U+03AE' => 'ή',
			'U+03AF' => 'ί',
			'U+03B0' => 'ΰ',
			'U+03B1' => 'α',
			'U+03B2' => 'β',
			'U+03B3' => 'γ',
			'U+03B4' => 'δ',
			'U+03B5' => 'ε',
			'U+03B6' => 'ζ',
			'U+03B7' => 'η',
			'U+03B8' => 'θ',
			'U+03B9' => 'ι',
			'U+03BA' => 'κ',
			'U+03BB' => 'λ',
			'U+03BC' => 'μ',
			'U+03BD' => 'ν',
			'U+03BE' => 'ξ',
			'U+03BF' => 'ο',
			'U+03C0' => 'π',
			'U+03C1' => 'ρ',
			'U+03C2' => 'ς',
			'U+03C3' => 'σ',
			'U+03C4' => 'τ',
			'U+03C5' => 'υ',
			'U+03C6' => 'φ',
			'U+03C7' => 'χ',
			'U+03C8' => 'ψ',
			'U+03C9' => 'ω',
			'U+03CA' => 'ϊ',
			'U+03CB' => 'ϋ',
			'U+03CC' => 'ό',
			'U+03CD' => 'ύ',
			'U+03CE' => 'ώ',
			'U+03CF' => 'Ϗ',
			'U+03D0' => 'ϐ',
			'U+03D1' => 'ϑ',
			'U+03D2' => 'ϒ',
			'U+03D3' => 'ϓ',
			'U+03D4' => 'ϔ',
			'U+03D5' => 'ϕ',
			'U+03D6' => 'ϖ',
			'U+03D7' => 'ϗ',
			'U+03D8' => 'Ϙ',
			'U+03D9' => 'ϙ',
			'U+03DA' => 'Ϛ',
			'U+03DB' => 'ϛ',
			'U+03DC' => 'Ϝ',
			'U+03DD' => 'ϝ',
			'U+03DE' => 'Ϟ',
			'U+03DF' => 'ϟ',
			'U+03E0' => 'Ϡ',
			'U+03E1' => 'ϡ',
			'U+03E2' => 'Ϣ',
			'U+03E3' => 'ϣ',
			'U+03E4' => 'Ϥ',
			'U+03E5' => 'ϥ',
			'U+03E6' => 'Ϧ',
			'U+03E7' => 'ϧ',
			'U+03E8' => 'Ϩ',
			'U+03E9' => 'ϩ',
			'U+03EA' => 'Ϫ',
			'U+03EB' => 'ϫ',
			'U+03EC' => 'Ϭ',
			'U+03ED' => 'ϭ',
			'U+03EE' => 'Ϯ',
			'U+03EF' => 'ϯ',
			'U+03F0' => 'ϰ',
			'U+03F1' => 'ϱ',
			'U+03F2' => 'ϲ',
			'U+03F3' => 'ϳ',
			'U+03F4' => 'ϴ',
			'U+03F5' => 'ϵ',
			'U+03F6' => '϶',
			'U+03F7' => 'Ϸ',
			'U+03F8' => 'ϸ',
			'U+03F9' => 'Ϲ',
			'U+03FA' => 'Ϻ',
			'U+03FB' => 'ϻ',
			'U+03FC' => 'ϼ',
			'U+03FD' => 'Ͻ',
			'U+03FE' => 'Ͼ',
			'U+03FF' => 'Ͽ',
			'U+0400' => 'Ѐ',
			'U+0401' => 'Ё',
			'U+0402' => 'Ђ',
			'U+0403' => 'Ѓ',
			'U+0404' => 'Є',
			'U+0405' => 'Ѕ',
			'U+0406' => 'І',
			'U+0407' => 'Ї',
			'U+0408' => 'Ј',
			'U+0409' => 'Љ',
			'U+040A' => 'Њ',
			'U+040B' => 'Ћ',
			'U+040C' => 'Ќ',
			'U+040D' => 'Ѝ',
			'U+040E' => 'Ў',
			'U+040F' => 'Џ',
			'U+0410' => 'А',
			'U+0411' => 'Б',
			'U+0412' => 'В',
			'U+0413' => 'Г',
			'U+0414' => 'Д',
			'U+0415' => 'Е',
			'U+0416' => 'Ж',
			'U+0417' => 'З',
			'U+0418' => 'И',
			'U+0419' => 'Й',
			'U+041A' => 'К',
			'U+041B' => 'Л',
			'U+041C' => 'М',
			'U+041D' => 'Н',
			'U+041E' => 'О',
			'U+041F' => 'П',
			'U+0420' => 'Р',
			'U+0421' => 'С',
			'U+0422' => 'Т',
			'U+0423' => 'У',
			'U+0424' => 'Ф',
			'U+0425' => 'Х',
			'U+0426' => 'Ц',
			'U+0427' => 'Ч',
			'U+0428' => 'Ш',
			'U+0429' => 'Щ',
			'U+042A' => 'Ъ',
			'U+042B' => 'Ы',
			'U+042C' => 'Ь',
			'U+042D' => 'Э',
			'U+042E' => 'Ю',
			'U+042F' => 'Я',
			'U+0430' => 'а',
			'U+0431' => 'б',
			'U+0432' => 'в',
			'U+0433' => 'г',
			'U+0434' => 'д',
			'U+0435' => 'е',
			'U+0436' => 'ж',
			'U+0437' => 'з',
			'U+0438' => 'и',
			'U+0439' => 'й',
			'U+043A' => 'к',
			'U+043B' => 'л',
			'U+043C' => 'м',
			'U+043D' => 'н',
			'U+043E' => 'о',
			'U+043F' => 'п',
			'U+0440' => 'р',
			'U+0441' => 'с',
			'U+0442' => 'т',
			'U+0443' => 'у',
			'U+0444' => 'ф',
			'U+0445' => 'х',
			'U+0446' => 'ц',
			'U+0447' => 'ч',
			'U+0448' => 'ш',
			'U+0449' => 'щ',
			'U+044A' => 'ъ',
			'U+044B' => 'ы',
			'U+044C' => 'ь',
			'U+044D' => 'э',
			'U+044E' => 'ю',
			'U+044F' => 'я',
			'U+0450' => 'ѐ',
			'U+0451' => 'ё',
			'U+0452' => 'ђ',
			'U+0453' => 'ѓ',
			'U+0454' => 'є',
			'U+0455' => 'ѕ',
			'U+0456' => 'і',
			'U+0457' => 'ї',
			'U+0458' => 'ј',
			'U+0459' => 'љ',
			'U+045A' => 'њ',
			'U+045B' => 'ћ',
			'U+045C' => 'ќ',
			'U+045D' => 'ѝ',
			'U+045E' => 'ў',
			'U+045F' => 'џ',
			'U+0460' => 'Ѡ',
			'U+0461' => 'ѡ',
			'U+0462' => 'Ѣ',
			'U+0463' => 'ѣ',
			'U+0464' => 'Ѥ',
			'U+0465' => 'ѥ',
			'U+0466' => 'Ѧ',
			'U+0467' => 'ѧ',
			'U+0468' => 'Ѩ',
			'U+0469' => 'ѩ',
			'U+046A' => 'Ѫ',
			'U+046B' => 'ѫ',
			'U+046C' => 'Ѭ',
			'U+046D' => 'ѭ',
			'U+046E' => 'Ѯ',
			'U+046F' => 'ѯ',
			'U+0470' => 'Ѱ',
			'U+0471' => 'ѱ',
			'U+0472' => 'Ѳ',
			'U+0473' => 'ѳ',
			'U+0474' => 'Ѵ',
			'U+0475' => 'ѵ',
			'U+0476' => 'Ѷ',
			'U+0477' => 'ѷ',
			'U+0478' => 'Ѹ',
			'U+0479' => 'ѹ',
			'U+047A' => 'Ѻ',
			'U+047B' => 'ѻ',
			'U+047C' => 'Ѽ',
			'U+047D' => 'ѽ',
			'U+047E' => 'Ѿ',
			'U+047F' => 'ѿ',
			'U+0480' => 'Ҁ',
			'U+0481' => 'ҁ',
			'U+0482' => '҂',
			'U+0483' => ' ҃',
			'U+0484' => ' ҄',
			'U+0485' => ' ҅',
			'U+0486' => ' ҆',
			'U+0487' => ' ҇',
			'U+0488' => ' ҈',
			'U+0489' => ' ҉',
			'U+048A' => 'Ҋ',
			'U+048B' => 'ҋ',
			'U+048C' => 'Ҍ',
			'U+048D' => 'ҍ',
			'U+048E' => 'Ҏ',
			'U+048F' => 'ҏ',
			'U+0490' => 'Ґ',
			'U+0491' => 'ґ',
			'U+0492' => 'Ғ',
			'U+0493' => 'ғ',
			'U+0494' => 'Ҕ',
			'U+0495' => 'ҕ',
			'U+0496' => 'Җ',
			'U+0497' => 'җ',
			'U+0498' => 'Ҙ',
			'U+0499' => 'ҙ',
			'U+049A' => 'Қ',
			'U+049B' => 'қ',
			'U+049C' => 'Ҝ',
			'U+049D' => 'ҝ',
			'U+049E' => 'Ҟ',
			'U+049F' => 'ҟ',
			'U+04A0' => 'Ҡ',
			'U+04A1' => 'ҡ',
			'U+04A2' => 'Ң',
			'U+04A3' => 'ң',
			'U+04A4' => 'Ҥ',
			'U+04A5' => 'ҥ',
			'U+04A6' => 'Ҧ',
			'U+04A7' => 'ҧ',
			'U+04A8' => 'Ҩ',
			'U+04A9' => 'ҩ',
			'U+04AA' => 'Ҫ',
			'U+04AB' => 'ҫ',
			'U+04AC' => 'Ҭ',
			'U+04AD' => 'ҭ',
			'U+04AE' => 'Ү',
			'U+04AF' => 'ү',
			'U+04B0' => 'Ұ',
			'U+04B1' => 'ұ',
			'U+04B2' => 'Ҳ',
			'U+04B3' => 'ҳ',
			'U+04B4' => 'Ҵ',
			'U+04B5' => 'ҵ',
			'U+04B6' => 'Ҷ',
			'U+04B7' => 'ҷ',
			'U+04B8' => 'Ҹ',
			'U+04B9' => 'ҹ',
			'U+04BA' => 'Һ',
			'U+04BB' => 'һ',
			'U+04BC' => 'Ҽ',
			'U+04BD' => 'ҽ',
			'U+04BE' => 'Ҿ',
			'U+04BF' => 'ҿ',
			'U+04C0' => 'Ӏ',
			'U+04C1' => 'Ӂ',
			'U+04C2' => 'ӂ',
			'U+04C3' => 'Ӄ',
			'U+04C4' => 'ӄ',
			'U+04C5' => 'Ӆ',
			'U+04C6' => 'ӆ',
			'U+04C7' => 'Ӈ',
			'U+04C8' => 'ӈ',
			'U+04C9' => 'Ӊ',
			'U+04CA' => 'ӊ',
			'U+04CB' => 'Ӌ',
			'U+04CC' => 'ӌ',
			'U+04CD' => 'Ӎ',
			'U+04CE' => 'ӎ',
			'U+04CF' => 'ӏ',
			'U+04D0' => 'Ӑ',
			'U+04D1' => 'ӑ',
			'U+04D2' => 'Ӓ',
			'U+04D3' => 'ӓ',
			'U+04D4' => 'Ӕ',
			'U+04D5' => 'ӕ',
			'U+04D6' => 'Ӗ',
			'U+04D7' => 'ӗ',
			'U+04D8' => 'Ә',
			'U+04D9' => 'ә',
			'U+04DA' => 'Ӛ',
			'U+04DB' => 'ӛ',
			'U+04DC' => 'Ӝ',
			'U+04DD' => 'ӝ',
			'U+04DE' => 'Ӟ',
			'U+04DF' => 'ӟ',
			'U+04E0' => 'Ӡ',
			'U+04E1' => 'ӡ',
			'U+04E2' => 'Ӣ',
			'U+04E3' => 'ӣ',
			'U+04E4' => 'Ӥ',
			'U+04E5' => 'ӥ',
			'U+04E6' => 'Ӧ',
			'U+04E7' => 'ӧ',
			'U+04E8' => 'Ө',
			'U+04E9' => 'ө',
			'U+04EA' => 'Ӫ',
			'U+04EB' => 'ӫ',
			'U+04EC' => 'Ӭ',
			'U+04ED' => 'ӭ',
			'U+04EE' => 'Ӯ',
			'U+04EF' => 'ӯ',
			'U+04F0' => 'Ӱ',
			'U+04F1' => 'ӱ',
			'U+04F2' => 'Ӳ',
			'U+04F3' => 'ӳ',
			'U+04F4' => 'Ӵ',
			'U+04F5' => 'ӵ',
			'U+04F6' => 'Ӷ',
			'U+04F7' => 'ӷ',
			'U+04F8' => 'Ӹ',
			'U+04F9' => 'ӹ',
			'U+04FA' => 'Ӻ',
			'U+04FB' => 'ӻ',
			'U+04FC' => 'Ӽ',
			'U+04FD' => 'ӽ',
			'U+04FE' => 'Ӿ',
			'U+04FF' => 'ӿ',
			'U+0500' => 'Ԁ',
			'U+0501' => 'ԁ',
			'U+0502' => 'Ԃ',
			'U+0503' => 'ԃ',
			'U+0504' => 'Ԅ',
			'U+0505' => 'ԅ',
			'U+0506' => 'Ԇ',
			'U+0507' => 'ԇ',
			'U+0508' => 'Ԉ',
			'U+0509' => 'ԉ',
			'U+050A' => 'Ԋ',
			'U+050B' => 'ԋ',
			'U+050C' => 'Ԍ',
			'U+050D' => 'ԍ',
			'U+050E' => 'Ԏ',
			'U+050F' => 'ԏ',
			'U+0510' => 'Ԑ',
			'U+0511' => 'ԑ',
			'U+0512' => 'Ԓ',
			'U+0513' => 'ԓ',
			'U+0514' => 'Ԕ',
			'U+0515' => 'ԕ',
			'U+0516' => 'Ԗ',
			'U+0517' => 'ԗ',
			'U+0518' => 'Ԙ',
			'U+0519' => 'ԙ',
			'U+051A' => 'Ԛ',
			'U+051B' => 'ԛ',
			'U+051C' => 'Ԝ',
			'U+051D' => 'ԝ',
			'U+051E' => 'Ԟ',
			'U+051F' => 'ԟ',
			'U+0520' => 'Ԡ',
			'U+0521' => 'ԡ',
			'U+0522' => 'Ԣ',
			'U+0523' => 'ԣ',
			'U+0524' => 'Ԥ',
			'U+0525' => 'ԥ',
			'U+0526' => 'Ԧ',
			'U+0527' => 'ԧ',
			'U+0528' => 'Ԩ',
			'U+0529' => 'ԩ',
			'U+052A' => 'Ԫ',
			'U+052B' => 'ԫ',
			'U+052C' => 'Ԭ',
			'U+052D' => 'ԭ',
			'U+052E' => 'Ԯ',
			'U+052F' => 'ԯ',
			'U+0530' => ' ',
			'U+0531' => 'Ա',
			'U+0532' => 'Բ',
			'U+0533' => 'Գ',
			'U+0534' => 'Դ',
			'U+0535' => 'Ե',
			'U+0536' => 'Զ',
			'U+0537' => 'Է',
			'U+0538' => 'Ը',
			'U+0539' => 'Թ',
			'U+053A' => 'Ժ',
			'U+053B' => 'Ի',
			'U+053C' => 'Լ',
			'U+053D' => 'Խ',
			'U+053E' => 'Ծ',
			'U+053F' => 'Կ',
			'U+0540' => 'Հ',
			'U+0541' => 'Ձ',
			'U+0542' => 'Ղ',
			'U+0543' => 'Ճ',
			'U+0544' => 'Մ',
			'U+0545' => 'Յ',
			'U+0546' => 'Ն',
			'U+0547' => 'Շ',
			'U+0548' => 'Ո',
			'U+0549' => 'Չ',
			'U+054A' => 'Պ',
			'U+054B' => 'Ջ',
			'U+054C' => 'Ռ',
			'U+054D' => 'Ս',
			'U+054E' => 'Վ',
			'U+054F' => 'Տ',
			'U+0550' => 'Ր',
			'U+0551' => 'Ց',
			'U+0552' => 'Ւ',
			'U+0553' => 'Փ',
			'U+0554' => 'Ք',
			'U+0555' => 'Օ',
			'U+0556' => 'Ֆ',
			'U+0557' => ' ',
			'U+0558' => ' ',
			'U+0559' => 'ՙ',
			'U+055A' => '՚',
			'U+055B' => '՛',
			'U+055C' => '՜',
			'U+055D' => '՝',
			'U+055E' => '՞',
			'U+055F' => '՟',
			'U+0560' => 'ՠ',
			'U+0561' => 'ա',
			'U+0562' => 'բ',
			'U+0563' => 'գ',
			'U+0564' => 'դ',
			'U+0565' => 'ե',
			'U+0566' => 'զ',
			'U+0567' => 'է',
			'U+0568' => 'ը',
			'U+0569' => 'թ',
			'U+056A' => 'ժ',
			'U+056B' => 'ի',
			'U+056C' => 'լ',
			'U+056D' => 'խ',
			'U+056E' => 'ծ',
			'U+056F' => 'կ',
			'U+0570' => 'հ',
			'U+0571' => 'ձ',
			'U+0572' => 'ղ',
			'U+0573' => 'ճ',
			'U+0574' => 'մ',
			'U+0575' => 'յ',
			'U+0576' => 'ն',
			'U+0577' => 'շ',
			'U+0578' => 'ո',
			'U+0579' => 'չ',
			'U+057A' => 'պ',
			'U+057B' => 'ջ',
			'U+057C' => 'ռ',
			'U+057D' => 'ս',
			'U+057E' => 'վ',
			'U+057F' => 'տ',
			'U+0580' => 'ր',
			'U+0581' => 'ց',
			'U+0582' => 'ւ',
			'U+0583' => 'փ',
			'U+0584' => 'ք',
			'U+0585' => 'օ',
			'U+0586' => 'ֆ',
			'U+0587' => 'և',
			'U+0588' => 'ֈ',
			'U+0589' => '։',
			'U+058A' => '֊',
			'U+058B' => ' ',
			'U+058C' => ' ',
			'U+058D' => '֍',
			'U+058E' => '֎',
			'U+058F' => '֏',
			'U+0590' => ' ',
			'U+0591' => ' ֑',
			'U+0592' => ' ֒',
			'U+0593' => ' ֓',
			'U+0594' => ' ֔',
			'U+0595' => ' ֕',
			'U+0596' => ' ֖',
			'U+0597' => ' ֗',
			'U+0598' => ' ֘',
			'U+0599' => ' ֙',
			'U+059A' => ' ֚',
			'U+059B' => ' ֛',
			'U+059C' => ' ֜',
			'U+059D' => ' ֝',
			'U+059E' => ' ֞',
			'U+059F' => ' ֟',
			'U+05A0' => ' ֠',
			'U+05A1' => ' ֡',
			'U+05A2' => ' ֢',
			'U+05A3' => ' ֣',
			'U+05A4' => ' ֤',
			'U+05A5' => ' ֥',
			'U+05A6' => ' ֦',
			'U+05A7' => ' ֧',
			'U+05A8' => ' ֨',
			'U+05A9' => ' ֩',
			'U+05AA' => ' ֪',
			'U+05AB' => ' ֫',
			'U+05AC' => ' ֬',
			'U+05AD' => ' ֭',
			'U+05AE' => ' ֮',
			'U+05AF' => ' ֯',
			'U+05B0' => ' ְ',
			'U+05B1' => ' ֱ',
			'U+05B2' => ' ֲ',
			'U+05B3' => ' ֳ',
			'U+05B4' => ' ִ',
			'U+05B5' => ' ֵ',
			'U+05B6' => ' ֶ',
			'U+05B7' => ' ַ',
			'U+05B8' => ' ָ',
			'U+05B9' => ' ֹ',
			'U+05BA' => ' ֺ',
			'U+05BB' => ' ֻ',
			'U+05BC' => ' ּ',
			'U+05BD' => ' ֽ',
			'U+05BE' => '־',
			'U+05BF' => ' ֿ',
			'U+05C0' => '׀',
			'U+05C1' => ' ׁ',
			'U+05C2' => ' ׂ',
			'U+05C3' => '׃',
			'U+05C4' => ' ׄ',
			'U+05C5' => ' ׅ',
			'U+05C6' => '׆',
			'U+05C7' => ' ׇ',
			'U+05C8' => ' ',
			'U+05C9' => ' ',
			'U+05CA' => ' ',
			'U+05CB' => ' ',
			'U+05CC' => ' ',
			'U+05CD' => ' ',
			'U+05CE' => ' ',
			'U+05CF' => ' ',
			'U+05D0' => 'א',
			'U+05D1' => 'ב',
			'U+05D2' => 'ג',
			'U+05D3' => 'ד',
			'U+05D4' => 'ה',
			'U+05D5' => 'ו',
			'U+05D6' => 'ז',
			'U+05D7' => 'ח',
			'U+05D8' => 'ט',
			'U+05D9' => 'י',
			'U+05DA' => 'ך',
			'U+05DB' => 'כ',
			'U+05DC' => 'ל',
			'U+05DD' => 'ם',
			'U+05DE' => 'מ',
			'U+05DF' => 'ן',
			'U+05E0' => 'נ',
			'U+05E1' => 'ס',
			'U+05E2' => 'ע',
			'U+05E3' => 'ף',
			'U+05E4' => 'פ',
			'U+05E5' => 'ץ',
			'U+05E6' => 'צ',
			'U+05E7' => 'ק',
			'U+05E8' => 'ר',
			'U+05E9' => 'ש',
			'U+05EA' => 'ת',
			'U+05EB' => ' ',
			'U+05EC' => ' ',
			'U+05ED' => ' ',
			'U+05EE' => ' ',
			'U+05EF' => 'ׯ',
			'U+05F0' => 'װ',
			'U+05F1' => 'ױ',
			'U+05F2' => 'ײ',
			'U+05F3' => '׳',
			'U+05F4' => '״',
			'U+05F5' => ' ',
			'U+05F6' => ' ',
			'U+05F7' => ' ',
			'U+05F8' => ' ',
			'U+05F9' => ' ',
			'U+05FA' => ' ',
			'U+05FB' => ' ',
			'U+05FC' => ' ',
			'U+05FD' => ' ',
			'U+05FE' => ' ',
			'U+05FF' => ' ',
			'U+0600' => '؀',
			'U+0601' => '؁',
			'U+0602' => '؂',
			'U+0603' => '؃',
			'U+0604' => '؄',
			'U+0605' => ' ؅',
			'U+0606' => '؆',
			'U+0607' => '؇',
			'U+0608' => '؈',
			'U+0609' => '؉',
			'U+060A' => '؊',
			'U+060B' => '؋',
			'U+060C' => '،',
			'U+060D' => '؍',
			'U+060E' => '؎',
			'U+060F' => '؏',
			'U+0610' => ' ؐ',
			'U+0611' => ' ؑ',
			'U+0612' => ' ؒ',
			'U+0613' => ' ؓ',
			'U+0614' => ' ؔ',
			'U+0615' => ' ؕ',
			'U+0616' => ' ؖ',
			'U+0617' => ' ؗ',
			'U+0618' => ' ؘ',
			'U+0619' => ' ؙ',
			'U+061A' => ' ؚ',
			'U+061B' => '؛',
			'U+061C' => '[ALM]',
			'U+061D' => ' ',
			'U+061E' => '؞',
			'U+061F' => '؟',
			'U+0620' => 'ؠ',
			'U+0621' => 'ء',
			'U+0622' => 'آ',
			'U+0623' => 'أ',
			'U+0624' => 'ؤ',
			'U+0625' => 'إ',
			'U+0626' => 'ئ',
			'U+0627' => 'ا',
			'U+0628' => 'ب',
			'U+0629' => 'ة',
			'U+062A' => 'ت',
			'U+062B' => 'ث',
			'U+062C' => 'ج',
			'U+062D' => 'ح',
			'U+062E' => 'خ',
			'U+062F' => 'د',
			'U+0630' => 'ذ',
			'U+0631' => 'ر',
			'U+0632' => 'ز',
			'U+0633' => 'س',
			'U+0634' => 'ش',
			'U+0635' => 'ص',
			'U+0636' => 'ض',
			'U+0637' => 'ط',
			'U+0638' => 'ظ',
			'U+0639' => 'ع',
			'U+063A' => 'غ',
			'U+063B' => 'ػ',
			'U+063C' => 'ؼ',
			'U+063D' => 'ؽ',
			'U+063E' => 'ؾ',
			'U+063F' => 'ؿ',
			'U+0640' => 'ـ',
			'U+0641' => 'ف',
			'U+0642' => 'ق',
			'U+0643' => 'ك',
			'U+0644' => 'ل',
			'U+0645' => 'م',
			'U+0646' => 'ن',
			'U+0647' => 'ه',
			'U+0648' => 'و',
			'U+0649' => 'ى',
			'U+064A' => 'ي',
			'U+064B' => ' ً',
			'U+064C' => ' ٌ',
			'U+064D' => ' ٍ',
			'U+064E' => ' َ',
			'U+064F' => ' ُ',
			'U+0650' => ' ِ',
			'U+0651' => ' ّ',
			'U+0652' => ' ْ',
			'U+0653' => ' ٓ',
			'U+0654' => ' ٔ',
			'U+0655' => ' ٕ',
			'U+0656' => ' ٖ',
			'U+0657' => ' ٗ',
			'U+0658' => ' ٘',
			'U+0659' => ' ٙ',
			'U+065A' => ' ٚ',
			'U+065B' => ' ٛ',
			'U+065C' => ' ٜ',
			'U+065D' => ' ٝ',
			'U+065E' => ' ٞ',
			'U+065F' => ' ٟ',
			'U+0660' => '٠',
			'U+0661' => '١',
			'U+0662' => '٢',
			'U+0663' => '٣',
			'U+0664' => '٤',
			'U+0665' => '٥',
			'U+0666' => '٦',
			'U+0667' => '٧',
			'U+0668' => '٨',
			'U+0669' => '٩',
			'U+066A' => '٪',
			'U+066B' => '٫',
			'U+066C' => '٬',
			'U+066D' => '٭',
			'U+066E' => 'ٮ',
			'U+066F' => 'ٯ',
			'U+0670' => ' ٰ',
			'U+0671' => 'ٱ',
			'U+0672' => 'ٲ',
			'U+0673' => 'ٳ',
			'U+0674' => ' ٴ',
			'U+0675' => 'ٵ',
			'U+0676' => 'ٶ',
			'U+0677' => 'ٷ',
			'U+0678' => 'ٸ',
			'U+0679' => 'ٹ',
			'U+067A' => 'ٺ',
			'U+067B' => 'ٻ',
			'U+067C' => 'ټ',
			'U+067D' => 'ٽ',
			'U+067E' => 'پ',
			'U+067F' => 'ٿ',
			'U+0680' => 'ڀ',
			'U+0681' => 'ځ',
			'U+0682' => 'ڂ',
			'U+0683' => 'ڃ',
			'U+0684' => 'ڄ',
			'U+0685' => 'څ',
			'U+0686' => 'چ',
			'U+0687' => 'ڇ',
			'U+0688' => 'ڈ',
			'U+0689' => 'ډ',
			'U+068A' => 'ڊ',
			'U+068B' => 'ڋ',
			'U+068C' => 'ڌ',
			'U+068D' => 'ڍ',
			'U+068E' => 'ڎ',
			'U+068F' => 'ڏ',
			'U+0690' => 'ڐ',
			'U+0691' => 'ڑ',
			'U+0692' => 'ڒ',
			'U+0693' => 'ړ',
			'U+0694' => 'ڔ',
			'U+0695' => 'ڕ',
			'U+0696' => 'ږ',
			'U+0697' => 'ڗ',
			'U+0698' => 'ژ',
			'U+0699' => 'ڙ',
			'U+069A' => 'ښ',
			'U+069B' => 'ڛ',
			'U+069C' => 'ڜ',
			'U+069D' => 'ڝ',
			'U+069E' => 'ڞ',
			'U+069F' => 'ڟ',
			'U+06A0' => 'ڠ',
			'U+06A1' => 'ڡ',
			'U+06A2' => 'ڢ',
			'U+06A3' => 'ڣ',
			'U+06A4' => 'ڤ',
			'U+06A5' => 'ڥ',
			'U+06A6' => 'ڦ',
			'U+06A7' => 'ڧ',
			'U+06A8' => 'ڨ',
			'U+06A9' => 'ک',
			'U+06AA' => 'ڪ',
			'U+06AB' => 'ګ',
			'U+06AC' => 'ڬ',
			'U+06AD' => 'ڭ',
			'U+06AE' => 'ڮ',
			'U+06AF' => 'گ',
			'U+06B0' => 'ڰ',
			'U+06B1' => 'ڱ',
			'U+06B2' => 'ڲ',
			'U+06B3' => 'ڳ',
			'U+06B4' => 'ڴ',
			'U+06B5' => 'ڵ',
			'U+06B6' => 'ڶ',
			'U+06B7' => 'ڷ',
			'U+06B8' => 'ڸ',
			'U+06B9' => 'ڹ',
			'U+06BA' => 'ں',
			'U+06BB' => 'ڻ',
			'U+06BC' => 'ڼ',
			'U+06BD' => 'ڽ',
			'U+06BE' => 'ھ',
			'U+06BF' => 'ڿ',
			'U+06C0' => 'ۀ',
			'U+06C1' => 'ہ',
			'U+06C2' => 'ۂ',
			'U+06C3' => 'ۃ',
			'U+06C4' => 'ۄ',
			'U+06C5' => 'ۅ',
			'U+06C6' => 'ۆ',
			'U+06C7' => 'ۇ',
			'U+06C8' => 'ۈ',
			'U+06C9' => 'ۉ',
			'U+06CA' => 'ۊ',
			'U+06CB' => 'ۋ',
			'U+06CC' => 'ی',
			'U+06CD' => 'ۍ',
			'U+06CE' => 'ێ',
			'U+06CF' => 'ۏ',
			'U+06D0' => 'ې',
			'U+06D1' => 'ۑ',
			'U+06D2' => 'ے',
			'U+06D3' => 'ۓ',
			'U+06D4' => '۔',
			'U+06D5' => 'ە',
			'U+06D6' => ' ۖ',
			'U+06D7' => ' ۗ',
			'U+06D8' => ' ۘ',
			'U+06D9' => ' ۙ',
			'U+06DA' => ' ۚ',
			'U+06DB' => ' ۛ',
			'U+06DC' => ' ۜ',
			'U+06DD' => '۝',
			'U+06DE' => '۞',
			'U+06DF' => ' ۟',
			'U+06E0' => ' ۠',
			'U+06E1' => ' ۡ',
			'U+06E2' => ' ۢ',
			'U+06E3' => ' ۣ',
			'U+06E4' => ' ۤ',
			'U+06E5' => 'ۥ',
			'U+06E6' => 'ۦ',
			'U+06E7' => ' ۧ',
			'U+06E8' => ' ۨ',
			'U+06E9' => '۩',
			'U+06EA' => ' ۪',
			'U+06EB' => ' ۫',
			'U+06EC' => ' ۬',
			'U+06ED' => ' ۭ',
			'U+06EE' => 'ۮ',
			'U+06EF' => 'ۯ',
			'U+06F0' => '۰',
			'U+06F1' => '۱',
			'U+06F2' => '۲',
			'U+06F3' => '۳',
			'U+06F4' => '۴',
			'U+06F5' => '۵',
			'U+06F6' => '۶',
			'U+06F7' => '۷',
			'U+06F8' => '۸',
			'U+06F9' => '۹',
			'U+06FA' => 'ۺ',
			'U+06FB' => 'ۻ',
			'U+06FC' => 'ۼ',
			'U+06FD' => '۽',
			'U+06FE' => '۾',
			'U+06FF' => 'ۿ',
			'U+0700' => '܀',
			'U+0701' => '܁',
			'U+0702' => '܂',
			'U+0703' => '܃',
			'U+0704' => '܄',
			'U+0705' => '܅',
			'U+0706' => '܆',
			'U+0707' => '܇',
			'U+0708' => '܈',
			'U+0709' => '܉',
			'U+070A' => '܊',
			'U+070B' => '܋',
			'U+070C' => '܌',
			'U+070D' => '܍',
			'U+070E' => ' ',
			'U+070F' => ' ܏',
			'U+0710' => 'ܐ',
			'U+0711' => ' ܑ',
			'U+0712' => 'ܒ',
			'U+0713' => 'ܓ',
			'U+0714' => 'ܔ',
			'U+0715' => 'ܕ',
			'U+0716' => 'ܖ',
			'U+0717' => 'ܗ',
			'U+0718' => 'ܘ',
			'U+0719' => 'ܙ',
			'U+071A' => 'ܚ',
			'U+071B' => 'ܛ',
			'U+071C' => 'ܜ',
			'U+071D' => 'ܝ',
			'U+071E' => 'ܞ',
			'U+071F' => 'ܟ',
			'U+0720' => 'ܠ',
			'U+0721' => 'ܡ',
			'U+0722' => 'ܢ',
			'U+0723' => 'ܣ',
			'U+0724' => 'ܤ',
			'U+0725' => 'ܥ',
			'U+0726' => 'ܦ',
			'U+0727' => 'ܧ',
			'U+0728' => 'ܨ',
			'U+0729' => 'ܩ',
			'U+072A' => 'ܪ',
			'U+072B' => 'ܫ',
			'U+072C' => 'ܬ',
			'U+072D' => 'ܭ',
			'U+072E' => 'ܮ',
			'U+072F' => 'ܯ',
			'U+0730' => ' ܰ',
			'U+0731' => ' ܱ',
			'U+0732' => ' ܲ',
			'U+0733' => ' ܳ',
			'U+0734' => ' ܴ',
			'U+0735' => ' ܵ',
			'U+0736' => ' ܶ',
			'U+0737' => ' ܷ',
			'U+0738' => ' ܸ',
			'U+0739' => ' ܹ',
			'U+073A' => ' ܺ',
			'U+073B' => ' ܻ',
			'U+073C' => ' ܼ',
			'U+073D' => ' ܽ',
			'U+073E' => ' ܾ',
			'U+073F' => ' ܿ',
			'U+0740' => ' ݀',
			'U+0741' => ' ݁',
			'U+0742' => ' ݂',
			'U+0743' => ' ݃',
			'U+0744' => ' ݄',
			'U+0745' => ' ݅',
			'U+0746' => ' ݆',
			'U+0747' => ' ݇',
			'U+0748' => ' ݈',
			'U+0749' => ' ݉',
			'U+074A' => ' ݊',
			'U+074B' => ' ',
			'U+074C' => ' ',
			'U+074D' => 'ݍ',
			'U+074E' => 'ݎ',
			'U+074F' => 'ݏ',
			'U+0750' => 'ݐ',
			'U+0751' => 'ݑ',
			'U+0752' => 'ݒ',
			'U+0753' => 'ݓ',
			'U+0754' => 'ݔ',
			'U+0755' => 'ݕ',
			'U+0756' => 'ݖ',
			'U+0757' => 'ݗ',
			'U+0758' => 'ݘ',
			'U+0759' => 'ݙ',
			'U+075A' => 'ݚ',
			'U+075B' => 'ݛ',
			'U+075C' => 'ݜ',
			'U+075D' => 'ݝ',
			'U+075E' => 'ݞ',
			'U+075F' => 'ݟ',
			'U+0760' => 'ݠ',
			'U+0761' => 'ݡ',
			'U+0762' => 'ݢ',
			'U+0763' => 'ݣ',
			'U+0764' => 'ݤ',
			'U+0765' => 'ݥ',
			'U+0766' => 'ݦ',
			'U+0767' => 'ݧ',
			'U+0768' => 'ݨ',
			'U+0769' => 'ݩ',
			'U+076A' => 'ݪ',
			'U+076B' => 'ݫ',
			'U+076C' => 'ݬ',
			'U+076D' => 'ݭ',
			'U+076E' => 'ݮ',
			'U+076F' => 'ݯ',
			'U+0770' => 'ݰ',
			'U+0771' => 'ݱ',
			'U+0772' => 'ݲ',
			'U+0773' => 'ݳ',
			'U+0774' => 'ݴ',
			'U+0775' => 'ݵ',
			'U+0776' => 'ݶ',
			'U+0777' => 'ݷ',
			'U+0778' => 'ݸ',
			'U+0779' => 'ݹ',
			'U+077A' => 'ݺ',
			'U+077B' => 'ݻ',
			'U+077C' => 'ݼ',
			'U+077D' => 'ݽ',
			'U+077E' => 'ݾ',
			'U+077F' => 'ݿ',
			'U+0780' => 'ހ',
			'U+0781' => 'ށ',
			'U+0782' => 'ނ',
			'U+0783' => 'ރ',
			'U+0784' => 'ބ',
			'U+0785' => 'ޅ',
			'U+0786' => 'ކ',
			'U+0787' => 'އ',
			'U+0788' => 'ވ',
			'U+0789' => 'މ',
			'U+078A' => 'ފ',
			'U+078B' => 'ދ',
			'U+078C' => 'ތ',
			'U+078D' => 'ލ',
			'U+078E' => 'ގ',
			'U+078F' => 'ޏ',
			'U+0790' => 'ސ',
			'U+0791' => 'ޑ',
			'U+0792' => 'ޒ',
			'U+0793' => 'ޓ',
			'U+0794' => 'ޔ',
			'U+0795' => 'ޕ',
			'U+0796' => 'ޖ',
			'U+0797' => 'ޗ',
			'U+0798' => 'ޘ',
			'U+0799' => 'ޙ',
			'U+079A' => 'ޚ',
			'U+079B' => 'ޛ',
			'U+079C' => 'ޜ',
			'U+079D' => 'ޝ',
			'U+079E' => 'ޞ',
			'U+079F' => 'ޟ',
			'U+07A0' => 'ޠ',
			'U+07A1' => 'ޡ',
			'U+07A2' => 'ޢ',
			'U+07A3' => 'ޣ',
			'U+07A4' => 'ޤ',
			'U+07A5' => 'ޥ',
			'U+07A6' => ' ަ',
			'U+07A7' => ' ާ',
			'U+07A8' => ' ި',
			'U+07A9' => ' ީ',
			'U+07AA' => ' ު',
			'U+07AB' => ' ޫ',
			'U+07AC' => ' ެ',
			'U+07AD' => ' ޭ',
			'U+07AE' => ' ޮ',
			'U+07AF' => ' ޯ',
			'U+07B0' => ' ް',
			'U+07B1' => 'ޱ',
			'U+07B2' => ' ',
			'U+07B3' => ' ',
			'U+07B4' => ' ',
			'U+07B5' => ' ',
			'U+07B6' => ' ',
			'U+07B7' => ' ',
			'U+07B8' => ' ',
			'U+07B9' => ' ',
			'U+07BA' => ' ',
			'U+07BB' => ' ',
			'U+07BC' => ' ',
			'U+07BD' => ' ',
			'U+07BE' => ' ',
			'U+07BF' => ' ',
			'U+07C0' => '߀',
			'U+07C1' => '߁',
			'U+07C2' => '߂',
			'U+07C3' => '߃',
			'U+07C4' => '߄',
			'U+07C5' => '߅',
			'U+07C6' => '߆',
			'U+07C7' => '߇',
			'U+07C8' => '߈',
			'U+07C9' => '߉',
			'U+07CA' => 'ߊ',
			'U+07CB' => 'ߋ',
			'U+07CC' => 'ߌ',
			'U+07CD' => 'ߍ',
			'U+07CE' => 'ߎ',
			'U+07CF' => 'ߏ',
			'U+07D0' => 'ߐ',
			'U+07D1' => 'ߑ',
			'U+07D2' => 'ߒ',
			'U+07D3' => 'ߓ',
			'U+07D4' => 'ߔ',
			'U+07D5' => 'ߕ',
			'U+07D6' => 'ߖ',
			'U+07D7' => 'ߗ',
			'U+07D8' => 'ߘ',
			'U+07D9' => 'ߙ',
			'U+07DA' => 'ߚ',
			'U+07DB' => 'ߛ',
			'U+07DC' => 'ߜ',
			'U+07DD' => 'ߝ',
			'U+07DE' => 'ߞ',
			'U+07DF' => 'ߟ',
			'U+07E0' => 'ߠ',
			'U+07E1' => 'ߡ',
			'U+07E2' => 'ߢ',
			'U+07E3' => 'ߣ',
			'U+07E4' => 'ߤ',
			'U+07E5' => 'ߥ',
			'U+07E6' => 'ߦ',
			'U+07E7' => 'ߧ',
			'U+07E8' => 'ߨ',
			'U+07E9' => 'ߩ',
			'U+07EA' => 'ߪ',
			'U+07EB' => ' ߫',
			'U+07EC' => ' ߬',
			'U+07ED' => ' ߭',
			'U+07EE' => ' ߮',
			'U+07EF' => ' ߯',
			'U+07F0' => ' ߰',
			'U+07F1' => ' ߱',
			'U+07F2' => ' ߲',
			'U+07F3' => ' ߳',
			'U+07F4' => 'ߴ',
			'U+07F5' => 'ߵ',
			'U+07F6' => '߶',
			'U+07F7' => '߷',
			'U+07F8' => '߸',
			'U+07F9' => '߹',
			'U+07FA' => 'ߺ',
			'U+07FB' => ' ',
			'U+07FC' => ' ',
			'U+07FD' => '߽',
			'U+07FE' => '߾',
			'U+07FF' => '߿',
			'U+0800' => 'ࠀ',
			'U+0801' => 'ࠁ',
			'U+0802' => 'ࠂ',
			'U+0803' => 'ࠃ',
			'U+0804' => 'ࠄ',
			'U+0805' => 'ࠅ',
			'U+0806' => 'ࠆ',
			'U+0807' => 'ࠇ',
			'U+0808' => 'ࠈ',
			'U+0809' => 'ࠉ',
			'U+080A' => 'ࠊ',
			'U+080B' => 'ࠋ',
			'U+080C' => 'ࠌ',
			'U+080D' => 'ࠍ',
			'U+080E' => 'ࠎ',
			'U+080F' => 'ࠏ',
			'U+0810' => 'ࠐ',
			'U+0811' => 'ࠑ',
			'U+0812' => 'ࠒ',
			'U+0813' => 'ࠓ',
			'U+0814' => 'ࠔ',
			'U+0815' => 'ࠕ',
			'U+0816' => 'ࠖ',
			'U+0817' => 'ࠗ',
			'U+0818' => '࠘',
			'U+0819' => '࠙',
			'U+081A' => 'ࠚ',
			'U+081B' => 'ࠛ',
			'U+081C' => 'ࠜ',
			'U+081D' => 'ࠝ',
			'U+081E' => 'ࠞ',
			'U+081F' => 'ࠟ',
			'U+0820' => 'ࠠ',
			'U+0821' => 'ࠡ',
			'U+0822' => 'ࠢ',
			'U+0823' => 'ࠣ',
			'U+0824' => 'ࠤ',
			'U+0825' => 'ࠥ',
			'U+0826' => 'ࠦ',
			'U+0827' => 'ࠧ',
			'U+0828' => 'ࠨ',
			'U+0829' => 'ࠩ',
			'U+082A' => 'ࠪ',
			'U+082B' => 'ࠫ',
			'U+082C' => 'ࠬ',
			'U+082D' => '࠭',
			'U+082E' => ' ',
			'U+082F' => ' ',
			'U+0830' => '࠰',
			'U+0831' => '࠱',
			'U+0832' => '࠲',
			'U+0833' => '࠳',
			'U+0834' => '࠴',
			'U+0835' => '࠵',
			'U+0836' => '࠶',
			'U+0837' => '࠷',
			'U+0838' => '࠸',
			'U+0839' => '࠹',
			'U+083A' => '࠺',
			'U+083B' => '࠻',
			'U+083C' => '࠼',
			'U+083D' => '࠽',
			'U+083E' => '࠾',
			'U+083F' => ' ',
			'U+0840' => 'ࡀ',
			'U+0841' => 'ࡁ',
			'U+0842' => 'ࡂ',
			'U+0843' => 'ࡃ',
			'U+0844' => 'ࡄ',
			'U+0845' => 'ࡅ',
			'U+0846' => 'ࡆ',
			'U+0847' => 'ࡇ',
			'U+0848' => 'ࡈ',
			'U+0849' => 'ࡉ',
			'U+084A' => 'ࡊ',
			'U+084B' => 'ࡋ',
			'U+084C' => 'ࡌ',
			'U+084D' => 'ࡍ',
			'U+084E' => 'ࡎ',
			'U+084F' => 'ࡏ',
			'U+0850' => 'ࡐ',
			'U+0851' => 'ࡑ',
			'U+0852' => 'ࡒ',
			'U+0853' => 'ࡓ',
			'U+0854' => 'ࡔ',
			'U+0855' => 'ࡕ',
			'U+0856' => 'ࡖ',
			'U+0857' => 'ࡗ',
			'U+0858' => 'ࡘ',
			'U+0859' => '࡙',
			'U+085A' => '࡚',
			'U+085B' => '࡛',
			'U+085C' => ' ',
			'U+085D' => ' ',
			'U+085E' => '࡞',
			'U+085F' => ' ',
			'U+0860' => 'ࡠ',
			'U+0861' => 'ࡡ',
			'U+0862' => 'ࡢ',
			'U+0863' => 'ࡣ',
			'U+0864' => 'ࡤ',
			'U+0865' => 'ࡥ',
			'U+0866' => 'ࡦ',
			'U+0867' => 'ࡧ',
			'U+0868' => 'ࡨ',
			'U+0869' => 'ࡩ',
			'U+086A' => 'ࡪ',
			'U+086B' => ' ',
			'U+086C' => ' ',
			'U+086D' => ' ',
			'U+086E' => ' ',
			'U+086F' => ' ',
			'U+0870' => ' ',
			'U+0871' => ' ',
			'U+0872' => ' ',
			'U+0873' => ' ',
			'U+0874' => ' ',
			'U+0875' => ' ',
			'U+0876' => ' ',
			'U+0877' => ' ',
			'U+0878' => ' ',
			'U+0879' => ' ',
			'U+087A' => ' ',
			'U+087B' => ' ',
			'U+087C' => ' ',
			'U+087D' => ' ',
			'U+087E' => ' ',
			'U+087F' => ' ',
			'U+0880' => ' ',
			'U+0881' => ' ',
			'U+0882' => ' ',
			'U+0883' => ' ',
			'U+0884' => ' ',
			'U+0885' => ' ',
			'U+0886' => ' ',
			'U+0887' => ' ',
			'U+0888' => ' ',
			'U+0889' => ' ',
			'U+088A' => ' ',
			'U+088B' => ' ',
			'U+088C' => ' ',
			'U+088D' => ' ',
			'U+088E' => ' ',
			'U+088F' => ' ',
			'U+0890' => ' ',
			'U+0891' => ' ',
			'U+0892' => ' ',
			'U+0893' => ' ',
			'U+0894' => ' ',
			'U+0895' => ' ',
			'U+0896' => ' ',
			'U+0897' => ' ',
			'U+0898' => ' ',
			'U+0899' => ' ',
			'U+089A' => ' ',
			'U+089B' => ' ',
			'U+089C' => ' ',
			'U+089D' => ' ',
			'U+089E' => ' ',
			'U+089F' => ' ',
			'U+08A0' => 'ࢠ',
			'U+08A1' => 'ࢡ',
			'U+08A2' => 'ࢢ',
			'U+08A3' => 'ࢣ',
			'U+08A4' => 'ࢤ',
			'U+08A5' => 'ࢥ',
			'U+08A6' => 'ࢦ',
			'U+08A7' => 'ࢧ',
			'U+08A8' => 'ࢨ',
			'U+08A9' => 'ࢩ',
			'U+08AA' => 'ࢪ',
			'U+08AB' => 'ࢫ',
			'U+08AC' => 'ࢬ',
			'U+08AD' => 'ࢭ',
			'U+08AE' => 'ࢮ',
			'U+08AF' => 'ࢯ',
			'U+08B0' => 'ࢰ',
			'U+08B1' => 'ࢱ',
			'U+08B2' => 'ࢲ',
			'U+08B3' => 'ࢳ',
			'U+08B4' => 'ࢴ',
			'U+08B5' => ' ',
			'U+08B6' => 'ࢶ',
			'U+08B7' => 'ࢷ',
			'U+08B8' => 'ࢸ',
			'U+08B9' => 'ࢹ',
			'U+08BA' => 'ࢺ',
			'U+08BB' => 'ࢻ',
			'U+08BC' => 'ࢼ',
			'U+08BD' => 'ࢽ',
			'U+08BE' => ' ',
			'U+08BF' => ' ',
			'U+08C0' => ' ',
			'U+08C1' => ' ',
			'U+08C2' => ' ',
			'U+08C3' => ' ',
			'U+08C4' => ' ',
			'U+08C5' => ' ',
			'U+08C6' => ' ',
			'U+08C7' => ' ',
			'U+08C8' => ' ',
			'U+08C9' => ' ',
			'U+08CA' => ' ',
			'U+08CB' => ' ',
			'U+08CC' => ' ',
			'U+08CD' => ' ',
			'U+08CE' => ' ',
			'U+08CF' => ' ',
			'U+08D0' => ' ',
			'U+08D1' => ' ',
			'U+08D2' => ' ',
			'U+08D3' => '࣓',
			'U+08D4' => 'ࣔ',
			'U+08D5' => 'ࣕ',
			'U+08D6' => 'ࣖ',
			'U+08D7' => 'ࣗ',
			'U+08D8' => 'ࣘ',
			'U+08D9' => 'ࣙ',
			'U+08DA' => 'ࣚ',
			'U+08DB' => 'ࣛ',
			'U+08DC' => 'ࣜ',
			'U+08DD' => 'ࣝ',
			'U+08DE' => 'ࣞ',
			'U+08DF' => 'ࣟ',
			'U+08E0' => '࣠',
			'U+08E1' => '࣡',
			'U+08E2' => '࣢',
			'U+08E3' => ' ࣣ',
			'U+08E4' => ' ࣤ',
			'U+08E5' => ' ࣥ',
			'U+08E6' => ' ࣦ',
			'U+08E7' => ' ࣧ',
			'U+08E8' => ' ࣨ',
			'U+08E9' => ' ࣩ',
			'U+08EA' => ' ࣪',
			'U+08EB' => ' ࣫',
			'U+08EC' => ' ࣬',
			'U+08ED' => ' ࣭',
			'U+08EE' => ' ࣮',
			'U+08EF' => ' ࣯',
			'U+08F0' => ' ࣰ',
			'U+08F1' => ' ࣱ',
			'U+08F2' => ' ࣲ',
			'U+08F3' => ' ࣳ',
			'U+08F4' => ' ࣴ',
			'U+08F5' => ' ࣵ',
			'U+08F6' => ' ࣶ',
			'U+08F7' => ' ࣷ',
			'U+08F8' => ' ࣸ',
			'U+08F9' => ' ࣹ',
			'U+08FA' => ' ࣺ',
			'U+08FB' => ' ࣻ',
			'U+08FC' => ' ࣼ',
			'U+08FD' => ' ࣽ',
			'U+08FE' => ' ࣾ',
			'U+08FF' => ' ࣿ',
			'U+0900' => 'ऀ',
			'U+0901' => 'ँ',
			'U+0902' => 'ं',
			'U+0903' => 'ः',
			'U+0904' => 'ऄ',
			'U+0905' => 'अ',
			'U+0906' => 'आ',
			'U+0907' => 'इ',
			'U+0908' => 'ई',
			'U+0909' => 'उ',
			'U+090A' => 'ऊ',
			'U+090B' => 'ऋ',
			'U+090C' => 'ऌ',
			'U+090D' => 'ऍ',
			'U+090E' => 'ऎ',
			'U+090F' => 'ए',
			'U+0910' => 'ऐ',
			'U+0911' => 'ऑ',
			'U+0912' => 'ऒ',
			'U+0913' => 'ओ',
			'U+0914' => 'औ',
			'U+0915' => 'क',
			'U+0916' => 'ख',
			'U+0917' => 'ग',
			'U+0918' => 'घ',
			'U+0919' => 'ङ',
			'U+091A' => 'च',
			'U+091B' => 'छ',
			'U+091C' => 'ज',
			'U+091D' => 'झ',
			'U+091E' => 'ञ',
			'U+091F' => 'ट',
			'U+0920' => 'ठ',
			'U+0921' => 'ड',
			'U+0922' => 'ढ',
			'U+0923' => 'ण',
			'U+0924' => 'त',
			'U+0925' => 'थ',
			'U+0926' => 'द',
			'U+0927' => 'ध',
			'U+0928' => 'न',
			'U+0929' => 'ऩ',
			'U+092A' => 'प',
			'U+092B' => 'फ',
			'U+092C' => 'ब',
			'U+092D' => 'भ',
			'U+092E' => 'म',
			'U+092F' => 'य',
			'U+0930' => 'र',
			'U+0931' => 'ऱ',
			'U+0932' => 'ल',
			'U+0933' => 'ळ',
			'U+0934' => 'ऴ',
			'U+0935' => 'व',
			'U+0936' => 'श',
			'U+0937' => 'ष',
			'U+0938' => 'स',
			'U+0939' => 'ह',
			'U+093A' => 'ऺ',
			'U+093B' => 'ऻ',
			'U+093C' => '़',
			'U+093D' => 'ऽ',
			'U+093E' => 'ा',
			'U+093F' => 'ि',
			'U+0940' => 'ी',
			'U+0941' => 'ु',
			'U+0942' => 'ू',
			'U+0943' => 'ृ',
			'U+0944' => 'ॄ',
			'U+0945' => 'ॅ',
			'U+0946' => 'ॆ',
			'U+0947' => 'े',
			'U+0948' => 'ै',
			'U+0949' => 'ॉ',
			'U+094A' => 'ॊ',
			'U+094B' => 'ो',
			'U+094C' => 'ौ',
			'U+094D' => '्',
			'U+094E' => 'ॎ',
			'U+094F' => 'ॏ',
			'U+0950' => 'ॐ',
			'U+0951' => '॑',
			'U+0952' => '॒',
			'U+0953' => '॓',
			'U+0954' => '॔',
			'U+0955' => 'ॕ',
			'U+0956' => 'ॖ',
			'U+0957' => 'ॗ',
			'U+0958' => 'क़',
			'U+0959' => 'ख़',
			'U+095A' => 'ग़',
			'U+095B' => 'ज़',
			'U+095C' => 'ड़',
			'U+095D' => 'ढ़',
			'U+095E' => 'फ़',
			'U+095F' => 'य़',
			'U+0960' => 'ॠ',
			'U+0961' => 'ॡ',
			'U+0962' => 'ॢ',
			'U+0963' => 'ॣ',
			'U+0964' => '।',
			'U+0965' => '॥',
			'U+0966' => '०',
			'U+0967' => '१',
			'U+0968' => '२',
			'U+0969' => '३',
			'U+096A' => '४',
			'U+096B' => '५',
			'U+096C' => '६',
			'U+096D' => '७',
			'U+096E' => '८',
			'U+096F' => '९',
			'U+0970' => '॰',
			'U+0971' => 'ॱ',
			'U+0972' => 'ॲ',
			'U+0973' => 'ॳ',
			'U+0974' => 'ॴ',
			'U+0975' => 'ॵ',
			'U+0976' => 'ॶ',
			'U+0977' => 'ॷ',
			'U+0978' => 'ॸ',
			'U+0979' => 'ॹ',
			'U+097A' => 'ॺ',
			'U+097B' => 'ॻ',
			'U+097C' => 'ॼ',
			'U+097D' => 'ॽ',
			'U+097E' => 'ॾ',
			'U+097F' => 'ॿ',
			'U+0980' => 'ঀ',
			'U+0981' => 'ঁ',
			'U+0982' => 'ং',
			'U+0983' => 'ঃ',
			'U+0984' => ' ',
			'U+0985' => 'অ',
			'U+0986' => 'আ',
			'U+0987' => 'ই',
			'U+0988' => 'ঈ',
			'U+0989' => 'উ',
			'U+098A' => 'ঊ',
			'U+098B' => 'ঋ',
			'U+098C' => 'ঌ',
			'U+098D' => ' ',
			'U+098E' => ' ',
			'U+098F' => 'এ',
			'U+0990' => 'ঐ',
			'U+0991' => ' ',
			'U+0992' => ' ',
			'U+0993' => 'ও',
			'U+0994' => 'ঔ',
			'U+0995' => 'ক',
			'U+0996' => 'খ',
			'U+0997' => 'গ',
			'U+0998' => 'ঘ',
			'U+0999' => 'ঙ',
			'U+099A' => 'চ',
			'U+099B' => 'ছ',
			'U+099C' => 'জ',
			'U+099D' => 'ঝ',
			'U+099E' => 'ঞ',
			'U+099F' => 'ট',
			'U+09A0' => 'ঠ',
			'U+09A1' => 'ড',
			'U+09A2' => 'ঢ',
			'U+09A3' => 'ণ',
			'U+09A4' => 'ত',
			'U+09A5' => 'থ',
			'U+09A6' => 'দ',
			'U+09A7' => 'ধ',
			'U+09A8' => 'ন',
			'U+09A9' => ' ',
			'U+09AA' => 'প',
			'U+09AB' => 'ফ',
			'U+09AC' => 'ব',
			'U+09AD' => 'ভ',
			'U+09AE' => 'ম',
			'U+09AF' => 'য',
			'U+09B0' => 'র',
			'U+09B1' => ' ',
			'U+09B2' => 'ল',
			'U+09B3' => ' ',
			'U+09B4' => ' ',
			'U+09B5' => ' ',
			'U+09B6' => 'শ',
			'U+09B7' => 'ষ',
			'U+09B8' => 'স',
			'U+09B9' => 'হ',
			'U+09BA' => ' ',
			'U+09BB' => ' ',
			'U+09BC' => '়',
			'U+09BD' => 'ঽ',
			'U+09BE' => 'া',
			'U+09BF' => 'ি',
			'U+09C0' => 'ী',
			'U+09C1' => 'ু',
			'U+09C2' => 'ূ',
			'U+09C3' => 'ৃ',
			'U+09C4' => 'ৄ',
			'U+09C5' => ' ',
			'U+09C6' => ' ',
			'U+09C7' => 'ে',
			'U+09C8' => 'ৈ',
			'U+09C9' => ' ',
			'U+09CA' => ' ',
			'U+09CB' => 'ো',
			'U+09CC' => 'ৌ',
			'U+09CD' => '্',
			'U+09CE' => 'ৎ',
			'U+09CF' => ' ',
			'U+09D0' => ' ',
			'U+09D1' => ' ',
			'U+09D2' => ' ',
			'U+09D3' => ' ',
			'U+09D4' => ' ',
			'U+09D5' => ' ',
			'U+09D6' => ' ',
			'U+09D7' => 'ৗ',
			'U+09D8' => ' ',
			'U+09D9' => ' ',
			'U+09DA' => ' ',
			'U+09DB' => ' ',
			'U+09DC' => 'ড়',
			'U+09DD' => 'ঢ়',
			'U+09DE' => ' ',
			'U+09DF' => 'য়',
			'U+09E0' => 'ৠ',
			'U+09E1' => 'ৡ',
			'U+09E2' => 'ৢ',
			'U+09E3' => 'ৣ',
			'U+09E4' => ' ',
			'U+09E5' => ' ',
			'U+09E6' => '০',
			'U+09E7' => '১',
			'U+09E8' => '২',
			'U+09E9' => '৩',
			'U+09EA' => '৪',
			'U+09EB' => '৫',
			'U+09EC' => '৬',
			'U+09ED' => '৭',
			'U+09EE' => '৮',
			'U+09EF' => '৯',
			'U+09F0' => 'ৰ',
			'U+09F1' => 'ৱ',
			'U+09F2' => '৲',
			'U+09F3' => '৳',
			'U+09F4' => '৴',
			'U+09F5' => '৵',
			'U+09F6' => '৶',
			'U+09F7' => '৷',
			'U+09F8' => '৸',
			'U+09F9' => '৹',
			'U+09FA' => '৺',
			'U+09FB' => '৻',
			'U+09FC' => 'ৼ',
			'U+09FD' => '৽',
			'U+09FE' => '৾',
			'U+09FF' => ' ',
			'U+0A00' => ' ',
			'U+0A01' => 'ਁ',
			'U+0A02' => 'ਂ',
			'U+0A03' => 'ਃ',
			'U+0A04' => ' ',
			'U+0A05' => 'ਅ',
			'U+0A06' => 'ਆ',
			'U+0A07' => 'ਇ',
			'U+0A08' => 'ਈ',
			'U+0A09' => 'ਉ',
			'U+0A0A' => 'ਊ',
			'U+0A0B' => ' ',
			'U+0A0C' => ' ',
			'U+0A0D' => ' ',
			'U+0A0E' => ' ',
			'U+0A0F' => 'ਏ',
			'U+0A10' => 'ਐ',
			'U+0A11' => ' ',
			'U+0A12' => ' ',
			'U+0A13' => 'ਓ',
			'U+0A14' => 'ਔ',
			'U+0A15' => 'ਕ',
			'U+0A16' => 'ਖ',
			'U+0A17' => 'ਗ',
			'U+0A18' => 'ਘ',
			'U+0A19' => 'ਙ',
			'U+0A1A' => 'ਚ',
			'U+0A1B' => 'ਛ',
			'U+0A1C' => 'ਜ',
			'U+0A1D' => 'ਝ',
			'U+0A1E' => 'ਞ',
			'U+0A1F' => 'ਟ',
			'U+0A20' => 'ਠ',
			'U+0A21' => 'ਡ',
			'U+0A22' => 'ਢ',
			'U+0A23' => 'ਣ',
			'U+0A24' => 'ਤ',
			'U+0A25' => 'ਥ',
			'U+0A26' => 'ਦ',
			'U+0A27' => 'ਧ',
			'U+0A28' => 'ਨ',
			'U+0A29' => ' ',
			'U+0A2A' => 'ਪ',
			'U+0A2B' => 'ਫ',
			'U+0A2C' => 'ਬ',
			'U+0A2D' => 'ਭ',
			'U+0A2E' => 'ਮ',
			'U+0A2F' => 'ਯ',
			'U+0A30' => 'ਰ',
			'U+0A31' => ' ',
			'U+0A32' => 'ਲ',
			'U+0A33' => 'ਲ਼',
			'U+0A34' => ' ',
			'U+0A35' => 'ਵ',
			'U+0A36' => 'ਸ਼',
			'U+0A37' => ' ',
			'U+0A38' => 'ਸ',
			'U+0A39' => 'ਹ',
			'U+0A3A' => ' ',
			'U+0A3B' => ' ',
			'U+0A3C' => '਼',
			'U+0A3D' => ' ',
			'U+0A3E' => 'ਾ',
			'U+0A3F' => 'ਿ',
			'U+0A40' => 'ੀ',
			'U+0A41' => 'ੁ',
			'U+0A42' => 'ੂ',
			'U+0A43' => ' ',
			'U+0A44' => ' ',
			'U+0A45' => ' ',
			'U+0A46' => ' ',
			'U+0A47' => 'ੇ',
			'U+0A48' => 'ੈ',
			'U+0A49' => ' ',
			'U+0A4A' => ' ',
			'U+0A4B' => 'ੋ',
			'U+0A4C' => 'ੌ',
			'U+0A4D' => '੍',
			'U+0A4E' => ' ',
			'U+0A4F' => ' ',
			'U+0A50' => ' ',
			'U+0A51' => ' ੑ',
			'U+0A52' => ' ',
			'U+0A53' => ' ',
			'U+0A54' => ' ',
			'U+0A55' => ' ',
			'U+0A56' => ' ',
			'U+0A57' => ' ',
			'U+0A58' => ' ',
			'U+0A59' => 'ਖ਼',
			'U+0A5A' => 'ਗ਼',
			'U+0A5B' => 'ਜ਼',
			'U+0A5C' => 'ੜ',
			'U+0A5D' => ' ',
			'U+0A5E' => 'ਫ਼',
			'U+0A5F' => ' ',
			'U+0A60' => ' ',
			'U+0A61' => ' ',
			'U+0A62' => ' ',
			'U+0A63' => ' ',
			'U+0A64' => ' ',
			'U+0A65' => ' ',
			'U+0A66' => '੦',
			'U+0A67' => '੧',
			'U+0A68' => '੨',
			'U+0A69' => '੩',
			'U+0A6A' => '੪',
			'U+0A6B' => '੫',
			'U+0A6C' => '੬',
			'U+0A6D' => '੭',
			'U+0A6E' => '੮',
			'U+0A6F' => '੯',
			'U+0A70' => 'ੰ',
			'U+0A71' => 'ੱ',
			'U+0A72' => 'ੲ',
			'U+0A73' => 'ੳ',
			'U+0A74' => 'ੴ',
			'U+0A75' => 'ੵ',
			'U+0A76' => '੶',
			'U+0A77' => ' ',
			'U+0A78' => ' ',
			'U+0A79' => ' ',
			'U+0A7A' => ' ',
			'U+0A7B' => ' ',
			'U+0A7C' => ' ',
			'U+0A7D' => ' ',
			'U+0A7E' => ' ',
			'U+0A7F' => ' ',
			'U+0A80' => ' ',
			'U+0A81' => 'ઁ',
			'U+0A82' => 'ં',
			'U+0A83' => 'ઃ',
			'U+0A84' => ' ',
			'U+0A85' => 'અ',
			'U+0A86' => 'આ',
			'U+0A87' => 'ઇ',
			'U+0A88' => 'ઈ',
			'U+0A89' => 'ઉ',
			'U+0A8A' => 'ઊ',
			'U+0A8B' => 'ઋ',
			'U+0A8C' => 'ઌ',
			'U+0A8D' => 'ઍ',
			'U+0A8E' => ' ',
			'U+0A8F' => 'એ',
			'U+0A90' => 'ઐ',
			'U+0A91' => 'ઑ',
			'U+0A92' => ' ',
			'U+0A93' => 'ઓ',
			'U+0A94' => 'ઔ',
			'U+0A95' => 'ક',
			'U+0A96' => 'ખ',
			'U+0A97' => 'ગ',
			'U+0A98' => 'ઘ',
			'U+0A99' => 'ઙ',
			'U+0A9A' => 'ચ',
			'U+0A9B' => 'છ',
			'U+0A9C' => 'જ',
			'U+0A9D' => 'ઝ',
			'U+0A9E' => 'ઞ',
			'U+0A9F' => 'ટ',
			'U+0AA0' => 'ઠ',
			'U+0AA1' => 'ડ',
			'U+0AA2' => 'ઢ',
			'U+0AA3' => 'ણ',
			'U+0AA4' => 'ત',
			'U+0AA5' => 'થ',
			'U+0AA6' => 'દ',
			'U+0AA7' => 'ધ',
			'U+0AA8' => 'ન',
			'U+0AA9' => ' ',
			'U+0AAA' => 'પ',
			'U+0AAB' => 'ફ',
			'U+0AAC' => 'બ',
			'U+0AAD' => 'ભ',
			'U+0AAE' => 'મ',
			'U+0AAF' => 'ય',
			'U+0AB0' => 'ર',
			'U+0AB1' => ' ',
			'U+0AB2' => 'લ',
			'U+0AB3' => 'ળ',
			'U+0AB4' => ' ',
			'U+0AB5' => 'વ',
			'U+0AB6' => 'શ',
			'U+0AB7' => 'ષ',
			'U+0AB8' => 'સ',
			'U+0AB9' => 'હ',
			'U+0ABA' => ' ',
			'U+0ABB' => ' ',
			'U+0ABC' => '઼',
			'U+0ABD' => 'ઽ',
			'U+0ABE' => 'ા',
			'U+0ABF' => 'િ',
			'U+0AC0' => 'ી',
			'U+0AC1' => 'ુ',
			'U+0AC2' => 'ૂ',
			'U+0AC3' => 'ૃ',
			'U+0AC4' => 'ૄ',
			'U+0AC5' => 'ૅ',
			'U+0AC6' => ' ',
			'U+0AC7' => 'ે',
			'U+0AC8' => 'ૈ',
			'U+0AC9' => 'ૉ',
			'U+0ACA' => ' ',
			'U+0ACB' => 'ો',
			'U+0ACC' => 'ૌ',
			'U+0ACD' => '્',
			'U+0ACE' => ' ',
			'U+0ACF' => ' ',
			'U+0AD0' => 'ૐ',
			'U+0AD1' => ' ',
			'U+0AD2' => ' ',
			'U+0AD3' => ' ',
			'U+0AD4' => ' ',
			'U+0AD5' => ' ',
			'U+0AD6' => ' ',
			'U+0AD7' => ' ',
			'U+0AD8' => ' ',
			'U+0AD9' => ' ',
			'U+0ADA' => ' ',
			'U+0ADB' => ' ',
			'U+0ADC' => ' ',
			'U+0ADD' => ' ',
			'U+0ADE' => ' ',
			'U+0ADF' => ' ',
			'U+0AE0' => 'ૠ',
			'U+0AE1' => 'ૡ',
			'U+0AE2' => 'ૢ',
			'U+0AE3' => 'ૣ',
			'U+0AE4' => ' ',
			'U+0AE5' => ' ',
			'U+0AE6' => '૦',
			'U+0AE7' => '૧',
			'U+0AE8' => '૨',
			'U+0AE9' => '૩',
			'U+0AEA' => '૪',
			'U+0AEB' => '૫',
			'U+0AEC' => '૬',
			'U+0AED' => '૭',
			'U+0AEE' => '૮',
			'U+0AEF' => '૯',
			'U+0AF0' => '૰',
			'U+0AF1' => '૱',
			'U+0AF2' => ' ',
			'U+0AF3' => ' ',
			'U+0AF4' => ' ',
			'U+0AF5' => ' ',
			'U+0AF6' => ' ',
			'U+0AF7' => ' ',
			'U+0AF8' => ' ',
			'U+0AF9' => 'ૹ',
			'U+0AFA' => 'ૺ',
			'U+0AFB' => 'ૻ',
			'U+0AFC' => 'ૼ',
			'U+0AFD' => '૽',
			'U+0AFE' => '૾',
			'U+0AFF' => '૿',
			'U+0B00' => ' ',
			'U+0B01' => 'ଁ',
			'U+0B02' => 'ଂ',
			'U+0B03' => 'ଃ',
			'U+0B04' => ' ',
			'U+0B05' => 'ଅ',
			'U+0B06' => 'ଆ',
			'U+0B07' => 'ଇ',
			'U+0B08' => 'ଈ',
			'U+0B09' => 'ଉ',
			'U+0B0A' => 'ଊ',
			'U+0B0B' => 'ଋ',
			'U+0B0C' => 'ଌ',
			'U+0B0D' => ' ',
			'U+0B0E' => ' ',
			'U+0B0F' => 'ଏ',
			'U+0B10' => 'ଐ',
			'U+0B11' => ' ',
			'U+0B12' => ' ',
			'U+0B13' => 'ଓ',
			'U+0B14' => 'ଔ',
			'U+0B15' => 'କ',
			'U+0B16' => 'ଖ',
			'U+0B17' => 'ଗ',
			'U+0B18' => 'ଘ',
			'U+0B19' => 'ଙ',
			'U+0B1A' => 'ଚ',
			'U+0B1B' => 'ଛ',
			'U+0B1C' => 'ଜ',
			'U+0B1D' => 'ଝ',
			'U+0B1E' => 'ଞ',
			'U+0B1F' => 'ଟ',
			'U+0B20' => 'ଠ',
			'U+0B21' => 'ଡ',
			'U+0B22' => 'ଢ',
			'U+0B23' => 'ଣ',
			'U+0B24' => 'ତ',
			'U+0B25' => 'ଥ',
			'U+0B26' => 'ଦ',
			'U+0B27' => 'ଧ',
			'U+0B28' => 'ନ',
			'U+0B29' => ' ',
			'U+0B2A' => 'ପ',
			'U+0B2B' => 'ଫ',
			'U+0B2C' => 'ବ',
			'U+0B2D' => 'ଭ',
			'U+0B2E' => 'ମ',
			'U+0B2F' => 'ଯ',
			'U+0B30' => 'ର',
			'U+0B31' => ' ',
			'U+0B32' => 'ଲ',
			'U+0B33' => 'ଳ',
			'U+0B34' => ' ',
			'U+0B35' => 'ଵ',
			'U+0B36' => 'ଶ',
			'U+0B37' => 'ଷ',
			'U+0B38' => 'ସ',
			'U+0B39' => 'ହ',
			'U+0B3A' => ' ',
			'U+0B3B' => ' ',
			'U+0B3C' => '଼',
			'U+0B3D' => 'ଽ',
			'U+0B3E' => 'ା',
			'U+0B3F' => 'ି',
			'U+0B40' => 'ୀ',
			'U+0B41' => 'ୁ',
			'U+0B42' => 'ୂ',
			'U+0B43' => 'ୃ',
			'U+0B44' => 'ୄ',
			'U+0B45' => ' ',
			'U+0B46' => ' ',
			'U+0B47' => 'େ',
			'U+0B48' => 'ୈ',
			'U+0B49' => ' ',
			'U+0B4A' => ' ',
			'U+0B4B' => 'ୋ',
			'U+0B4C' => 'ୌ',
			'U+0B4D' => '୍',
			'U+0B4E' => ' ',
			'U+0B4F' => ' ',
			'U+0B50' => ' ',
			'U+0B51' => ' ',
			'U+0B52' => ' ',
			'U+0B53' => ' ',
			'U+0B54' => ' ',
			'U+0B55' => ' ',
			'U+0B56' => 'ୖ',
			'U+0B57' => 'ୗ',
			'U+0B58' => ' ',
			'U+0B59' => ' ',
			'U+0B5A' => ' ',
			'U+0B5B' => ' ',
			'U+0B5C' => 'ଡ଼',
			'U+0B5D' => 'ଢ଼',
			'U+0B5E' => ' ',
			'U+0B5F' => 'ୟ',
			'U+0B60' => 'ୠ',
			'U+0B61' => 'ୡ',
			'U+0B62' => 'ୢ',
			'U+0B63' => 'ୣ',
			'U+0B64' => ' ',
			'U+0B65' => ' ',
			'U+0B66' => '୦',
			'U+0B67' => '୧',
			'U+0B68' => '୨',
			'U+0B69' => '୩',
			'U+0B6A' => '୪',
			'U+0B6B' => '୫',
			'U+0B6C' => '୬',
			'U+0B6D' => '୭',
			'U+0B6E' => '୮',
			'U+0B6F' => '୯',
			'U+0B70' => '୰',
			'U+0B71' => 'ୱ',
			'U+0B72' => '୲',
			'U+0B73' => '୳',
			'U+0B74' => '୴',
			'U+0B75' => '୵',
			'U+0B76' => '୶',
			'U+0B77' => '୷',
			'U+0B78' => ' ',
			'U+0B79' => ' ',
			'U+0B7A' => ' ',
			'U+0B7B' => ' ',
			'U+0B7C' => ' ',
			'U+0B7D' => ' ',
			'U+0B7E' => ' ',
			'U+0B7F' => ' ',
			'U+0B80' => ' ',
			'U+0B81' => ' ',
			'U+0B82' => 'ஂ',
			'U+0B83' => 'ஃ',
			'U+0B84' => ' ',
			'U+0B85' => 'அ',
			'U+0B86' => 'ஆ',
			'U+0B87' => 'இ',
			'U+0B88' => 'ஈ',
			'U+0B89' => 'உ',
			'U+0B8A' => 'ஊ',
			'U+0B8B' => ' ',
			'U+0B8C' => ' ',
			'U+0B8D' => ' ',
			'U+0B8E' => 'எ',
			'U+0B8F' => 'ஏ',
			'U+0B90' => 'ஐ',
			'U+0B91' => ' ',
			'U+0B92' => 'ஒ',
			'U+0B93' => 'ஓ',
			'U+0B94' => 'ஔ',
			'U+0B95' => 'க',
			'U+0B96' => ' ',
			'U+0B97' => ' ',
			'U+0B98' => ' ',
			'U+0B99' => 'ங',
			'U+0B9A' => 'ச',
			'U+0B9B' => ' ',
			'U+0B9C' => 'ஜ',
			'U+0B9D' => ' ',
			'U+0B9E' => 'ஞ',
			'U+0B9F' => 'ட',
			'U+0BA0' => ' ',
			'U+0BA1' => ' ',
			'U+0BA2' => ' ',
			'U+0BA3' => 'ண',
			'U+0BA4' => 'த',
			'U+0BA5' => ' ',
			'U+0BA6' => ' ',
			'U+0BA7' => ' ',
			'U+0BA8' => 'ந',
			'U+0BA9' => 'ன',
			'U+0BAA' => 'ப',
			'U+0BAB' => ' ',
			'U+0BAC' => ' ',
			'U+0BAD' => ' ',
			'U+0BAE' => 'ம',
			'U+0BAF' => 'ய',
			'U+0BB0' => 'ர',
			'U+0BB1' => 'ற',
			'U+0BB2' => 'ல',
			'U+0BB3' => 'ள',
			'U+0BB4' => 'ழ',
			'U+0BB5' => 'வ',
			'U+0BB6' => 'ஶ',
			'U+0BB7' => 'ஷ',
			'U+0BB8' => 'ஸ',
			'U+0BB9' => 'ஹ',
			'U+0BBA' => ' ',
			'U+0BBB' => ' ',
			'U+0BBC' => ' ',
			'U+0BBD' => ' ',
			'U+0BBE' => 'ா',
			'U+0BBF' => 'ி',
			'U+0BC0' => 'ீ',
			'U+0BC1' => 'ு',
			'U+0BC2' => 'ூ',
			'U+0BC3' => ' ',
			'U+0BC4' => ' ',
			'U+0BC5' => ' ',
			'U+0BC6' => 'ெ',
			'U+0BC7' => 'ே',
			'U+0BC8' => 'ை',
			'U+0BC9' => ' ',
			'U+0BCA' => 'ொ',
			'U+0BCB' => 'ோ',
			'U+0BCC' => 'ௌ',
			'U+0BCD' => '்',
			'U+0BCE' => ' ',
			'U+0BCF' => ' ',
			'U+0BD0' => 'ௐ',
			'U+0BD1' => ' ',
			'U+0BD2' => ' ',
			'U+0BD3' => ' ',
			'U+0BD4' => ' ',
			'U+0BD5' => ' ',
			'U+0BD6' => ' ',
			'U+0BD7' => 'ௗ',
			'U+0BD8' => ' ',
			'U+0BD9' => ' ',
			'U+0BDA' => ' ',
			'U+0BDB' => ' ',
			'U+0BDC' => ' ',
			'U+0BDD' => ' ',
			'U+0BDE' => ' ',
			'U+0BDF' => ' ',
			'U+0BE0' => ' ',
			'U+0BE1' => ' ',
			'U+0BE2' => ' ',
			'U+0BE3' => ' ',
			'U+0BE4' => ' ',
			'U+0BE5' => ' ',
			'U+0BE6' => '௦',
			'U+0BE7' => '௧',
			'U+0BE8' => '௨',
			'U+0BE9' => '௩',
			'U+0BEA' => '௪',
			'U+0BEB' => '௫',
			'U+0BEC' => '௬',
			'U+0BED' => '௭',
			'U+0BEE' => '௮',
			'U+0BEF' => '௯',
			'U+0BF0' => '௰',
			'U+0BF1' => '௱',
			'U+0BF2' => '௲',
			'U+0BF3' => '௳',
			'U+0BF4' => '௴',
			'U+0BF5' => '௵',
			'U+0BF6' => '௶',
			'U+0BF7' => '௷',
			'U+0BF8' => '௸',
			'U+0BF9' => '௹',
			'U+0BFA' => '௺',
			'U+0BFB' => ' ',
			'U+0BFC' => ' ',
			'U+0BFD' => ' ',
			'U+0BFE' => ' ',
			'U+0BFF' => ' ',
			'U+0C00' => 'ఀ',
			'U+0C01' => 'ఁ',
			'U+0C02' => 'ం',
			'U+0C03' => 'ః',
			'U+0C04' => 'ఄ',
			'U+0C05' => 'అ',
			'U+0C06' => 'ఆ',
			'U+0C07' => 'ఇ',
			'U+0C08' => 'ఈ',
			'U+0C09' => 'ఉ',
			'U+0C0A' => 'ఊ',
			'U+0C0B' => 'ఋ',
			'U+0C0C' => 'ఌ',
			'U+0C0D' => ' ',
			'U+0C0E' => 'ఎ',
			'U+0C0F' => 'ఏ',
			'U+0C10' => 'ఐ',
			'U+0C11' => ' ',
			'U+0C12' => 'ఒ',
			'U+0C13' => 'ఓ',
			'U+0C14' => 'ఔ',
			'U+0C15' => 'క',
			'U+0C16' => 'ఖ',
			'U+0C17' => 'గ',
			'U+0C18' => 'ఘ',
			'U+0C19' => 'ఙ',
			'U+0C1A' => 'చ',
			'U+0C1B' => 'ఛ',
			'U+0C1C' => 'జ',
			'U+0C1D' => 'ఝ',
			'U+0C1E' => 'ఞ',
			'U+0C1F' => 'ట',
			'U+0C20' => 'ఠ',
			'U+0C21' => 'డ',
			'U+0C22' => 'ఢ',
			'U+0C23' => 'ణ',
			'U+0C24' => 'త',
			'U+0C25' => 'థ',
			'U+0C26' => 'ద',
			'U+0C27' => 'ధ',
			'U+0C28' => 'న',
			'U+0C29' => ' ',
			'U+0C2A' => 'ప',
			'U+0C2B' => 'ఫ',
			'U+0C2C' => 'బ',
			'U+0C2D' => 'భ',
			'U+0C2E' => 'మ',
			'U+0C2F' => 'య',
			'U+0C30' => 'ర',
			'U+0C31' => 'ఱ',
			'U+0C32' => 'ల',
			'U+0C33' => 'ళ',
			'U+0C34' => 'ఴ',
			'U+0C35' => 'వ',
			'U+0C36' => 'శ',
			'U+0C37' => 'ష',
			'U+0C38' => 'స',
			'U+0C39' => 'హ',
			'U+0C3A' => ' ',
			'U+0C3B' => ' ',
			'U+0C3C' => ' ',
			'U+0C3D' => 'ఽ',
			'U+0C3E' => 'ా',
			'U+0C3F' => 'ి',
			'U+0C40' => 'ీ',
			'U+0C41' => 'ు',
			'U+0C42' => 'ూ',
			'U+0C43' => 'ృ',
			'U+0C44' => 'ౄ',
			'U+0C45' => ' ',
			'U+0C46' => 'ె',
			'U+0C47' => 'ే',
			'U+0C48' => 'ై',
			'U+0C49' => ' ',
			'U+0C4A' => 'ొ',
			'U+0C4B' => 'ో',
			'U+0C4C' => 'ౌ',
			'U+0C4D' => '్',
			'U+0C4E' => ' ',
			'U+0C4F' => ' ',
			'U+0C50' => ' ',
			'U+0C51' => ' ',
			'U+0C52' => ' ',
			'U+0C53' => ' ',
			'U+0C54' => ' ',
			'U+0C55' => 'ౕ',
			'U+0C56' => 'ౖ',
			'U+0C57' => ' ',
			'U+0C58' => 'ౘ',
			'U+0C59' => 'ౙ',
			'U+0C5A' => 'ౚ',
			'U+0C5B' => ' ',
			'U+0C5C' => ' ',
			'U+0C5D' => ' ',
			'U+0C5E' => ' ',
			'U+0C5F' => ' ',
			'U+0C60' => 'ౠ',
			'U+0C61' => 'ౡ',
			'U+0C62' => 'ౢ',
			'U+0C63' => 'ౣ',
			'U+0C64' => ' ',
			'U+0C65' => ' ',
			'U+0C66' => '౦',
			'U+0C67' => '౧',
			'U+0C68' => '౨',
			'U+0C69' => '౩',
			'U+0C6A' => '౪',
			'U+0C6B' => '౫',
			'U+0C6C' => '౬',
			'U+0C6D' => '౭',
			'U+0C6E' => '౮',
			'U+0C6F' => '౯',
			'U+0C70' => ' ',
			'U+0C71' => ' ',
			'U+0C72' => ' ',
			'U+0C73' => ' ',
			'U+0C74' => ' ',
			'U+0C75' => ' ',
			'U+0C76' => ' ',
			'U+0C77' => ' ',
			'U+0C78' => '౸',
			'U+0C79' => '౹',
			'U+0C7A' => '౺',
			'U+0C7B' => '౻',
			'U+0C7C' => '౼',
			'U+0C7D' => '౽',
			'U+0C7E' => '౾',
			'U+0C7F' => '౿',
			'U+0C80' => 'ಀ',
			'U+0C81' => 'ಁ',
			'U+0C82' => 'ಂ',
			'U+0C83' => 'ಃ',
			'U+0C84' => '಄',
			'U+0C85' => 'ಅ',
			'U+0C86' => 'ಆ',
			'U+0C87' => 'ಇ',
			'U+0C88' => 'ಈ',
			'U+0C89' => 'ಉ',
			'U+0C8A' => 'ಊ',
			'U+0C8B' => 'ಋ',
			'U+0C8C' => 'ಌ',
			'U+0C8D' => ' ',
			'U+0C8E' => 'ಎ',
			'U+0C8F' => 'ಏ',
			'U+0C90' => 'ಐ',
			'U+0C91' => ' ',
			'U+0C92' => 'ಒ',
			'U+0C93' => 'ಓ',
			'U+0C94' => 'ಔ',
			'U+0C95' => 'ಕ',
			'U+0C96' => 'ಖ',
			'U+0C97' => 'ಗ',
			'U+0C98' => 'ಘ',
			'U+0C99' => 'ಙ',
			'U+0C9A' => 'ಚ',
			'U+0C9B' => 'ಛ',
			'U+0C9C' => 'ಜ',
			'U+0C9D' => 'ಝ',
			'U+0C9E' => 'ಞ',
			'U+0C9F' => 'ಟ',
			'U+0CA0' => 'ಠ',
			'U+0CA1' => 'ಡ',
			'U+0CA2' => 'ಢ',
			'U+0CA3' => 'ಣ',
			'U+0CA4' => 'ತ',
			'U+0CA5' => 'ಥ',
			'U+0CA6' => 'ದ',
			'U+0CA7' => 'ಧ',
			'U+0CA8' => 'ನ',
			'U+0CA9' => ' ',
			'U+0CAA' => 'ಪ',
			'U+0CAB' => 'ಫ',
			'U+0CAC' => 'ಬ',
			'U+0CAD' => 'ಭ',
			'U+0CAE' => 'ಮ',
			'U+0CAF' => 'ಯ',
			'U+0CB0' => 'ರ',
			'U+0CB1' => 'ಱ',
			'U+0CB2' => 'ಲ',
			'U+0CB3' => 'ಳ',
			'U+0CB4' => ' ',
			'U+0CB5' => 'ವ',
			'U+0CB6' => 'ಶ',
			'U+0CB7' => 'ಷ',
			'U+0CB8' => 'ಸ',
			'U+0CB9' => 'ಹ',
			'U+0CBA' => ' ',
			'U+0CBB' => ' ',
			'U+0CBC' => '಼',
			'U+0CBD' => 'ಽ',
			'U+0CBE' => 'ಾ',
			'U+0CBF' => 'ಿ',
			'U+0CC0' => 'ೀ',
			'U+0CC1' => 'ು',
			'U+0CC2' => 'ೂ',
			'U+0CC3' => 'ೃ',
			'U+0CC4' => 'ೄ',
			'U+0CC5' => ' ',
			'U+0CC6' => 'ೆ',
			'U+0CC7' => 'ೇ',
			'U+0CC8' => 'ೈ',
			'U+0CC9' => ' ',
			'U+0CCA' => 'ೊ',
			'U+0CCB' => 'ೋ',
			'U+0CCC' => 'ೌ',
			'U+0CCD' => '್',
			'U+0CCE' => ' ',
			'U+0CCF' => ' ',
			'U+0CD0' => ' ',
			'U+0CD1' => ' ',
			'U+0CD2' => ' ',
			'U+0CD3' => ' ',
			'U+0CD4' => ' ',
			'U+0CD5' => 'ೕ',
			'U+0CD6' => 'ೖ',
			'U+0CD7' => ' ',
			'U+0CD8' => ' ',
			'U+0CD9' => ' ',
			'U+0CDA' => ' ',
			'U+0CDB' => ' ',
			'U+0CDC' => ' ',
			'U+0CDD' => ' ',
			'U+0CDE' => 'ೞ',
			'U+0CDF' => ' ',
			'U+0CE0' => 'ೠ',
			'U+0CE1' => 'ೡ',
			'U+0CE2' => 'ೢ',
			'U+0CE3' => 'ೣ',
			'U+0CE4' => ' ',
			'U+0CE5' => ' ',
			'U+0CE6' => '೦',
			'U+0CE7' => '೧',
			'U+0CE8' => '೨',
			'U+0CE9' => '೩',
			'U+0CEA' => '೪',
			'U+0CEB' => '೫',
			'U+0CEC' => '೬',
			'U+0CED' => '೭',
			'U+0CEE' => '೮',
			'U+0CEF' => '೯',
			'U+0CF0' => ' ',
			'U+0CF1' => 'ೱ',
			'U+0CF2' => 'ೲ',
			'U+0CF3' => ' ',
			'U+0CF4' => ' ',
			'U+0CF5' => ' ',
			'U+0CF6' => ' ',
			'U+0CF7' => ' ',
			'U+0CF8' => ' ',
			'U+0CF9' => ' ',
			'U+0CFA' => ' ',
			'U+0CFB' => ' ',
			'U+0CFC' => ' ',
			'U+0CFD' => ' ',
			'U+0CFE' => ' ',
			'U+0CFF' => ' ',
			'U+0D00' => 'ഀ',
			'U+0D01' => 'ഁ',
			'U+0D02' => 'ം',
			'U+0D03' => 'ഃ',
			'U+0D04' => ' ',
			'U+0D05' => 'അ',
			'U+0D06' => 'ആ',
			'U+0D07' => 'ഇ',
			'U+0D08' => 'ഈ',
			'U+0D09' => 'ഉ',
			'U+0D0A' => 'ഊ',
			'U+0D0B' => 'ഋ',
			'U+0D0C' => 'ഌ',
			'U+0D0D' => ' ',
			'U+0D0E' => 'എ',
			'U+0D0F' => 'ഏ',
			'U+0D10' => 'ഐ',
			'U+0D11' => ' ',
			'U+0D12' => 'ഒ',
			'U+0D13' => 'ഓ',
			'U+0D14' => 'ഔ',
			'U+0D15' => 'ക',
			'U+0D16' => 'ഖ',
			'U+0D17' => 'ഗ',
			'U+0D18' => 'ഘ',
			'U+0D19' => 'ങ',
			'U+0D1A' => 'ച',
			'U+0D1B' => 'ഛ',
			'U+0D1C' => 'ജ',
			'U+0D1D' => 'ഝ',
			'U+0D1E' => 'ഞ',
			'U+0D1F' => 'ട',
			'U+0D20' => 'ഠ',
			'U+0D21' => 'ഡ',
			'U+0D22' => 'ഢ',
			'U+0D23' => 'ണ',
			'U+0D24' => 'ത',
			'U+0D25' => 'ഥ',
			'U+0D26' => 'ദ',
			'U+0D27' => 'ധ',
			'U+0D28' => 'ന',
			'U+0D29' => 'ഩ',
			'U+0D2A' => 'പ',
			'U+0D2B' => 'ഫ',
			'U+0D2C' => 'ബ',
			'U+0D2D' => 'ഭ',
			'U+0D2E' => 'മ',
			'U+0D2F' => 'യ',
			'U+0D30' => 'ര',
			'U+0D31' => 'റ',
			'U+0D32' => 'ല',
			'U+0D33' => 'ള',
			'U+0D34' => 'ഴ',
			'U+0D35' => 'വ',
			'U+0D36' => 'ശ',
			'U+0D37' => 'ഷ',
			'U+0D38' => 'സ',
			'U+0D39' => 'ഹ',
			'U+0D3A' => 'ഺ',
			'U+0D3B' => '഻',
			'U+0D3C' => '഼',
			'U+0D3D' => 'ഽ',
			'U+0D3E' => 'ാ',
			'U+0D3F' => 'ി',
			'U+0D40' => 'ീ',
			'U+0D41' => 'ു',
			'U+0D42' => 'ൂ',
			'U+0D43' => 'ൃ',
			'U+0D44' => 'ൄ',
			'U+0D45' => ' ',
			'U+0D46' => 'െ',
			'U+0D47' => 'േ',
			'U+0D48' => 'ൈ',
			'U+0D49' => ' ',
			'U+0D4A' => 'ൊ',
			'U+0D4B' => 'ോ',
			'U+0D4C' => 'ൌ',
			'U+0D4D' => '്',
			'U+0D4E' => 'ൎ',
			'U+0D4F' => '൏',
			'U+0D50' => ' ',
			'U+0D51' => ' ',
			'U+0D52' => ' ',
			'U+0D53' => ' ',
			'U+0D54' => 'ൔ',
			'U+0D55' => 'ൕ',
			'U+0D56' => 'ൖ',
			'U+0D57' => 'ൗ',
			'U+0D58' => '൘',
			'U+0D59' => '൙',
			'U+0D5A' => '൚',
			'U+0D5B' => '൛',
			'U+0D5C' => '൜',
			'U+0D5D' => '൝',
			'U+0D5E' => '൞',
			'U+0D5F' => 'ൟ',
			'U+0D60' => 'ൠ',
			'U+0D61' => 'ൡ',
			'U+0D62' => 'ൢ',
			'U+0D63' => 'ൣ',
			'U+0D64' => ' ',
			'U+0D65' => ' ',
			'U+0D66' => '൦',
			'U+0D67' => '൧',
			'U+0D68' => '൨',
			'U+0D69' => '൩',
			'U+0D6A' => '൪',
			'U+0D6B' => '൫',
			'U+0D6C' => '൬',
			'U+0D6D' => '൭',
			'U+0D6E' => '൮',
			'U+0D6F' => '൯',
			'U+0D70' => '൰',
			'U+0D71' => '൱',
			'U+0D72' => '൲',
			'U+0D73' => '൳',
			'U+0D74' => '൴',
			'U+0D75' => '൵',
			'U+0D76' => '൶',
			'U+0D77' => '൷',
			'U+0D78' => '൸',
			'U+0D79' => '൹',
			'U+0D7A' => 'ൺ',
			'U+0D7B' => 'ൻ',
			'U+0D7C' => 'ർ',
			'U+0D7D' => 'ൽ',
			'U+0D7E' => 'ൾ',
			'U+0D7F' => 'ൿ',
			'U+0D80' => ' ',
			'U+0D81' => ' ',
			'U+0D82' => 'ං',
			'U+0D83' => 'ඃ',
			'U+0D84' => ' ',
			'U+0D85' => 'අ',
			'U+0D86' => 'ආ',
			'U+0D87' => 'ඇ',
			'U+0D88' => 'ඈ',
			'U+0D89' => 'ඉ',
			'U+0D8A' => 'ඊ',
			'U+0D8B' => 'උ',
			'U+0D8C' => 'ඌ',
			'U+0D8D' => 'ඍ',
			'U+0D8E' => 'ඎ',
			'U+0D8F' => 'ඏ',
			'U+0D90' => 'ඐ',
			'U+0D91' => 'එ',
			'U+0D92' => 'ඒ',
			'U+0D93' => 'ඓ',
			'U+0D94' => 'ඔ',
			'U+0D95' => 'ඕ',
			'U+0D96' => 'ඖ',
			'U+0D97' => ' ',
			'U+0D98' => ' ',
			'U+0D99' => ' ',
			'U+0D9A' => 'ක',
			'U+0D9B' => 'ඛ',
			'U+0D9C' => 'ග',
			'U+0D9D' => 'ඝ',
			'U+0D9E' => 'ඞ',
			'U+0D9F' => 'ඟ',
			'U+0DA0' => 'ච',
			'U+0DA1' => 'ඡ',
			'U+0DA2' => 'ජ',
			'U+0DA3' => 'ඣ',
			'U+0DA4' => 'ඤ',
			'U+0DA5' => 'ඥ',
			'U+0DA6' => 'ඦ',
			'U+0DA7' => 'ට',
			'U+0DA8' => 'ඨ',
			'U+0DA9' => 'ඩ',
			'U+0DAA' => 'ඪ',
			'U+0DAB' => 'ණ',
			'U+0DAC' => 'ඬ',
			'U+0DAD' => 'ත',
			'U+0DAE' => 'ථ',
			'U+0DAF' => 'ද',
			'U+0DB0' => 'ධ',
			'U+0DB1' => 'න',
			'U+0DB2' => ' ',
			'U+0DB3' => 'ඳ',
			'U+0DB4' => 'ප',
			'U+0DB5' => 'ඵ',
			'U+0DB6' => 'බ',
			'U+0DB7' => 'භ',
			'U+0DB8' => 'ම',
			'U+0DB9' => 'ඹ',
			'U+0DBA' => 'ය',
			'U+0DBB' => 'ර',
			'U+0DBC' => ' ',
			'U+0DBD' => 'ල',
			'U+0DBE' => ' ',
			'U+0DBF' => ' ',
			'U+0DC0' => 'ව',
			'U+0DC1' => 'ශ',
			'U+0DC2' => 'ෂ',
			'U+0DC3' => 'ස',
			'U+0DC4' => 'හ',
			'U+0DC5' => 'ළ',
			'U+0DC6' => 'ෆ',
			'U+0DC7' => ' ',
			'U+0DC8' => ' ',
			'U+0DC9' => ' ',
			'U+0DCA' => '්',
			'U+0DCB' => ' ',
			'U+0DCC' => ' ',
			'U+0DCD' => ' ',
			'U+0DCE' => ' ',
			'U+0DCF' => 'ා',
			'U+0DD0' => 'ැ',
			'U+0DD1' => 'ෑ',
			'U+0DD2' => 'ි',
			'U+0DD3' => 'ී',
			'U+0DD4' => 'ු',
			'U+0DD5' => ' ',
			'U+0DD6' => 'ූ',
			'U+0DD7' => ' ',
			'U+0DD8' => 'ෘ',
			'U+0DD9' => 'ෙ',
			'U+0DDA' => 'ේ',
			'U+0DDB' => 'ෛ',
			'U+0DDC' => 'ො',
			'U+0DDD' => 'ෝ',
			'U+0DDE' => 'ෞ',
			'U+0DDF' => 'ෟ',
			'U+0DE0' => ' ',
			'U+0DE1' => ' ',
			'U+0DE2' => ' ',
			'U+0DE3' => ' ',
			'U+0DE4' => ' ',
			'U+0DE5' => ' ',
			'U+0DE6' => '෦',
			'U+0DE7' => '෧',
			'U+0DE8' => '෨',
			'U+0DE9' => '෩',
			'U+0DEA' => '෪',
			'U+0DEB' => '෫',
			'U+0DEC' => '෬',
			'U+0DED' => '෭',
			'U+0DEE' => '෮',
			'U+0DEF' => '෯',
			'U+0DF0' => ' ',
			'U+0DF1' => ' ',
			'U+0DF2' => 'ෲ',
			'U+0DF3' => 'ෳ',
			'U+0DF4' => '෴',
			'U+0DF5' => ' ',
			'U+0DF6' => ' ',
			'U+0DF7' => ' ',
			'U+0DF8' => ' ',
			'U+0DF9' => ' ',
			'U+0DFA' => ' ',
			'U+0DFB' => ' ',
			'U+0DFC' => ' ',
			'U+0DFD' => ' ',
			'U+0DFE' => ' ',
			'U+0DFF' => ' ',
			'U+0E00' => ' ',
			'U+0E01' => 'ก',
			'U+0E02' => 'ข',
			'U+0E03' => 'ฃ',
			'U+0E04' => 'ค',
			'U+0E05' => 'ฅ',
			'U+0E06' => 'ฆ',
			'U+0E07' => 'ง',
			'U+0E08' => 'จ',
			'U+0E09' => 'ฉ',
			'U+0E0A' => 'ช',
			'U+0E0B' => 'ซ',
			'U+0E0C' => 'ฌ',
			'U+0E0D' => 'ญ',
			'U+0E0E' => 'ฎ',
			'U+0E0F' => 'ฏ',
			'U+0E10' => 'ฐ',
			'U+0E11' => 'ฑ',
			'U+0E12' => 'ฒ',
			'U+0E13' => 'ณ',
			'U+0E14' => 'ด',
			'U+0E15' => 'ต',
			'U+0E16' => 'ถ',
			'U+0E17' => 'ท',
			'U+0E18' => 'ธ',
			'U+0E19' => 'น',
			'U+0E1A' => 'บ',
			'U+0E1B' => 'ป',
			'U+0E1C' => 'ผ',
			'U+0E1D' => 'ฝ',
			'U+0E1E' => 'พ',
			'U+0E1F' => 'ฟ',
			'U+0E20' => 'ภ',
			'U+0E21' => 'ม',
			'U+0E22' => 'ย',
			'U+0E23' => 'ร',
			'U+0E24' => 'ฤ',
			'U+0E25' => 'ล',
			'U+0E26' => 'ฦ',
			'U+0E27' => 'ว',
			'U+0E28' => 'ศ',
			'U+0E29' => 'ษ',
			'U+0E2A' => 'ส',
			'U+0E2B' => 'ห',
			'U+0E2C' => 'ฬ',
			'U+0E2D' => 'อ',
			'U+0E2E' => 'ฮ',
			'U+0E2F' => 'ฯ',
			'U+0E30' => 'ะ',
			'U+0E31' => ' ั',
			'U+0E32' => 'า',
			'U+0E33' => 'ำ',
			'U+0E34' => ' ิ',
			'U+0E35' => ' ี',
			'U+0E36' => ' ึ',
			'U+0E37' => ' ื',
			'U+0E38' => ' ุ',
			'U+0E39' => ' ู',
			'U+0E3A' => ' ฺ',
			'U+0E3B' => ' ',
			'U+0E3C' => ' ',
			'U+0E3D' => ' ',
			'U+0E3E' => ' ',
			'U+0E3F' => '฿',
			'U+0E40' => 'เ',
			'U+0E41' => 'แ',
			'U+0E42' => 'โ',
			'U+0E43' => 'ใ',
			'U+0E44' => 'ไ',
			'U+0E45' => 'ๅ',
			'U+0E46' => 'ๆ',
			'U+0E47' => ' ็',
			'U+0E48' => ' ่',
			'U+0E49' => ' ้',
			'U+0E4A' => ' ๊',
			'U+0E4B' => ' ๋',
			'U+0E4C' => ' ์',
			'U+0E4D' => ' ํ',
			'U+0E4E' => ' ๎',
			'U+0E4F' => '๏',
			'U+0E50' => '๐',
			'U+0E51' => '๑',
			'U+0E52' => '๒',
			'U+0E53' => '๓',
			'U+0E54' => '๔',
			'U+0E55' => '๕',
			'U+0E56' => '๖',
			'U+0E57' => '๗',
			'U+0E58' => '๘',
			'U+0E59' => '๙',
			'U+0E5A' => '๚',
			'U+0E5B' => '๛',
			'U+0E5C' => ' ',
			'U+0E5D' => ' ',
			'U+0E5E' => ' ',
			'U+0E5F' => ' ',
			'U+0E60' => ' ',
			'U+0E61' => ' ',
			'U+0E62' => ' ',
			'U+0E63' => ' ',
			'U+0E64' => ' ',
			'U+0E65' => ' ',
			'U+0E66' => ' ',
			'U+0E67' => ' ',
			'U+0E68' => ' ',
			'U+0E69' => ' ',
			'U+0E6A' => ' ',
			'U+0E6B' => ' ',
			'U+0E6C' => ' ',
			'U+0E6D' => ' ',
			'U+0E6E' => ' ',
			'U+0E6F' => ' ',
			'U+0E70' => ' ',
			'U+0E71' => ' ',
			'U+0E72' => ' ',
			'U+0E73' => ' ',
			'U+0E74' => ' ',
			'U+0E75' => ' ',
			'U+0E76' => ' ',
			'U+0E77' => ' ',
			'U+0E78' => ' ',
			'U+0E79' => ' ',
			'U+0E7A' => ' ',
			'U+0E7B' => ' ',
			'U+0E7C' => ' ',
			'U+0E7D' => ' ',
			'U+0E7E' => ' ',
			'U+0E7F' => ' ',
			'U+0E80' => ' ',
			'U+0E81' => 'ກ',
			'U+0E82' => 'ຂ',
			'U+0E83' => ' ',
			'U+0E84' => 'ຄ',
			'U+0E85' => ' ',
			'U+0E86' => ' ',
			'U+0E87' => 'ງ',
			'U+0E88' => 'ຈ',
			'U+0E89' => ' ',
			'U+0E8A' => 'ຊ',
			'U+0E8B' => ' ',
			'U+0E8C' => ' ',
			'U+0E8D' => 'ຍ',
			'U+0E8E' => ' ',
			'U+0E8F' => ' ',
			'U+0E90' => ' ',
			'U+0E91' => ' ',
			'U+0E92' => ' ',
			'U+0E93' => ' ',
			'U+0E94' => 'ດ',
			'U+0E95' => 'ຕ',
			'U+0E96' => 'ຖ',
			'U+0E97' => 'ທ',
			'U+0E98' => ' ',
			'U+0E99' => 'ນ',
			'U+0E9A' => 'ບ',
			'U+0E9B' => 'ປ',
			'U+0E9C' => 'ຜ',
			'U+0E9D' => 'ຝ',
			'U+0E9E' => 'ພ',
			'U+0E9F' => 'ຟ',
			'U+0EA0' => ' ',
			'U+0EA1' => 'ມ',
			'U+0EA2' => 'ຢ',
			'U+0EA3' => 'ຣ',
			'U+0EA4' => ' ',
			'U+0EA5' => 'ລ',
			'U+0EA6' => ' ',
			'U+0EA7' => 'ວ',
			'U+0EA8' => ' ',
			'U+0EA9' => ' ',
			'U+0EAA' => 'ສ',
			'U+0EAB' => 'ຫ',
			'U+0EAC' => ' ',
			'U+0EAD' => 'ອ',
			'U+0EAE' => 'ຮ',
			'U+0EAF' => 'ຯ',
			'U+0EB0' => 'ະ',
			'U+0EB1' => ' ັ',
			'U+0EB2' => 'າ',
			'U+0EB3' => 'ຳ',
			'U+0EB4' => ' ິ',
			'U+0EB5' => ' ີ',
			'U+0EB6' => ' ຶ',
			'U+0EB7' => ' ື',
			'U+0EB8' => ' ຸ',
			'U+0EB9' => ' ູ',
			'U+0EBA' => ' ',
			'U+0EBB' => ' ົ',
			'U+0EBC' => ' ຼ',
			'U+0EBD' => 'ຽ',
			'U+0EBE' => ' ',
			'U+0EBF' => ' ',
			'U+0EC0' => 'ເ',
			'U+0EC1' => 'ແ',
			'U+0EC2' => 'ໂ',
			'U+0EC3' => 'ໃ',
			'U+0EC4' => 'ໄ',
			'U+0EC5' => ' ',
			'U+0EC6' => 'ໆ',
			'U+0EC7' => ' ',
			'U+0EC8' => ' ່',
			'U+0EC9' => ' ້',
			'U+0ECA' => ' ໊',
			'U+0ECB' => ' ໋',
			'U+0ECC' => ' ໌',
			'U+0ECD' => ' ໍ',
			'U+0ECE' => ' ',
			'U+0ECF' => ' ',
			'U+0ED0' => '໐',
			'U+0ED1' => '໑',
			'U+0ED2' => '໒',
			'U+0ED3' => '໓',
			'U+0ED4' => '໔',
			'U+0ED5' => '໕',
			'U+0ED6' => '໖',
			'U+0ED7' => '໗',
			'U+0ED8' => '໘',
			'U+0ED9' => '໙',
			'U+0EDA' => ' ',
			'U+0EDB' => ' ',
			'U+0EDC' => 'ໜ',
			'U+0EDD' => 'ໝ',
			'U+0EDE' => 'ໞ',
			'U+0EDF' => 'ໟ',
			'U+0EE0' => ' ',
			'U+0EE1' => ' ',
			'U+0EE2' => ' ',
			'U+0EE3' => ' ',
			'U+0EE4' => ' ',
			'U+0EE5' => ' ',
			'U+0EE6' => ' ',
			'U+0EE7' => ' ',
			'U+0EE8' => ' ',
			'U+0EE9' => ' ',
			'U+0EEA' => ' ',
			'U+0EEB' => ' ',
			'U+0EEC' => ' ',
			'U+0EED' => ' ',
			'U+0EEE' => ' ',
			'U+0EEF' => ' ',
			'U+0EF0' => ' ',
			'U+0EF1' => ' ',
			'U+0EF2' => ' ',
			'U+0EF3' => ' ',
			'U+0EF4' => ' ',
			'U+0EF5' => ' ',
			'U+0EF6' => ' ',
			'U+0EF7' => ' ',
			'U+0EF8' => ' ',
			'U+0EF9' => ' ',
			'U+0EFA' => ' ',
			'U+0EFB' => ' ',
			'U+0EFC' => ' ',
			'U+0EFD' => ' ',
			'U+0EFE' => ' ',
			'U+0EFF' => ' ',
			'U+0F00' => 'ༀ',
			'U+0F01' => '༁',
			'U+0F02' => '༂',
			'U+0F03' => '༃',
			'U+0F04' => '༄',
			'U+0F05' => '༅',
			'U+0F06' => '༆',
			'U+0F07' => '༇',
			'U+0F08' => '༈',
			'U+0F09' => '༉',
			'U+0F0A' => '༊',
			'U+0F0B' => '་',
			'U+0F0C' => '༌',
			'U+0F0D' => '།',
			'U+0F0E' => '༎',
			'U+0F0F' => '༏',
			'U+0F10' => '༐',
			'U+0F11' => '༑',
			'U+0F12' => '༒',
			'U+0F13' => '༓',
			'U+0F14' => '༔',
			'U+0F15' => '༕',
			'U+0F16' => '༖',
			'U+0F17' => '༗',
			'U+0F18' => '༘',
			'U+0F19' => ' ༙',
			'U+0F1A' => '༚',
			'U+0F1B' => '༛',
			'U+0F1C' => '༜',
			'U+0F1D' => '༝',
			'U+0F1E' => '༞',
			'U+0F1F' => '༟',
			'U+0F20' => '༠',
			'U+0F21' => '༡',
			'U+0F22' => '༢',
			'U+0F23' => '༣',
			'U+0F24' => '༤',
			'U+0F25' => '༥',
			'U+0F26' => '༦',
			'U+0F27' => '༧',
			'U+0F28' => '༨',
			'U+0F29' => '༩',
			'U+0F2A' => '༪',
			'U+0F2B' => '༫',
			'U+0F2C' => '༬',
			'U+0F2D' => '༭',
			'U+0F2E' => '༮',
			'U+0F2F' => '༯',
			'U+0F30' => '༰',
			'U+0F31' => '༱',
			'U+0F32' => '༲',
			'U+0F33' => '༳',
			'U+0F34' => '༴',
			'U+0F35' => ' ༵',
			'U+0F36' => '༶',
			'U+0F37' => ' ༷',
			'U+0F38' => '༸',
			'U+0F39' => ' ༹',
			'U+0F3A' => '༺',
			'U+0F3B' => '༻',
			'U+0F3C' => '༼',
			'U+0F3D' => '༽',
			'U+0F3E' => '༾',
			'U+0F3F' => '༿',
			'U+0F40' => 'ཀ',
			'U+0F41' => 'ཁ',
			'U+0F42' => 'ག',
			'U+0F43' => 'གྷ',
			'U+0F44' => 'ང',
			'U+0F45' => 'ཅ',
			'U+0F46' => 'ཆ',
			'U+0F47' => 'ཇ',
			'U+0F48' => ' ',
			'U+0F49' => 'ཉ',
			'U+0F4A' => 'ཊ',
			'U+0F4B' => 'ཋ',
			'U+0F4C' => 'ཌ',
			'U+0F4D' => 'ཌྷ',
			'U+0F4E' => 'ཎ',
			'U+0F4F' => 'ཏ',
			'U+0F50' => 'ཐ',
			'U+0F51' => 'ད',
			'U+0F52' => 'དྷ',
			'U+0F53' => 'ན',
			'U+0F54' => 'པ',
			'U+0F55' => 'ཕ',
			'U+0F56' => 'བ',
			'U+0F57' => 'བྷ',
			'U+0F58' => 'མ',
			'U+0F59' => 'ཙ',
			'U+0F5A' => 'ཚ',
			'U+0F5B' => 'ཛ',
			'U+0F5C' => 'ཛྷ',
			'U+0F5D' => 'ཝ',
			'U+0F5E' => 'ཞ',
			'U+0F5F' => 'ཟ',
			'U+0F60' => 'འ',
			'U+0F61' => 'ཡ',
			'U+0F62' => 'ར',
			'U+0F63' => 'ལ',
			'U+0F64' => 'ཤ',
			'U+0F65' => 'ཥ',
			'U+0F66' => 'ས',
			'U+0F67' => 'ཧ',
			'U+0F68' => 'ཨ',
			'U+0F69' => 'ཀྵ',
			'U+0F6A' => 'ཪ',
			'U+0F6B' => 'ཫ',
			'U+0F6C' => 'ཬ',
			'U+0F6D' => ' ',
			'U+0F6E' => ' ',
			'U+0F6F' => ' ',
			'U+0F70' => ' ',
			'U+0F71' => ' ཱ',
			'U+0F72' => ' ི',
			'U+0F73' => ' ཱི',
			'U+0F74' => ' ུ',
			'U+0F75' => ' ཱུ',
			'U+0F76' => ' ྲྀ',
			'U+0F77' => ' ཷ',
			'U+0F78' => ' ླྀ',
			'U+0F79' => ' ཹ',
			'U+0F7A' => ' ེ',
			'U+0F7B' => ' ཻ',
			'U+0F7C' => ' ོ',
			'U+0F7D' => ' ཽ',
			'U+0F7E' => ' ཾ',
			'U+0F7F' => 'ཿ',
			'U+0F80' => ' ྀ',
			'U+0F81' => ' ཱྀ',
			'U+0F82' => ' ྂ',
			'U+0F83' => ' ྃ',
			'U+0F84' => ' ྄',
			'U+0F85' => '྅',
			'U+0F86' => ' ྆',
			'U+0F87' => ' ྇',
			'U+0F88' => 'ྈ',
			'U+0F89' => 'ྉ',
			'U+0F8A' => 'ྊ',
			'U+0F8B' => 'ྋ',
			'U+0F8C' => 'ྌ',
			'U+0F8D' => ' ྍ',
			'U+0F8E' => ' ྎ',
			'U+0F8F' => ' ྏ',
			'U+0F90' => ' ྐ',
			'U+0F91' => ' ྑ',
			'U+0F92' => ' ྒ',
			'U+0F93' => ' ྒྷ',
			'U+0F94' => ' ྔ',
			'U+0F95' => ' ྕ',
			'U+0F96' => ' ྖ',
			'U+0F97' => ' ྗ',
			'U+0F98' => ' ',
			'U+0F99' => ' ྙ',
			'U+0F9A' => ' ྚ',
			'U+0F9B' => ' ྛ',
			'U+0F9C' => ' ྜ',
			'U+0F9D' => ' ྜྷ',
			'U+0F9E' => ' ྞ',
			'U+0F9F' => ' ྟ',
			'U+0FA0' => ' ྠ',
			'U+0FA1' => ' ྡ',
			'U+0FA2' => ' ྡྷ',
			'U+0FA3' => ' ྣ',
			'U+0FA4' => ' ྤ',
			'U+0FA5' => ' ྥ',
			'U+0FA6' => ' ྦ',
			'U+0FA7' => ' ྦྷ',
			'U+0FA8' => ' ྨ',
			'U+0FA9' => ' ྩ',
			'U+0FAA' => ' ྪ',
			'U+0FAB' => ' ྫ',
			'U+0FAC' => ' ྫྷ',
			'U+0FAD' => ' ྭ',
			'U+0FAE' => ' ྮ',
			'U+0FAF' => ' ྯ',
			'U+0FB0' => ' ྰ',
			'U+0FB1' => ' ྱ',
			'U+0FB2' => ' ྲ',
			'U+0FB3' => ' ླ',
			'U+0FB4' => ' ྴ',
			'U+0FB5' => ' ྵ',
			'U+0FB6' => ' ྶ',
			'U+0FB7' => ' ྷ',
			'U+0FB8' => ' ྸ',
			'U+0FB9' => ' ྐྵ',
			'U+0FBA' => ' ྺ',
			'U+0FBB' => ' ྻ',
			'U+0FBC' => ' ྼ',
			'U+0FBD' => ' ',
			'U+0FBE' => '྾',
			'U+0FBF' => '྿',
			'U+0FC0' => '࿀',
			'U+0FC1' => '࿁',
			'U+0FC2' => '࿂',
			'U+0FC3' => '࿃',
			'U+0FC4' => '࿄',
			'U+0FC5' => '࿅',
			'U+0FC6' => ' ࿆',
			'U+0FC7' => '࿇',
			'U+0FC8' => '࿈',
			'U+0FC9' => '࿉',
			'U+0FCA' => '࿊',
			'U+0FCB' => '࿋',
			'U+0FCC' => '࿌',
			'U+0FCD' => ' ',
			'U+0FCE' => '࿎',
			'U+0FCF' => '࿏',
			'U+0FD0' => '࿐',
			'U+0FD1' => '࿑',
			'U+0FD2' => '࿒',
			'U+0FD3' => '࿓',
			'U+0FD4' => '࿔',
			'U+0FD5' => '࿕',
			'U+0FD6' => '࿖',
			'U+0FD7' => '࿗',
			'U+0FD8' => '࿘',
			'U+0FD9' => '࿙',
			'U+0FDA' => '࿚',
			'U+0FDB' => ' ',
			'U+0FDC' => ' ',
			'U+0FDD' => ' ',
			'U+0FDE' => ' ',
			'U+0FDF' => ' '
		];
	}
	
    private function ascii($value, $language = 'en')
    {
        $lng = $this->lng_chars($language);

        if(!$this->is_null($lng)) {
            $value = str_replace($lng[0], $lng[1], $value);
        }

        foreach($this->chars() as $key => $val) {
            $value = str_replace($val, $key, $value);
        }

        return preg_replace('/[^\x20-\x7E]/u', '', $value);
    }

    private function lng_chars($language)
    {
		$lng = [
			'bg' => [
				['х', 'Х', 'щ', 'Щ', 'ъ', 'Ъ', 'ь', 'Ь'],
				['h', 'H', 'sht', 'SHT', 'a', 'А', 'y', 'Y'],
			],
			'de' => [
				['ä',  'ö',  'ü',  'Ä',  'Ö',  'Ü'],
				['ae', 'oe', 'ue', 'AE', 'OE', 'UE'],
			],
		];

        return $lng[$language] ?? null;
    }

    private function chars()
    {

        return $chars = [
            '0'    => ['°', '₀', '۰', '０'],
            '1'    => ['¹', '₁', '۱', '１'],
            '2'    => ['²', '₂', '۲', '２'],
            '3'    => ['³', '₃', '۳', '３'],
            '4'    => ['⁴', '₄', '۴', '٤', '４'],
            '5'    => ['⁵', '₅', '۵', '٥', '５'],
            '6'    => ['⁶', '₆', '۶', '٦', '６'],
            '7'    => ['⁷', '₇', '۷', '７'],
            '8'    => ['⁸', '₈', '۸', '８'],
            '9'    => ['⁹', '₉', '۹', '９'],
            'a'    => ['à', 'á', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'ā', 'ą', 'å', 'α', 'ά', 'ἀ', 'ἁ', 'ἂ', 'ἃ', 'ἄ', 'ἅ', 'ἆ', 'ἇ', 'ᾀ', 'ᾁ', 'ᾂ', 'ᾃ', 'ᾄ', 'ᾅ', 'ᾆ', 'ᾇ', 'ὰ', 'ά', 'ᾰ', 'ᾱ', 'ᾲ', 'ᾳ', 'ᾴ', 'ᾶ', 'ᾷ', 'а', 'أ', 'အ', 'ာ', 'ါ', 'ǻ', 'ǎ', 'ª', 'ა', 'अ', 'ا', 'ａ', 'ä'],
            'b'    => ['б', 'β', 'ب', 'ဗ', 'ბ', 'ｂ'],
            'c'    => ['ç', 'ć', 'č', 'ĉ', 'ċ', 'ｃ'],
            'd'    => ['ď', 'ð', 'đ', 'ƌ', 'ȡ', 'ɖ', 'ɗ', 'ᵭ', 'ᶁ', 'ᶑ', 'д', 'δ', 'د', 'ض', 'ဍ', 'ဒ', 'დ', 'ｄ'],
            'e'    => ['é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'ë', 'ē', 'ę', 'ě', 'ĕ', 'ė', 'ε', 'έ', 'ἐ', 'ἑ', 'ἒ', 'ἓ', 'ἔ', 'ἕ', 'ὲ', 'έ', 'е', 'ё', 'э', 'є', 'ə', 'ဧ', 'ေ', 'ဲ', 'ე', 'ए', 'إ', 'ئ', 'ｅ'],
            'f'    => ['ф', 'φ', 'ف', 'ƒ', 'ფ', 'ｆ'],
            'g'    => ['ĝ', 'ğ', 'ġ', 'ģ', 'г', 'ґ', 'γ', 'ဂ', 'გ', 'گ', 'ｇ'],
            'h'    => ['ĥ', 'ħ', 'η', 'ή', 'ح', 'ه', 'ဟ', 'ှ', 'ჰ', 'ｈ'],
            'i'    => ['í', 'ì', 'ỉ', 'ĩ', 'ị', 'î', 'ï', 'ī', 'ĭ', 'į', 'ı', 'ι', 'ί', 'ϊ', 'ΐ', 'ἰ', 'ἱ', 'ἲ', 'ἳ', 'ἴ', 'ἵ', 'ἶ', 'ἷ', 'ὶ', 'ί', 'ῐ', 'ῑ', 'ῒ', 'ΐ', 'ῖ', 'ῗ', 'і', 'ї', 'и', 'ဣ', 'ိ', 'ီ', 'ည်', 'ǐ', 'ი', 'इ', 'ی', 'ｉ'],
            'j'    => ['ĵ', 'ј', 'Ј', 'ჯ', 'ج', 'ｊ'],
            'k'    => ['ķ', 'ĸ', 'к', 'κ', 'Ķ', 'ق', 'ك', 'က', 'კ', 'ქ', 'ک', 'ｋ'],
            'l'    => ['ł', 'ľ', 'ĺ', 'ļ', 'ŀ', 'л', 'λ', 'ل', 'လ', 'ლ', 'ｌ'],
            'm'    => ['м', 'μ', 'م', 'မ', 'მ', 'ｍ'],
            'n'    => ['ñ', 'ń', 'ň', 'ņ', 'ŉ', 'ŋ', 'ν', 'н', 'ن', 'န', 'ნ', 'ｎ'],
            'o'    => ['ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ø', 'ō', 'ő', 'ŏ', 'ο', 'ὀ', 'ὁ', 'ὂ', 'ὃ', 'ὄ', 'ὅ', 'ὸ', 'ό', 'о', 'و', 'θ', 'ို', 'ǒ', 'ǿ', 'º', 'ო', 'ओ', 'ｏ', 'ö'],
            'p'    => ['п', 'π', 'ပ', 'პ', 'پ', 'ｐ'],
            'q'    => ['ყ', 'ｑ'],
            'r'    => ['ŕ', 'ř', 'ŗ', 'р', 'ρ', 'ر', 'რ', 'ｒ'],
            's'    => ['ś', 'š', 'ş', 'с', 'σ', 'ș', 'ς', 'س', 'ص', 'စ', 'ſ', 'ს', 'ｓ'],
            't'    => ['ť', 'ţ', 'т', 'τ', 'ț', 'ت', 'ط', 'ဋ', 'တ', 'ŧ', 'თ', 'ტ', 'ｔ'],
            'u'    => ['ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'û', 'ū', 'ů', 'ű', 'ŭ', 'ų', 'µ', 'у', 'ဉ', 'ု', 'ူ', 'ǔ', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'უ', 'उ', 'ｕ', 'ў', 'ü'],
            'v'    => ['в', 'ვ', 'ϐ', 'ｖ'],
            'w'    => ['ŵ', 'ω', 'ώ', 'ဝ', 'ွ', 'ｗ'],
            'x'    => ['χ', 'ξ', 'ｘ'],
            'y'    => ['ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'ÿ', 'ŷ', 'й', 'ы', 'υ', 'ϋ', 'ύ', 'ΰ', 'ي', 'ယ', 'ｙ'],
            'z'    => ['ź', 'ž', 'ż', 'з', 'ζ', 'ز', 'ဇ', 'ზ', 'ｚ'],
            'aa'   => ['ع', 'आ', 'آ'],
            'ae'   => ['æ', 'ǽ'],
            'ai'   => ['ऐ'],
            'ch'   => ['ч', 'ჩ', 'ჭ', 'چ'],
            'dj'   => ['ђ', 'đ'],
            'dz'   => ['џ', 'ძ'],
            'ei'   => ['ऍ'],
            'gh'   => ['غ', 'ღ'],
            'ii'   => ['ई'],
            'ij'   => ['ĳ'],
            'kh'   => ['х', 'خ', 'ხ'],
            'lj'   => ['љ'],
            'nj'   => ['њ'],
            'oe'   => ['ö', 'œ', 'ؤ'],
            'oi'   => ['ऑ'],
            'oii'  => ['ऒ'],
            'ps'   => ['ψ'],
            'sh'   => ['ш', 'შ', 'ش'],
            'shch' => ['щ'],
            'ss'   => ['ß'],
            'sx'   => ['ŝ'],
            'th'   => ['þ', 'ϑ', 'ث', 'ذ', 'ظ'],
            'ts'   => ['ц', 'ც', 'წ'],
            'ue'   => ['ü'],
            'uu'   => ['ऊ'],
            'ya'   => ['я'],
            'yu'   => ['ю'],
            'zh'   => ['ж', 'ჟ', 'ژ'],
            '(c)'  => ['©'],
            'A'    => ['Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Å', 'Ā', 'Ą', 'Α', 'Ά', 'Ἀ', 'Ἁ', 'Ἂ', 'Ἃ', 'Ἄ', 'Ἅ', 'Ἆ', 'Ἇ', 'ᾈ', 'ᾉ', 'ᾊ', 'ᾋ', 'ᾌ', 'ᾍ', 'ᾎ', 'ᾏ', 'Ᾰ', 'Ᾱ', 'Ὰ', 'Ά', 'ᾼ', 'А', 'Ǻ', 'Ǎ', 'Ａ', 'Ä'],
            'B'    => ['Б', 'Β', 'ब', 'Ｂ'],
            'C'    => ['Ç', 'Ć', 'Č', 'Ĉ', 'Ċ', 'Ｃ'],
            'D'    => ['Ď', 'Ð', 'Đ', 'Ɖ', 'Ɗ', 'Ƌ', 'ᴅ', 'ᴆ', 'Д', 'Δ', 'Ｄ'],
            'E'    => ['É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Ë', 'Ē', 'Ę', 'Ě', 'Ĕ', 'Ė', 'Ε', 'Έ', 'Ἐ', 'Ἑ', 'Ἒ', 'Ἓ', 'Ἔ', 'Ἕ', 'Έ', 'Ὲ', 'Е', 'Ё', 'Э', 'Є', 'Ə', 'Ｅ'],
            'F'    => ['Ф', 'Φ', 'Ｆ'],
            'G'    => ['Ğ', 'Ġ', 'Ģ', 'Г', 'Ґ', 'Γ', 'Ｇ'],
            'H'    => ['Η', 'Ή', 'Ħ', 'Ｈ'],
            'I'    => ['Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Î', 'Ï', 'Ī', 'Ĭ', 'Į', 'İ', 'Ι', 'Ί', 'Ϊ', 'Ἰ', 'Ἱ', 'Ἳ', 'Ἴ', 'Ἵ', 'Ἶ', 'Ἷ', 'Ῐ', 'Ῑ', 'Ὶ', 'Ί', 'И', 'І', 'Ї', 'Ǐ', 'ϒ', 'Ｉ'],
            'J'    => ['Ｊ'],
            'K'    => ['К', 'Κ', 'Ｋ'],
            'L'    => ['Ĺ', 'Ł', 'Л', 'Λ', 'Ļ', 'Ľ', 'Ŀ', 'ल', 'Ｌ'],
            'M'    => ['М', 'Μ', 'Ｍ'],
            'N'    => ['Ń', 'Ñ', 'Ň', 'Ņ', 'Ŋ', 'Н', 'Ν', 'Ｎ'],
            'O'    => ['Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ø', 'Ō', 'Ő', 'Ŏ', 'Ο', 'Ό', 'Ὀ', 'Ὁ', 'Ὂ', 'Ὃ', 'Ὄ', 'Ὅ', 'Ὸ', 'Ό', 'О', 'Θ', 'Ө', 'Ǒ', 'Ǿ', 'Ｏ', 'Ö'],
            'P'    => ['П', 'Π', 'Ｐ'],
            'Q'    => ['Ｑ'],
            'R'    => ['Ř', 'Ŕ', 'Р', 'Ρ', 'Ŗ', 'Ｒ'],
            'S'    => ['Ş', 'Ŝ', 'Ș', 'Š', 'Ś', 'С', 'Σ', 'Ｓ'],
            'T'    => ['Ť', 'Ţ', 'Ŧ', 'Ț', 'Т', 'Τ', 'Ｔ'],
            'U'    => ['Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Û', 'Ū', 'Ů', 'Ű', 'Ŭ', 'Ų', 'У', 'Ǔ', 'Ǖ', 'Ǘ', 'Ǚ', 'Ǜ', 'Ｕ', 'Ў', 'Ü'],
            'V'    => ['В', 'Ｖ'],
            'W'    => ['Ω', 'Ώ', 'Ŵ', 'Ｗ'],
            'X'    => ['Χ', 'Ξ', 'Ｘ'],
            'Y'    => ['Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Ÿ', 'Ῠ', 'Ῡ', 'Ὺ', 'Ύ', 'Ы', 'Й', 'Υ', 'Ϋ', 'Ŷ', 'Ｙ'],
            'Z'    => ['Ź', 'Ž', 'Ż', 'З', 'Ζ', 'Ｚ'],
            'AE'   => ['Æ', 'Ǽ'],
            'Ch'   => ['Ч'],
            'Dj'   => ['Ђ'],
            'Dz'   => ['Џ'],
            'Gx'   => ['Ĝ'],
            'Hx'   => ['Ĥ'],
            'Ij'   => ['Ĳ'],
            'Jx'   => ['Ĵ'],
            'Kh'   => ['Х'],
            'Lj'   => ['Љ'],
            'Nj'   => ['Њ'],
            'Oe'   => ['Œ'],
            'Ps'   => ['Ψ'],
            'Sh'   => ['Ш'],
            'Shch' => ['Щ'],
            'Ss'   => ['ẞ'],
            'Th'   => ['Þ'],
            'Ts'   => ['Ц'],
            'Ya'   => ['Я'],
            'Yu'   => ['Ю'],
            'Zh'   => ['Ж'],
            ' '    => ["\xC2\xA0", "\xE2\x80\x80", "\xE2\x80\x81", "\xE2\x80\x82", "\xE2\x80\x83", "\xE2\x80\x84", "\xE2\x80\x85", "\xE2\x80\x86", "\xE2\x80\x87", "\xE2\x80\x88", "\xE2\x80\x89", "\xE2\x80\x8A", "\xE2\x80\xAF", "\xE2\x81\x9F", "\xE3\x80\x80", "\xEF\xBE\xA0"],
        ];
    }

	private function get_class_skeleton() {

		$c = [];

		$class = new ReflectionClass($this);

		$c['namespace']	= $class->getNamespaceName();
		$c['name']		= $class->getName();
		$c['methods']	= [];

		$i = 0;
		foreach($class->getMethods() as $m) {

			$c['methods'][$i]['parameters'] = '';

			$rm = new ReflectionMethod($this, $m->getName());

			foreach($rm->getParameters() as $key => $p) {

				if($c['methods'][$i]['parameters']) {
					$c['methods'][$i]['parameters'].=",\n\t\t\t";
				}

				$c['methods'][$i]['parameters'].='[';

				if($p->isOptional()) {
					$c['methods'][$i]['parameters'].= '\'type\' => \''.$this->call('get_type', [$p->getDefaultValue()]).'\', \'name\' => \''.$p->name.'\', \'optional\' => 1, \'value\' => '.$this->call('fix_type', [$p->getDefaultValue()]);
				} else {
					$c['methods'][$i]['parameters'].= '\'type\' => \'unknown\', \'name\' => \''.$p->name.'\', \'optional\' => 0';
				}

				$c['methods'][$i]['parameters'].=']';

			}

			if($c['methods'][$i]['parameters']) {
				$c['methods'][$i]['parameters'] = "[\n\t\t\t".$c['methods'][$i]['parameters']."\n\t\t]";
			}

			$c['methods'][$i]['body'] = '';

	        if(!$m->isAbstract()) {

				$file = $rm->getFileName();

		        if($file) {

					$start	= $rm->getStartLine() - 1;
					$end	= $rm->getEndLine() - $start + 1;

			        $lines = file($file, FILE_IGNORE_NEW_LINES);
			        $lines = array_slice($lines, $start, $end, true);

			        $lines = implode("\n", $lines);

			        $obrace = strpos($lines, '{');
			        $cbrace = strrpos($lines, '}');

			        // preg_match_all('/({((?:[^{}]*|(?1))*)})/x', body, matches);

					if($body = $this->substring($lines, $obrace + 1, $cbrace - $obrace - 1)) {
						$body = $this->trim($this->call('trimright', [$body]), "\n\r");
						if($this->write_file($this->storage('code.txt'), '<?php '.$body.' ?>')) {
							$body = $this->file_contents($this->storage('code.txt'));
							$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
							$body = $parser->parse($body);
							//print_r(json_decode(json_encode($body), true)); exit;
							$prettyPrinter = new PrettyPrinter\Standard;
							$c['methods'][$i]['body'] = substr($prettyPrinter->prettyPrintFile($body), 6);
						}
					}


		        }

	        }

			$c['methods'][$i]['visibility'] = '';

			if($m->isPublic()) {
				$c['methods'][$i]['visibility'] = 'public';
			}
			elseif($m->isProtected()) {
				$c['methods'][$i]['visibility'] = 'protected';
			} else {
				if($m->isPrivate()) {
					$c['methods'][$i]['visibility'] = 'private';
				}
			}

			$c['methods'][$i]['name'] = $m->getName();
			$c['methods'][$i]['is_static'] = $m->isStatic();

			$i++;
		}
		return $c;
	}

	private function export() {

		$class = $this->get_class_skeleton();

		$string = "";

		foreach($class['methods'] as $m) {

			$string.="\t";
			$string.='$this->tag(\'function\');';
			$string.="\n\t\t";
			$string.='$this->attr(\'name\', \''.$m['name'].'\');';

			if($m['visibility']) {
				$string.="\n\t\t";
				$string.='$this->attr(\'visibility\', \''.$m['visibility'].'\');';
			}


			if($m['parameters']) {
				$string.="\n\t\t";
				$string.='$this->attr(\'arguments\', '.$m['parameters'].');';
			}
			if($m['body']) {
				//$string.="\n\t\t\n\t\t";
				//$string.="\$body = '";
				$string.="\n\t\t".$this->trim($m['body']);
				//$string.="\n\t\t';";
			} else {
				$string.="\n\t\t// -------------------------------------- \n\t";
			}

			$string.="\n\t";
			$string.='$this->tag();';
			$string.="\n";
			$string.="\n";

		}
		if($this->make_file_and_folder_force($this->storage('application.php'), "<?php\n\n".$string."\n")) {
			return 1;
		}
		return 0;
	}
	private function fix_type($value) {
		switch(gettype($value)) {
			case 'boolean':
				$value = $value ? 'true' : 'false';
			break;
			case 'integer':
			case 'double':
				//
			break;
			case 'string':
				$value = "'".$this->escapeString($value)."'";
			break;
			case 'NULL':
				$value = 'null';
			break;
			case 'array':
				if($this->isEmpty($value)) {
					$value = '[]';
				} else { 
					$value = $this->build([$value]);
				}
			break;
			default:
				
			break;
		}
		return $value;
	}


}

