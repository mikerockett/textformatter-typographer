<?php

/**
 * Field helpers for ProcessWire
 * Allows field-building on the fly.
 * Add namespace and `use FieldsTrait` in your class.
 *
 * @author  Mike Rockett
 * @license MIT
 */

namespace Rockett;

trait FieldsTrait
{
    /**
     * Given a fieldtype, create, populate, and return an Inputfield
     * @param  string       $fieldNameId
     * @param  array        $meta
     * @return Inputfield
     */
    protected function buildInputField($fieldNameId, $meta)
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
