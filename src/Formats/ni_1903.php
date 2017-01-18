<?php

namespace Duffleman\VRM\Formats;

class ni_1903 implements FormatInterface
{
    const validFrom = 1903;
    const regexOriginal = '/^([A-Z]{2})(\d{1,4})$/';
    const regexReversed = '/^(\d{1,4})([A-Z]{2})$/';
    const areas = [
        'AZ', 'BZ', 'CZ', 'DZ', 'EZ', 'FZ', 'GZ', 'HZ', 'IA', 'IB', 'IG', 'IJ',
        'IL', 'IW', 'JI', 'JZ', 'KZ', 'LZ', 'MZ', 'NZ', 'OI', 'OZ', 'PZ', 'RZ',
        'SZ', 'TZ', 'UI', 'UZ', 'VZ', 'WZ', 'XI', 'XZ', 'YZ',
    ];

    public function parse(string $vrm)
    {
        $original = preg_match(self::regexOriginal, $vrm, $originalOut);
        $reversed = preg_match(self::regexReversed, $vrm, $reversedOut);
        $match = $original || $reversed;

        if (!$match) {
            return;
        }

        $isOriginal = (bool) $original;
        $area = $isOriginal ? $originalOut[1] : $reversedOut[2];
        $seq = intval($isOriginal ? $originalOut[2] : $reversedOut[1], 10);
        $matchOut = $isOriginal ? $originalOut : $reversedOut;

        if (!in_array($area, self::areas)) {
            return;
        }

        array_shift($matchOut);

        return [
            'prettyVrm' => implode($matchOut, ' '),

            '_extra' => [
                'reversed' => !$isOriginal,
                'area'     => $area,
                'sequence' => $seq,
            ],
        ];
    }
}
