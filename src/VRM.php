<?php

namespace Duffleman\VRM;

use Exception;
use Duffleman\VRM\Validator;
use function Duffleman\VRM\Formats\gb_2001;

class VRM {
	public static function coerce(string $input, array $allowedFormats = null) {
		if (!$input) {
			throw new Exception('input_invalid');
		}

		if ($allowedFormats !== null) {
			Validator::validateAllowedFormats($allowedFormats);

			gb_2001('test');
		}
	}

	public static function info() {}


}
