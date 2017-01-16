<?php

require_once('vendor/autoload.php');

use Duffleman\VRM\VRM;

var_dump(VRM::coerce('Lbo7 se0'));
var_dump(VRM::coerce('Lbo7 se0', ['gb_2001']));
var_dump(VRM::coerce('Lbo7 se0', ['gb_1903']));
var_dump(VRM::info('LB07SEO'));
var_dump(VRM::info('LB07SEO', 'gb_2001'));
var_dump(VRM::info('LB07SEO', 'gb_1903'));

/*
console.log(VRM.coerce('Lbo7 se0')); // => [{ format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }]
console.log(VRM.coerce('Lbo7 se0', ['gb_2001'])); // => [{ format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }]
console.log(VRM.coerce('Lbo7 se0', ['gb_1903'])); // => []
console.log(VRM.info('LB07SEO')); // => { format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }
console.log(VRM.info('LB07SEO', 'gb_2001')); // => { format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }
console.log(VRM.info('LB07SEO', 'gb_1903')); // => null
*/
