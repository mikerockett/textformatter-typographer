<?php

namespace ProcessWire;

require_once __DIR__ . '/loader.php';

use Util\DebugTrait;
use Util\UtilityTrait;
use Typographer\TypographerTrait;

class TextformatterTypographer extends Textformatter
{
  use DebugTrait;
  use TypographerTrait;
  use UtilityTrait;

  public function formatValue(Page $page, Field $field, &$value)
  {
    require_once(/*NoCompile*/__DIR__ . '/lib/autoload.php');

    $value = $this->typographer($value);
  }

  public function formatString(string $value)
  {
    require_once(/*NoCompile*/__DIR__ . '/lib/autoload.php');

    return $this->typographer($value);
  }

  public static function getModuleInfo()
  {
    return json_decode(
      file_get_contents(__DIR__ . '/TextformatterTypographer.info.json'),
      true
    );
  }
}
