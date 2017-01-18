<?php

namespace Duffleman\VRM;

class Mark
{
    private $vrm;
    private $prettyVrm;
    private $format;
    private $details;

    public function __construct(string $vrm, string $fmt, array $details)
    {
        $this->vrm = $vrm;
        $this->format = $fmt;
        $this->details = $details;

        if (isset($details['prettyVrm'])) {
            $this->prettyVrm = $details['prettyVrm'];
        }
    }

    public function __get($var)
    {
        return $this->{$var};
    }

    public function __toString()
    {
        return $this->prettyVrm;
    }
}
