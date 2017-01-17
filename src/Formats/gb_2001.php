<?php

namespace Duffleman\VRM\Formats;

class gb_2001 implements FormatInterface {
	const validFrom = 2001;
	const regex = '/^([A-Z]{2}\d{2})([A-Z]{3})$/';
	const prohibitedLettersArea = ['I', 'Q', 'Z'];
	const prohibitedLettersSerial = ['I', 'Q'];

	public function parse(string $vrm) {

	}
}
