<?php

namespace Brosta\Builders;

class OS_Php
{

	public function _build($data, $level = 0) {
		$contents = '';

		foreach($data as $item) {
			if(!os_is_empty($item['items'])) {
				$item['nested'] = $this->_build($item['items'], $item['level']);
			}

			$item['lower_tag'] = os_lower($item['tag']);
			$item['new_line_after'] = os_new_line();
			$item['new_line_before'] = os_new_line();
			$item['space'] = os_space_like_tab(($level + $item['cspace']) + $item['start_code_space_level']);

			switch($item['lower_tag'])
			{
				case'untaged':
					if(isset($item['fake_line'])) {
						$item['lower_tag'] = '';
						$item['space'] = '';
						$item['tag_before'] = '';
						$item['tag_after'] = '';
					}
				break;
				case'namespace':
					$item['tag_before'].= 'namespace '.os_class_separator_fix($item['attr']['name']).';';
					$item['tag_after'].= '';
				break;
				case'use':
					$item['tag_before'].= 'use '.os_class_separator_fix($item['attr']['name']).';';
					$item['tag_after'].= '';
				break;
				case'property':
					if(isset($item['attr']['visibility'])) {
						$item['tag_before'].=$item['attr']['visibility'].' ';
					}
					if(isset($item['attr']['is_static']) && $item['attr']['is_static']) {
						$item['tag_before'].='static ';
					}
					$item['tag_before'].= '$'.$item['attr']['name'];
					if(isset($item['attr']['value'])) {
						$item['tag_before'].=' = '.os_fix_type($item['attr']['value']);
					}
					$item['tag_before'].=';';
				break;
				case'class':
					$item['tag_before'].= 'class '.$item['attr']['name'].' {';
					$item['tag_after'] = '}';
				break;
				case'function':
					if(isset($item['attr']['visibility'])) {
						$item['tag_before'].=$item['attr']['visibility'].' ';
					}
					if(isset($item['attr']['static']) && $item['attr']['static']) {
						$item['tag_before'].='static ';
					}
					$item['tag_before'].= 'function '.$item['attr']['name'];
					$args ='';
					if(isset($item['attr']['arguments'])) {
						foreach($item['attr']['arguments'] as $arg) {
							if($args) {
								$args.=', ';
							}
							if($arg['type']) {
								$args.=$arg['type'].' ';
							}
							if(isset($arg['bref']) && $arg['bref']) {
								$args.='&';
							}
							$args.='$'.$arg['name'];
							if(os_key_in_array('value', $arg)) {
								$args.=' = '.os_fix_type($arg['value']);
							}
						}
					}
					$item['tag_before'].='('.$args.') {';
					if(isset($item['attr']['body'])) {
						$item['tag_before'].=$item['attr']['body'];
					}
					$item['tag_after'] = '}';
				break;
				default:
					$item['tag_before'] = $item['open_tag'].$item['tag'].$item['attr_string'].$item['close_tag'];
					$item['tag_after'] = $item['open_tag'].$item['tag'].$item['close_tag'];
				break;
			}
			$contents.=os_get_builded_text($item, $level);
		}
		return $contents;
	}

}