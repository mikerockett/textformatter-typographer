<?php

namespace Typographer;

use PHP_Typography\PHP_Typography as Typography;

class Typographer extends Typography
{
  protected array $css_classes = [
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

  public array $basicOptions = [
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

    // RESERVED:
    // 'units',
  ];

  public array $propertyMappings = [
    'diacriticCustomReplacements' => "\n",
  ];
}
