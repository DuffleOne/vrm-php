<?php

namespace Duffleman\VRM\Formats;

class gb_1932 implements FormatInterface {
	const validFrom = 1932;
	const regexOriginal = '/^([A-Z]{3})(\d{1,3})$/';
	const regexReversed = '/^(\d{1,3})([A-Z]{3})$/';
	const prohibitedLetters = ['I', 'Q', 'Z'];

	public function parse(string $vrm) {
		$original = preg_match(self::regexOriginal, $vrm, $originalOut);
		$reversed = preg_match(self::regexReversed, $vrm, $reversedOut);
		$match = $original || $reversed;

		if (!$match) {
			return null;
		}

		$isOriginal = !!$original;
		$serial = substr($isOriginal ? $originalOut[1] : $reversedOut[2], 0, 1);
		$area = substr($isOriginal ? $originalOut[1] : $reversedOut[2], 1, 2);
		$seq = intval($isOriginal ? $originalOut[2] : $reversedOut[1], 10);
		$matchOut = $isOriginal ? $originalOut : $reversedOut;

		if (in_array($serial, self::prohibitedLetters)) {
			return null;
		}

		foreach (str_split($area) as $letter) {
			if (in_array($letter, self::prohibitedLetters)) {
				return null;
			}
		}

		array_shift($matchOut);

		return [
			'prettyVrm' => implode($matchOut, ' '),

			'_extra' => [
				'reversed' => !$isOriginal,
				'serialLetter' => $serial,
				'area' => $area,
				'sequence' => $seq,
			],
		];
	}
}
