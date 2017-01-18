<?php

namespace Duffleman\VRM\Formats;

class gb_2001 implements FormatInterface
{
    const validFrom = 2001;
    const regex = '/^([A-Z]{2}\d{2})([A-Z]{3})$/';
    const prohibitedLettersArea = ['I', 'Q', 'Z'];
    const prohibitedLettersSerial = ['I', 'Q'];

    public function parse(string $vrm)
    {
        $match = preg_match(self::regex, $vrm, $output);

        if ($match !== 1) {
            return;
        }

        $area = substr($output[1], 0, 2);
        $ageId = substr($output[1], 2, 2);
        $serial = $output[2];

        $year = self::calcYear($ageId);

        foreach (str_split($area) as $letter) {
            if (in_array($letter, self::prohibitedLettersArea)) {
                return;
            }
        }

        foreach (str_split($serial) as $letter) {
            if (in_array($letter, self::prohibitedLettersSerial)) {
                return;
            }
        }

        array_shift($output);

        return [
            'prettyVrm' => implode(' ', $output),

            '_extra' => [
                'area'          => $area,
                'ageIdentifier' => $ageId,
                'serialLetter'  => $serial,
                'year'          => $year,
            ],
        ];
    }

    private static function calcYear(string $ageId)
    {
        $age = intval($ageId, 10);

        if ($age === 0) {
            return 2050;
        }

        if ($age > 50) {
            return $age - 50 + 2000;
        }

        return $age + 2000;
    }
}
