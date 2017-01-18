<?php

namespace Duffleman\VRM\Formats;

class ni_1966 implements FormatInterface
{
    const validFrom = 1966;
    const regex = '/^([A-Z]{3})(\d{1,4})$/';
    const areas = [
        'AZ', 'BZ', 'CZ', 'DZ', 'EZ', 'FZ', 'GZ', 'HZ', 'IA', 'IB', 'IG', 'IJ',
        'IL', 'IW', 'JI', 'JZ', 'KZ', 'LZ', 'MZ', 'NZ', 'OI', 'OZ', 'PZ', 'RZ',
        'SZ', 'TZ', 'UI', 'UZ', 'VZ', 'WZ', 'XI', 'XZ', 'YZ',
    ];

    public function parse(string $vrm)
    {
        $match = preg_match(self::regex, $vrm, $matchOut);

        if ($match !== 1) {
            return;
        }

        $serial = substr($matchOut[1], 0, 1);
        $area = substr($matchOut[1], 1, 2);
        $seq = intval($matchOut[2], 10);

        if (!in_array($area, self::areas)) {
            return;
        }

        array_shift($matchOut);

        return [
            'prettyVrm' => implode($matchOut, ' '),

            '_extra' => [
                'serialLetter' => $serial,
                'area'         => $area,
                'sequence'     => $seq,
            ],
        ];
    }
}
