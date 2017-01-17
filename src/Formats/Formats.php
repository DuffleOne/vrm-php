<?php

namespace Duffleman\VRM\Formats;

class Formats {
	const formats = [
		'diplomatic',
		'gb_1903',
		'gb_1932',
		'gb_1963',
		'gb_1983',
		'gb_2001',
		'military',
		'ni_1903',
		'ni_1966',
	];

	public static function all() {
		return self::formats;
	}

	public static function validateFormat(string $format) {
		return in_array($format, self::formats);
	}
}
