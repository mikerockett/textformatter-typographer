<?php

namespace Util;

use ProcessWire\Inputfield;

trait FieldsTrait
{
  protected function buildInputField(string $fieldNameId, array $meta): Inputfield
  {
    $field = $this->modules->get("Inputfield{$fieldNameId}");

    foreach ($meta as $metaNames => $metaInfo) {
      $metaNames = explode('+', $metaNames);
      foreach ($metaNames as $metaName) {
        $field->$metaName = $metaInfo;
        if ($metaName === 'monospace' && $metaInfo === true) {
          $field->setAttribute(
            'style',
            "font-family:Menlo,Monaco,'Andale Mono','Lucida Console','Courier New',monospace;" .
              'font-size:14px;padding:4px'
          );
        }
      }
    }

    return $field;
  }
}
