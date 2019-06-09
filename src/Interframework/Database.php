<?php

namespace Brosta;

use Brosta\App;

class Database
{

	private $structure = [];

	private $database;

	private $table;

	private $select;

	private $where = [];

	private $data = [];

	private $results = [];

	public function __construct(string $table) {
		$this->database_name = App::config('database.active');
		$this->structure = App::getDatabaseConfig($this->database_name);
		$this->table = $table;
	}

	public function select($select) {
		$this->select = _is_array($select) ? $select : func_get_args();
		return $this;
	}

	public function where($column, $operator = null, $value = null) {

		if($value === null) {
			$value = $operator;
			$operator = '=';
		}

		$this->where[] = [
			'column' => $column,
			'operator' => $operator,
			'value' => $value,
		];
		return $this;
	}

	public function save() {
		
	}

	protected function getWhere($data) {
		for($i = 0; $i < _count($this->where); $i++) {
			if(_key_in_array($this->where[$i]['column'], $data)) {
				if($this->where[$i]['operator'] === '=') {
					if($this->where[$i]['value'] == $data[$this->where[$i]['column']]) {
						
					} else {
						return [];
					}
				}
			}
		}
		return $data;
	}

	public function get_table_data($env) {
		return _get_file_contents(database_path($env.'/data.txt'));
	}

	public function get_table_columns($table) {
		$data = $this->get_table_data($table);
		$line = _explode_lines($data)[0];
		$re = [];
		$normalize = _explode('|', $line);
		for($i=0;$i< _count($normalize);$i++) {
			$item = _explode(':', $normalize[$i]);
			$g[$i] = [
				'column' => $item[0],
				'type' => $item[1],
				'data' => [],
			];
		}
		return 
	}

	public function get() {

		if(_in_array($this->table, $this->structure['tables'])) {
			$data = $this->get_table_data($this->database_name.'/'.$this->table);
			$lines = _explode_lines($data);
			$g = [];
			$normalize = _explode('|', $lines[0]);
			for($i=0;$i< _count($normalize);$i++) {
				$item = _explode(':', $normalize[$i]);
				$g[$i] = [
					'column' => $item[0],
					'type' => $item[1],
					'data' => [],
				];
			}

			$array = [];
			for($line = 1; $line < _count($lines) - 1; $line++) {
				$tmp = [];
				$item = _explode('|', $lines[$line]);
				for($i = 0; $i < _count($item); $i++) {
					if($g[$i]['type'] == 'int') {
						$item[$i] = (int)$item[$i];
					} else if($g[$i]['type'] == 'text') {
						$item[$i] = (string)$item[$i];
					}
					$tmp[$g[$i]['column']] = $item[$i];
				}
				if(!_is_empty($this->where)) {
					$tmp = $this->getWhere($tmp);
				}
				if(!_is_empty($tmp)) {
					$array[] = $tmp;
				}
			}
		}

		return $array;
	}


}