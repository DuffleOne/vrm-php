<?php

namespace Duffleman\VRM;

use Exception;
use Duffleman\VRM\Validator;

class VRM {
	const equivalent = [
		['0', 'O'],
		['1', 'I'],
	];

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

		$normalized = Validator::normalize($input);

		if (!$normalized) {
			return [];
		}

		$combinations = self::alternatives($normalized);

		return $combinations;
	}

	public static function info() {}

	private static function alternatives(string $input) {
		$substitutable = [];
		$substitutes = [];
		$substituteIndexes = [];

		foreach (self::equivalent as $eq) {
			foreach ($eq as $char) {
				$substitutable[] = $char;
				$substitutes[$char] = $eq;
			}
		}

		for ($i = 0; $i < strlen($input); $i++) {
			$letter = $input[$i];

			if (in_array($letter, $substitutable)) {
				$substituteIndexes[] = $i;
			}
		}

		if (count($substituteIndexes) <= 0) {
			return [$input];
		}

		$sets = array_map(function ($idx) use($substitutes, $input) {
			return $substitutes[$input[$idx]];
		}, $substituteIndexes);

		$combinations = self::cartesianProduct($sets);
		$splitInput = str_split($input);

		return array_map(function ($combination) use ($splitInput, $substituteIndexes) {
			$splitString = $splitInput;

			for ($i = 0; $i < count($substituteIndexes); $i++) {
				$splitString[$substituteIndexes[$i]] = $combination[$i];
			}

			return implode('', $splitString);
		}, $combinations);
	}

	private static function cartesianProduct(array $sets) {
		$input = array_filter($sets);
		$result = [[]];

		foreach ($sets as $key => $values) {
			$append = [];

			foreach ($result as $product) {
				foreach ($values as $item) {
					$product[$key] = $item;
					$append[] = $product;
				}
			}

			$result = $append;
		}

		return $result;
	}
}
