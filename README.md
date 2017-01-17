# vrm

Coerces, validates and prettifies vehicle registration marks (VRMs). Presently,
only UK DVLA registration plate formats are supported. Please open an issue if
you'd like to see support added for other driving authorities' formats - I'd be
keen to work with you to enable this.

```php
<?php

require_once('vendor/autoload.php');

use Duffleman\VRM\VRM;

var_dump(VRM::coerce('Lbo7 se0')); // => [{ format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }]
var_dump(VRM::coerce('Lbo7 se0', ['gb_2001'])); // => [{ format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }]
var_dump(VRM::coerce('Lbo7 se0', ['gb_1903'])); // => []
var_dump(VRM::info('LB07SEO')); // => { format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }
var_dump(VRM::info('LB07SEO', 'gb_2001')); // => { format: 'gb_2001', vrm: 'LB07SEO', prettyVrm: 'LB07 SEO' }
var_dump(VRM::info('LB07SEO', 'gb_1903')); // => null
```

## Installation

```bash
$ composer install
```

## API

### `VRM::coerce($input[, $allowedFormats])`

Coerces the input into a set of possible VRMs which the input could represent.
See [more information below](#coercion) about why this is needed.

The returned array contains the VRM details for each given format, sorted in
order of likelihood, where the most likely format is the first value.

If the allowed formats are specified, coercion will be limited to these formats.
Any other formats will not be checked.

Returns an empty array if the input is invalid or cannot be coerced into any of
the formats checked.

Throws if any of the allowed formats are not known.

### `VRM::info($normalizedVRM[, $format])`

Verifies that the given VRM matches one of the known formats and returns the
relevant VRM details. Only normalized VRMs are accepted.

If the format is specified, only that format is checked.

Returns null if the VRM does not match any of the formats checked.

Throws if the provided format is not known.

## Notes

### Formats

The known formats are referenced as the following strings:

- `gb_1903` - `AB 1234` - the first number-plates issued under the "Motor Car Act"
- `gb_1932` - `ABC 123` - after the above format began to run out
- `gb_1963` - `ABC 123A` - ditto
- `gb_1983` - `A123 ABC` - ditto
- `gb_2001` - `AB12 ABC` - the current GB format
- `ni_1903` - `AZ 1234` - roughly equivalent to `gb_1903`
- `ni_1966` - `AAZ 1234` - similar to `gb_1932`, the current NI format
- `diplomatic` - `123 D 123` - used for diplomats (`D`) and accredited personnel (`X`)
- `military` - `AB 12 AB` - used for military vehicles

### VRM Details

The VRM details objects returned include the following information:

```js
{
	format: 'gb_2001', // required, string - format referenced as-above
	vrm: 'LB07SEO', // required, string - vrm in upper case, no spaces, no special chars, etc.
	prettyVrm: 'LB07 SEO', // optional, string - vrm spaced as legally defined
}
```

More information is likely to be added to this object over time. These changes
would not be considered as 'breaking'.

### Coercion

UK registration plates use a particular official typeface which was designed by
Charles Wright. Bizarrely, it uses exactly the same physical shape for `I` and
`1`, and for `O` and `0`. This means that the exact VRM can be ambiguous and
people often mistype the VRM into forms. Additionally, it presents some
difficulties for automatic number plate recognition (ANPR) systems.

The `coerce` method handles this. It returns an array because (occasionally)
multiple formats can be possible.

The returned array is ordered with the original input first if it is valid,
followed by the other valid options with the newer formats coming first. This
behavior could change though, so don't rely on it.

### Possible Additions

If there is demand for it, two other bits of functionality could be provided:

- normalization: normalizing an arbitrary string input without coercing it (this happens internally anyway)
- extending `info`: exposing the data contained within a given VRM - e.g. the 2001 UK format contains information about the area and year of registration

### Data Sources

- [PDF from CVPG](http://www.cvpg.co.uk/REG.pdf) - last updated October 2003
- [Article on Wikipedia](https://en.wikipedia.org/w/index.php?title=Vehicle_registration_plates_of_the_United_Kingdom,_Crown_dependencies_and_overseas_territories&oldid=710572752) - last updated March 2016

## Support

Please open an issue on this repository.

## Authors

- James Billingham <james@jamesbillingham.com>
- George Miller <george@duffleman.co.uk>

## License

MIT licensed - see [LICENSE](LICENSE) file
