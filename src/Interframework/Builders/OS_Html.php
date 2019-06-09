<?php

namespace Brosta\Builders;

class OS_Html
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

			$item['attr_string'] = "";

			if(!os_is_empty($item['attr'])) {
				foreach($item['attr'] as $attr_name => $attr_value) {

					if(os_is_array($attr_value) || os_is_object($attr_value)) {
						$attr_value = '';
						$item['attr_string'].=$attr_name.'="AAAA"';
					} else {
						if(os_is_null($attr_value) || os_trim($attr_value) == '') {
							$attr_value = '';
						}
					}

					if($attr_value) {
						if($attr_name == 'style') {
							if(!os_last_in(os_trim($attr_value), ';')) {
								$attr_value.=';';
							}
							if(os_key_in_array('class', $item['attr'])) {
								//$this->_style_to_file($attr_value, $item['attr']['class']);
							}
						}
					}

					if($item['attr_string']) {
						$item['attr_string'].=' ';
					}

					if(os_is_numeric($attr_name)) {
						$item['attr_string'].=$attr_value;
					} else {
						if(os_lower($item['tag']) == 'doctype') {
							if(os_trim($attr_value) == '') {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'=""';
								}
							} else {
								if($attr_name == 'html') {
									$item['attr_string'].=$attr_name;
								} else {
									$item['attr_string'].=$attr_name.'="'.$attr_value.'"';
								}
							}
						} else {
							$item['attr_string'].=$attr_name.'="'.$attr_value.'"';
						}
					}
				}
			}

			$item['attr_string'] = os_trim($item['attr_string']);
			$item['attr_string'] = $item['attr_string'] ? ' '.$item['attr_string'] : '';

			switch($item['lower_tag'])
			{
				case'_':
					$item['open_tag'] = "";
					$item['close_tag'] = "";
					$item['tag_before'] = "";
					$item['tag_after'] = "";
				break;
				case'untaged':

				break;
				case'doctype':
					$item['tag_before'] = $item['open_tag'].'!'.os_upper($item['tag']).$item['attr_string'].$item['close_tag'];
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
			$contents.=os_get_builded_text($item, $level);
		}
		return $contents;
	}

}