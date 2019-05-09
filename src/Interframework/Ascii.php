<?php

namespace Brosta;


class Ascii {


	public function getControlChars($code = null, $type = 'symbol') {
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

	public function getSymbolChars($code = null, $type = 'symbol') {
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

	public function getAlphaFromLowerToUpper($code = null, $type = 'symbol') {
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

	public function getAlphaFromUpperToLower($code = null, $type = 'symbol') {
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

	public function getAlphaLower($code = null, $type = 'symbol') {
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

	public function getAlphaUpper($code = null, $type = 'symbol') {
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

	public function getNumbers($code = null, $type = 'symbol') {
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

}