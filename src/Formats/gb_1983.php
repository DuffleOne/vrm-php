<?php

namespace Duffleman\VRM\Formats;

class gb_1983 implements FormatInterface
{
    const validFrom = 1983;
    const regex = '/^([A-Z]\d{1,3})([A-Z]{3})$/';
    const prohibitedLetters = ['I', 'Q', 'Z'];
    const ageIdToYear = [
        'A' => 1984,
        'B' => 1985,
        'C' => 1986,
        'D' => 1987,
        'E' => 1988,
        'F' => 1989,
        'G' => 1990,
        'H' => 1991,
        'J' => 1992,
        'K' => 1993,
        'L' => 1994,
        'M' => 1995,
        'N' => 1996,
        'P' => 1997,
        'R' => 1998,
        'S' => 1999,
        'T' => 1999,
        'V' => 2000,
        'W' => 2000,
        'X' => 2001,
        'Y' => 2001,
    ];

    public function parse(string $vrm)
    {
        $match = preg_match(self::regex, $vrm, $matchOut);

        if ($match !== 1) {
            return;
        }

        $ageId = substr($matchOut[1], 0, 1);
        $seq = intval(substr($matchOut[1], 1), 10);
        $serial = substr($matchOut[2], 0, 1);
        $area = substr($matchOut[2], 1, 2);

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
                'ageIdentifier' => $ageId,
                'sequence'      => $seq,
                'serialLetter'  => $serial,
                'area'          => $area,
                'year'          => $year,
            ],
        ];
    }
}
