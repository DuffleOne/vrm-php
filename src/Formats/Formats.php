<?php

namespace Duffleman\VRM\Formats;

class Formats {
	public static function all() {
		$files = scandir(__DIR__);

		$files = array_map(function ($fileName) {
			if (strpos($fileName, '.php') === false) {
				return $fileName;
			}

			return str_replace('.php', '', $fileName);
		}, $files);

		$exceptions = ['.', '..', 'FormatInterface', 'Formats'];

		$files = array_filter($files, function ($file) use ($exceptions) {
			if (in_array($file, $exceptions)) {
				return false;
			}

			return true;
		});

		return $files;
	}
}
