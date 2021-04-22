# Typographer for ProcessWire

Typographer is a ProcessWire wrapper for the awesome PHP Typography class, originally authored by KINGdesk LLC and enhanced by Peter Putzer for wp-Typography. Like Smartypants, it supercharges text fields with enhanced typography and typesetting, such as smart quotations, hyphenation in 59 languages, ellipses, copyright-, trade-, and service-marks, math symbols, and more.

## Install

The module may be installed via the modules directory by searching for **TextformatterTypographer**.

## Configuration

This module is configurable. Feel free to visit the configuration page to see what can be toggled and configured.

To modify the instance of Typographer's settings class programatically, hook into the `customTypographerSettings` method.

```php
wire()->addHookAfter('TextformatterTypographer::customTypographerSettings', function (HookEvent $event) {
  /** @var \PHP_Typography\Settings $settings */
  $settings = $event->return;

  $lang = $event->user->language->name;

  if ($lang === 'de') {
    $settings->set_hyphenation_language('de');
    $settings->set_smart_quotes_primary('doubleLow9Reversed');
    $settings->set_smart_quotes_secondary('singleLow9Reversed');
  }
});
```

## Licenses

This project is licensed under the permissive MIT license, and the libraries it depends on have their own licenses. Please review these in the [license file](LICENSE.md).
