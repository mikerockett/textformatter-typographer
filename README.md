# Typographer for ProcessWire

Typographer is a ProcessWire wrapper for the awesome PHP Typography class, originally authored by KINGdesk LLC and enhanced by Peter Putzer for wp-Typography. Like Smartypants, it supercharges text fields with enhanced typography and typesetting, such as smart quotations, hyphenation in 59 languages, ellipses, copyright-, trade-, and service-marks, math symbols, and more.

## Install

The module may be installed via the modules directory by searching for **TextformatterTypographer**.

Or, you can use Composer:

```
composer require rockett/textformatter-typographer
```

## Usage

To use Typographer, simply apply the text formatter on a field as you usually would.

If you are interacting with content that does not form part of the normal ProcessWire page system, such as fetching content from an external source, you can make use of the `formatString` method on the module class:

```php
print $modules
  ->get('TextformatterTypographer')
  ->formatString('"Hello, world!"');
```

## Configuration

This module is configurable. Feel free to visit the configuration page to see what can be toggled and configured.

To modify the instance of Typographer's settings class programatically, hook into the `customTypographerSettings` method.

```php
wire()->addHookAfter('TextformatterTypographer::customTypographerSettings', function (HookEvent $event) {
  /** @var \PHP_Typography\Settings $settings */
  $settings = $event->return;
  $lang = $event->user->language->name;

  // Set configurations based on language, for example:
  if ($lang === 'de') {
    $settings->set_hyphenation_language('de');
    $settings->set_smart_quotes_primary('doubleLow9Reversed');
    $settings->set_smart_quotes_secondary('singleLow9Reversed');
  }
});
```

## Licenses

This project is licensed under the permissive MIT license, and the libraries it depends on have their own licenses. Please review these in the [license file](LICENSE.md).
