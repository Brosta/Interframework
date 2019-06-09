<?php

namespace Brosta;

use Brosta\App;

class Growth
{
	private $html;

	public function contents($file) {
		$file	= App::_slash_to_dot($file);
		$file	= App::_to_base($file);
		$method	= App::_fix_prefix_app($file['base']);
		$inside = App::_implode('/', $file['keys']);
		$inside = App::_lower($inside);
		$editor = App::_new('Brosta\Signal');
		$editor->set_start_code_space_level(0);
		return call_user_func_array([$this, $method], [$editor, $inside]);
	}

	private function _common($editor, $name) {
		switch($name) {
			case'signal':
				$editor->text('<?php

$app = new Brosta\App;

$app->construct(dirname(__DIR__), [
	\'get\' => $_GET,
	\'post\' => $_POST,
	\'files\' => $_FILES,
	\'cookie\' => $_COOKIE,
	\'server\' => $_SERVER,
	\'extra\' => [],
]);

return $app;');

			break;
		}
		return $editor->get_ready_document();
	}

	private function _views($editor, $name) {
		switch($name) {
			case'default/index':
				$editor->text('<?php

$html->text(\'Welcome\');');

			break;
		}
		return $editor->get_ready_document();
	}

	private function _public($editor, $name) {
		switch($name) {
			case'index':
				$editor->text('<?php

// Brosta Interframework

require(__DIR__.\'/../vendor/autoload.php\');
require(__DIR__.\'/../vendor/brosta/interframework/src/Interframework/helpers.php\');

$signal = require_once(__DIR__.\'/../common/signal.php\');');

			break;
		}
		return $editor->get_ready_document();
	}


}