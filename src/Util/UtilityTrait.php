<?php

namespace Util;

trait UtilityTrait
{
  /**
   * Parse a property list.
   *
   * Valid separators are "=" and ":". Separators may be surrounded
   * by whitespace - this will be trimmed.
   *
   * Example:
   *   MSFT=Microsoft
   *   HP: Hewlett Packard
   *   SKU = Stock Keeping Unit
   */
  protected function parsePropertyList(string $list, $propertySeparator = "\n"): array
  {
    $properties = explode($propertySeparator, $list);

    foreach ($properties as $property) {
      $separator = strpbrk($property, '=:');
      [$key, $value] = explode($separator[0], $property);
      $propertiesArray[trim($key)] = trim($value);
    }

    return $propertiesArray;
  }

  protected function snakeCase(string $input): string
  {
    preg_match_all(
      '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!',
      $input,
      $matches
    );

    $ret = $matches[0];

    foreach ($ret as &$match) {
      $match = $match == strtoupper($match)
        ? strtolower($match)
        : lcfirst($match);
    }

    return implode('_', $ret);
  }
}
