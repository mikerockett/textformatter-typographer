<?php

/**
 * Typographer for ProcessWire
 *
 * Module class
 *
 * @author Mike Rockett <github@rockett.pw>
 * @copyright 2017
 * @license MIT
 */

require_once __DIR__ . '/loader.php';

use Rockett\DebugTrait as Debugs;
use Rockett\UtilityTrait as UsesUtilities;
use Typographer\TypographerTrait as UsesTypographer;

class TextformatterTypographer extends Textformatter
{
    use UsesTypographer, UsesUtilities, Debugs;

    /**
     * Module constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Textformatter formatValue (Typographer handler)
     * @param $text
     */
    public function formatValue(Page $page, Field $field, &$value)
    {
        // Require the Composer autoloader and prevent
        // the ProcessWire FileCompile from touching anything.
        // Note: this is only done at method-call-time as it this is
        // not an autoload module.
        require_once /*NoCompile*/__DIR__ . '/vendor/autoload.php';

        // Run Typographer and return the converted input.
        $value = $this->typographer($value);
    }

    /**
     * Required function for obtaining module info
     * in the "Details" tab of a field.
     * @return Array
     */
    public function getModuleInfo()
    {
        return json_decode(file_get_contents(__DIR__ . '/TextformatterTypographer.info.json'), true);
    }
}
