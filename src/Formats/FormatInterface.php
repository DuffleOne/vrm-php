<?php

namespace Duffleman\VRM\Formats;

interface FormatInterface
{
    public function parse(string $vrm);
}
