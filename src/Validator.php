<?php

namespace Duffleman\VRM;

class Validator {
	public static function validateAllowedFormats($allowedFormats) {
		if (!is_array($allowedFormats)) {
			throw new Exception('allowed_formats_invalid');
		}

		if (count($allowedFormats) === 0) {
			throw new Exception('allowed_formats_invalid');
		}

		foreach ($allowedFormats as $format) {
			$pattern = '/^[a-z0-9_]+$/';
			$match = preg_match($pattern, $format);

			if ($match !== 1) {
				throw new Exception('allowed_format_invalid');
			}
		}
	}
}
