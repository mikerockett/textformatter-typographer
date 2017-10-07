<?php

/**
 * Typographer for ProcessWire
 *
 * Main trait
 *
 * @author Mike Rockett <github@rockett.pw>
 * @copyright 2017
 * @license MIT
 */

namespace Typographer;

use PHP_Typography\PHP_Typography as Typography;

class Typographer extends Typography
{
    /**
     * CSS classes that can be overriden by calling
     * the appropriate methods.
     * @var array
     */
    protected $css_classes = [
        'quo' => 'pull-single initial',
        'dquo' => 'pull-double initial',
        'pull-single' => 'pull-single',
        'pull-double' => 'pull-double',
        'push-single' => 'push-single',
        'push-double' => 'push-double',
        'caps' => 'capitals',
        'numbers' => 'numbers',
        'amp' => 'char-amp',
        'numerator' => 'char-numerator',
        'denominator' => 'char-denominator',
        'ordinal' => 'char-ordinal',
    ];

    /**
     * Basic options
     * @var array
     */
    public $basicOptions = [
        'dewidow',
        'diacriticLanguage',
        'emailWrap',
        'fractionSpacing',
        'frenchPunctuationSpacing',
        'initialQuoteTags',
        'singleCharacterWordSpacing',
        'smartDashes',
        'smartDashesStyle',
        'smartDiacritics',
        'smartEllipses',
        'smartExponents',
        'smartFractions',
        'smartMarks',
        'smartMath',
        'smartOrdinalSuffix',
        'smartQuotes',
        'spaceCollapse',
        'styleAmpersands',
        'styleCaps',
        'styleHangingPunctuation',
        'styleInitialQuotes',
        'styleNumbers',
        'urlWrap',
        'wrapHardHyphens',
        'unitSpacing',
        'numberedAbbreviationSpacing',
        'dashSpacing',
        'trueNoBreakNarrowSpace',

        /**
         * The following basic options are reserved
         * for a future release.
         * @reserved
         */
        // 'units',
    ];

    /**
     * Property mappings
     * @var array
     */
    public $propertyMappings = [
        'diacriticCustomReplacements' => "\n",
    ];
}
