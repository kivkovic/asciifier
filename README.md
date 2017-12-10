# asciifier

A semantic unicode transliteration library for PHP. Provides an interface for converting non-Latin text into Latin, and non-ASCII Latin into ASCII. Unlike naive methods to achieve transliteration (e.g. by abusing `iconv`), asciifier will try to convert as many characters as possible into their natural transliterated values (e.g. `à, á, â, ã, ä, å, ą, ... -> a`)

```php
echo Asciifier::latin_to_ascii('Átbetűzés')
// outputs: Atbetuzes

echo Asciffier::unicode_to_latin('Транслитерация')
// outputs: Transliteracija

```

## Usage

The `Asciifier` class provides two static methods:

- `Asciifier::latin_to_ascii($string, $force = TRUE, $locale = FALSE)`- transliterates all Latin characters in the given string into ASCII values. Takes the following arguments:
  - `${string}` - string to transliterate
  - `${force}` (*optional*) whether to purge remaining non-ASCII characters (those outside of the range `[\x20-\x7E]`) after the conversion
  - `${locale}` (*optional*) may be a locale string (two-character [https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes](ISO 639-1)) used to override certain edge cases when a letter has multiple transliteration depending on the language used:
    - 'de': `ä -> ae, ö -> oe, ü -> ue, ß -> ss` instead of the default `ä -> a, ö -> o, ü -> u, ß -> s`
    - 'da': `ø -> oe, å -> aa` instead of the default `ø -> o, å -> a`
    - 'vi': `đ -> d` instead of the default `đ -> dj`

- `Asciifier::unicode_to_latin($string, $force = FALSE, $locale = FALSE)` - transliterates certain non-Latin alphabets into Latin. Currently incomplete, incorporates conversion rules for: Cyrillic, Greek, Arabic, Hebrew, Thai, Futhark and Katakana.
