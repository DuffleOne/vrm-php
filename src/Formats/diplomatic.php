<?php

namespace Duffleman\VRM\Formats;

class diplomatic implements FormatInterface {
	const validFrom = 1979;
	const regex = '/^(\d{3})(D|X)(\d{3})$/';

	public function parse(string $vrm) {
		$match = preg_match(self::regex, $vrm, $matchOut);

		if ($match !== 1) {
			return null;
		}

		$entity = intval($matchOut[1], 10);
		$serial = intval($matchOut[3], 10);

		array_shift($matchOut);

		return [
			'prettyVrm' => implode($matchOut, ' '),

			'_extra' => [
				'entity' => $entity,
				'serial' => $serial,
			],
		];
	}
}
