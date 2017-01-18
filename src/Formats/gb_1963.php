<?php

namespace Duffleman\VRM\Formats;

class gb_1963 implements FormatInterface
{
    const validFrom = 1963;
    const regex = '/^([A-Z]{3})(\d{1,3}[A-Z])$/';
    const prohibitedLetters = ['I', 'Q', 'Z'];
    const ageIdToYear = [
        'A' => 1963,
        'B' => 1964,
        'C' => 1965,
        'D' => 1966,
        'E' => 1967,
        'F' => 1968,
        'G' => 1969,
        'H' => 1970,
        'J' => 1971,
        'K' => 1972,
        'L' => 1973,
        'M' => 1974,
        'N' => 1975,
        'P' => 1976,
        'R' => 1977,
        'S' => 1978,
        'T' => 1979,
        'V' => 1980,
        'W' => 1981,
        'X' => 1982,
        'Y' => 1983,
    ];

    public function parse(string $vrm)
    {
        $match = preg_match(self::regex, $vrm, $matchOut);

        if ($match !== 1) {
            return;
        }

        $serial = substr($matchOut[1], 0, 1);
        $area = substr($matchOut[1], 1, 2);
        $seq = intval(substr($matchOut[2], 0, -1), 10);
        $ageId = substr($matchOut[2], -1);

        if (!isset(self::ageIdToYear[$ageId])) {
            return;
        }

        if (in_array($serial, self::prohibitedLetters)) {
            return;
        }

        foreach (str_split($area) as $letter) {
            if (in_array($letter, self::prohibitedLetters)) {
                return;
            }
        }

        $year = self::ageIdToYear[$ageId];

        array_shift($matchOut);

        return [
            'prettyVrm' => implode($matchOut, ' '),

            '_extra' => [
                'serialLetter'  => $serial,
                'area'          => $area,
                'sequence'      => $seq,
                'ageIdentifier' => $ageId,
                'year'          => $year,
            ],
        ];
    }
}
