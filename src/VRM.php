<?php

namespace Duffleman\VRM;

use Exception;
use Duffleman\VRM\Validator;
use Duffleman\VRM\Formats\Formats;

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
		$formats = self::getSortedFormats($allowedFormats);
		$results = [];

		foreach ($formats as $fmt) {
			$className = "\Duffleman\VRM\Formats\\$fmt";
			$formatter = new $className;

			foreach ($combinations as $vrm) {
				$details = $formatter->parse($vrm);

				if (is_null($details)) {
					continue;
				}

				$results[] = self::mapDetails($details, $fmt, $vrm);
			}
		}

		self::preferVrm($results, $normalized);

		return $results;
	}

	public static function info(string $normalizedVrm, string $format = null) {
		if (!Validator::validateNormalizedVRM($normalizedVrm))
			throw new Error('normalized_vrm_invalid');

		if (!is_null($format)) {
			if (!Formats::validateFormat($format)) {
				throw new Exception('format_invalid');
			}
		}

		$formats = $format ? [$format] : Formats::all();

		foreach ($formats as $fmt) {
			$className = "\Duffleman\VRM\Formats\\$fmt";
			$formatter = new $className;

			$details = $formatter->parse($normalizedVrm);

			if ($details) {
				return self::mapDetails($details, $fmt, $normalizedVrm);
			}
		}

		return null;
	}

	private static function mapDetails(array $details, string $fmt, string $vrm) {
		return new Mark($vrm, $fmt, $details);
	}

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

	private static function getSortedFormats($allowedRefs) {
		if (!$allowedRefs || empty($allowedRefs))
			return Formats::all();

		return array_filter(Formats::all(), function ($format) use($allowedRefs) {
			return in_array($format, $allowedRefs);
		});
	}

	private static function preferVrm(array &$results, string $preferredVrm) {
		for ($i = 0; $i < count($results); $i++) {
			$result = $results[$i];

			if ($result->vrm !== $preferredVrm) {
				continue;
			}

			array_splice($results, $i, 1);
			array_unshift($results, $result);

			return;
		}
	}
}
