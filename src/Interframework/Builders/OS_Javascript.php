<?php

namespace Brosta\Builders;

class OS_Javascript
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
						$item['tag_before'] = '';
						$item['space'] = '';
						$item['tag_after'] = '';
					}
				break;
				case'function':
					if(isset($item['attr']['assigned'])) {
						if(isset($item['attr']['instance'])) {
							$item['tag_before'].=$item['attr']['instance'].'.'.(os_is_array($item['attr']['name']) ? os_implode('.', $item['attr']['name']) : $item['attr']['name']);
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

	public function _build($data, $level = 0) {
		$contents.=os_get_builded_text($item, $level);
		return $contents;
	}

}