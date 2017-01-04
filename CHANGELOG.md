# TextformatterTypographer Changelog

## 0.2.0 (2017-01-04)

- Switch from the old PHP Typography library to the newer, more frequently updated library found at [wp-Typography](https://github.com/mundschenk-at/wp-typography/)
- Config no longer allows the textformatter to be disabled. If you want to turn it off, rather remove it from your field.
- Config no longer allows to reset to defaults. A different implementation of this may be added in the future, if deemed required.
- Stylesheet is no longer provided as a function for the purposes of not being opinionated. There is, however, a stylesheet in the module's directory, which can be used as a basis for your main stylesheet.
- You will also notice that the stylesheet references new classes. Due to the fact that this fork of PHP-Typography uses hanging punctuation, new classes have been added. However, they retained the original naming for single and double initial quotes. As this is a new module for PW, I thought it sensible to change these names. All the new class names are in the stylesheet, and also in the Typographer subclass.