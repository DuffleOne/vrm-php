<?php

namespace Duffleman\VRM\Formats;

class gb_1903 implements FormatInterface {
	const validFrom = 1903;
	const regexOriginal = '/^([A-Z]{1,2})(\d{1,4})$/';
	const regexReversed = '/^(\d{1,4})([A-Z]{1,2})$/';
	const prohibitedLetters = ['I', 'Q', 'Z'];

	public function parse(string $vrm) {
		$original = preg_match(self::regexOriginal, $vrm, $originalOut);
		$reversed = preg_match(self::regexReversed, $vrm, $reversedOut);
		$match = $original || $reversed;

		if (!$match) {
			return null;
		}

		$isOriginal = !!$original;
		$area = $isOriginal ? $originalOut[1] : $reversedOut[2];
		$seq = intval($isOriginal ? $originalOut[2] : $reversedOut[1], 10);
		$matchOut = $isOriginal ? $originalOut : $reversedOut;

		foreach (str_split($area) as $letter) {
			if (in_array($letter, self::prohibitedLetters)) {
				return null;
			}
		}

		array_shift($matchOut);
		$prettyVrm = implode($matchOut, ' ');

		return [
			'prettyVrm' => $prettyVrm,
			'_extra' => [
				'reversed' => !$isOriginal,
				'area' => $area,
				'sequence' => $seq,
			],
		];
	}
}
