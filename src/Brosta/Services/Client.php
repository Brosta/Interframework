<?php


class Brosta_Client
{

	const API_BASIS_DIRECTORY = 'http://localhost/server';

	private $config;

	private $document = [];

	private $protect = [
		'as' => 'string',
		'key' => null,
		'count' => 0,
		'level' => 0,
		'space' => '',
		'newline' => "\n",
		'tabspace' => "\t",
		'document' => '',
		'notnewline' => "",
		'spacecount' => 0,
		'opentagsymbol' => '[',
		'closetagsymbol' => ']',
		'key_separator_value' => ' => ',
		'row_separator' => ','
	];

	public function __construct(array $config = []) {

		$this->config = array_merge([
			'email' => 'john.stamoutsos@gmail.com',
			'password' => '1!Mypass',
			'basis_directory' => self::API_BASIS_DIRECTORY,
			'algorithms' => [],
			'desktop_file' => '/projection/interfaces/default/_common/desktop.php'
        ], $config);

        if(isset($this->config['items'])) {
        	//echo $this->remix($this->config['items'], $this->protect);
        }
require_once (realpath(__DIR__.'/../../../../../../').'/public/metrall/public/index.php');

$this->protect = array_merge($this->protect, [
	'without_numeric_keys' => 0,
	'key_with_quotation_mark' => 0,
	'value_with_quotation_mark' => 0
]);

echo $this->remix([$document->arrayContents], $this->protect);
        if(isset($this->config['root']) && $this->config['load_from_root'] == 1) {
        	require_once($this->config['root'].$this->config['desktop_file']);
        }

	}

	public function remix(array $remix, array $relase = null) {

		$protect = $this->protect;

		$protect['key']			= $relase['key'];
		$protect['count']		= $relase['count'];
		$protect['level']		= $relase['level'];
		$protect['space']		= $relase['space'];
		$protect['spacecount']	= $relase['spacecount'];
		$protect['without_numeric_keys'] = $relase['without_numeric_keys'];
		$protect['key_with_quotation_mark'] = $relase['key_with_quotation_mark'];
		$protect['value_with_quotation_mark'] = $relase['value_with_quotation_mark'];

        $protect['notnewline'] = $protect['newline'];

        for($j=0; $j < $protect['spacecount']; $j++) {
        	$protect['space'].=$protect['tabspace'];
        }

		$protect['last_count'] = 0;

		foreach($remix as $key => $x) {

			$protect['key'] = $key;
			$protect['keytype'] = gettype($key);
			$protect['value'] = $x;
			$protect['valuetype'] = gettype($protect['value']);

			if($protect['valuetype'] == 'array') {
				$protect['nested'] = $this->remix($protect['value'], $protect);
			}

			if($protect['keytype'] == 'integer') {
				if(isset($protect['without_numeric_keys'])) {
					if($protect['without_numeric_keys'] == 1) {
						$protect['key'] = '';
						$protect['key_separator_value'] = '';
					}
				}
			}

			if(isset($protect['key_with_quotation_mark'])) {
				if($protect['key_with_quotation_mark'] == 1) {
					$protect['key'] = "'".$protect['key']."'";
				}
			}

			if(isset($protect['value_with_quotation_mark'])) {
				if($protect['value_with_quotation_mark'] == 1) {
					if($protect['valuetype'] !== 'array') {
						$protect['value'] = "'".$protect['value']."'";
					}
				}
			}

			if($protect['valuetype'] == 'array') {
				$protect['count'] = count($protect['value']);

				$protect['level'] = $protect['level'] + 1;

				$s="";
				$ss="";

				if(!$protect['value']) {
					$protect['notnewline'] = '';
				}
				for($j=0; $j < $protect['level']; $j++) { 
					if($j>0) {
						$s.= $protect['tabspace'];
					}
					$ss.= $protect['tabspace'];
				}
				if($protect['count'] > 1 && $protect['last_count'] != $protect['count'] - 1) {
					$protect['row_separator'] =','.$protect['newline']; 
				} else {
					$protect['row_separator'] = $protect['newline'];
				}
				$protect['nested'] = $this->remix($protect['value'], $protect);
				if(!$protect['nested']) {
					$protect['document'].=$protect['space'];
					$protect['document'].=$s;
					$protect['document'].=$protect['key'];
					$protect['document'].=$protect['key_separator_value'];
					$protect['document'].=$protect['opentagsymbol'];
					$protect['document'].=$protect['closetagsymbol'];
					$protect['document'].=$protect['row_separator'];
				} else {
					$protect['document'].=$protect['space'];
					$protect['document'].=$s;
					$protect['document'].=$protect['key'];
					$protect['document'].=$protect['key_separator_value'];
					$protect['document'].=$protect['opentagsymbol'];
					$protect['document'].=$protect['newline'];
					$protect['document'].=$protect['nested'];
					$protect['document'].=$protect['space'];
					$protect['document'].=$s;
					$protect['document'].=$protect['closetagsymbol'];
					$protect['document'].=$protect['row_separator'];
				}
				$protect['level'] = $protect['level'] - 1;
			} else {

				$s="";
				$ss="";
				for($j=0; $j < $protect['level']; $j++) { 
					if($j>0) {
						$s.=$protect['tabspace'];
					}
					$ss.=$protect['tabspace'];
				}
				switch($protect['valuetype']) {
					case 'boolean':
						$protect['value'] = $protect['value'] ? 'true' : 'false';
		            break;
		            case 'integer':
		            case 'double':
		            	$protect['value'] = $protect['value'];
		            break;
		            case 'string':
		            	$protect['value'] = "'".strtr($protect['value'], array(
				            "\r" => '\r',
				            "\n" => '\n',
				            "\t" => '\t',
				            "'" => "\\'",
				            '"' => '\"',
				            '\\' => '\\\\'
				        ))."'";
		            break;
		            case 'NULL':
		            	$protect['value'] = 'null';
		            break;
		        }
				if($protect['count'] > 1 && $protect['last_count'] != $protect['count'] - 1) {
					$protect['row_separator'] =','.$protect['newline']; 
				} else {
					$protect['row_separator'] =$protect['newline'];
				}

				$protect['key'].=$protect['key_separator_value'];
				$protect['document'].=$protect['space'].$ss.$protect['key'].$protect['value'].$protect['row_separator'];
			}
			$protect['last_count'] = $protect['last_count'] + 1;
		}
		if($protect['as'] == 'string') {
			return $protect['document'];
		}
		if($protect['as'] == 'array') {
			return $protect['value'];
		}
    }

	public function __call($tag, $attr) {
		echo $this->remix([0 => $tag, 1 => $attr]);
		return $this;
	}


}