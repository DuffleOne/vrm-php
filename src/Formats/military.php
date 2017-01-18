<?php

namespace Duffleman\VRM\Formats;

class military implements FormatInterface
{
    const regexOriginal = '/^(\d{2})([A-Z]{2})(\d{2})$/';
    const regexReversed = '/^([A-Z]{2})(\d{2})([A-Z]{2})$/';

    public function parse(string $vrm)
    {
        $original = preg_match(self::regexOriginal, $vrm, $originalOut);
        $reversed = preg_match(self::regexReversed, $vrm, $reversedOut);
        $match = $original || $reversed;

        if (!$match) {
            return;
        }

        $isOriginal = (bool) $original;
        $matchOut = $isOriginal ? $originalOut : $reversedOut;

        array_shift($matchOut);

        return [
            'prettyVrm' => implode($matchOut, ' '),

            '_extra' => [
                'sections' => $matchOut,
            ],
        ];
    }
}
