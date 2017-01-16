<?php

namespace Duffleman\VRM;

use Exception;
use Duffleman\VRM\Validator;

class VRM {
	public static function coerce(string $input, array $allowedFormats = null) {
		if (!$input) {
			throw new Exception('input_invalid');
		}

		if ($allowedFormats !== null) {
			Validator::validateAllowedFormats($allowedFormats);

			array_walk($allowedFormats, function ($format) {
				if (!class_exists("\\Duffleman\\VRM\\Formats\\$format")) {
					throw new Exception('allowed_formats_unknown');
				}
			});
		}
	}

	public static function info() {}


}
