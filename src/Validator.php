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

	public static function normalize($vrm) {
		if (!$vrm) {
			return null;
		}

		$cleaned = preg_replace('/\\s/', '', $vrm);
		$cleaned = strtoupper($cleaned);

		if (!self::validateNormalizedVRM($cleaned)) {
			return null;
		}

		return $cleaned;
	}

	private static function validateNormalizedVRM($vrm) {
		if (!$vrm) {
			return false;
		}

		if (strlen($vrm) > 7 || strlen($vrm) < 1) {
			return false;
		}

		if (preg_match('/^[A-Z0-9]+$/', $vrm) !== 1) {
			return false;
		}

		return true;
	}
}
