<?php

/**
 * TextformatterTypographer is a ProcessWire implemetation of PHP Typography by KINGdesk LLC.
 * Author: Mike Rockett
 * PhpTypography: http://kingdesk.com/projects/php-tyography/
 * @license PhpTypography: GNU GPL 2.0; TextformatterTypograher: MIT
 */

class TextformatterTypographer extends Textformatter
{
    /**
     * Module constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Textformatter formatValue
     * @param $text
     */
    public function formatValue(Page $page, Field $field, &$value)
    {
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

    /**
     * Module typographer
     * Uses PHP Typographer, included via Composer.
     * Home: http://kingdesk.com/projects/php-typography/
     * Docs: http://kingdesk.com/projects/php-typography-documentation/
     * @param  $input
     * @return string
     */
    public function typographer($input)
    {
        if (!$this->enabled) {
            return $input;
        }

        // Require composer libs and instatiate PhpTypography
        require_once __DIR__ . '/lib/PhpTypography.php';
        require_once __DIR__ . '/lib/ParseHTML.php';
        require_once __DIR__ . '/lib/ParseText.php';
        $typographer = new Debach\PhpTypography\PhpTypography();

        // Set package options, if `defaults` not set
        if (!$this->defaults) {
            # 1 General options
            foreach ([
                'dewidow',
                'emailWrap',
                'initialQuoteTags',
                'smartDashes',
                'smartDiacritics',
                'smartEllipses',
                'smartExponents',
                'smartFractions',
                'smartMarks',
                'smartMath',
                'smartOrdinalSuffix',
                'smartQuotes',
                'styleAmpersands',
                'styleCaps',
                'styleInitialQuotes',
                'styleNumbers',
                'urlWrap',
                'wrapHardHyphens',
            ] as $option) {
                $option_ = $this->snakeCase($option);
                $method = "set_{$option_}";
                $typographer->$method($this->$option);
            }

            # 2 Hyphenation
            #   If the language is not empty, then we can set the language,
            #   options, and word-exceptions. Otherwise, turn off hyphenation.
            if (!empty($this->hyphenationLanguage)) {
                $typographer->set_hyphenation_language($this->hyphenationLanguage);
                foreach ($this->hyphenationOptions as $option) {
                    $option = $this->snakeCase($option);
                    $method = "set_hyphenate_{$option}";
                    $typographer->$method(true);
                }
                $typographer->set_hyphenation_exceptions(explode('|', $this->hyphenationExceptions));
            } else {
                $typographer->set_hyphenation(false);
            }

            # 3 Exclusions
            #   Explode the exclusions string to an array after trimming it and removing
            #   any duplicate whitespace. Then process each entry, adding it to the
            #   applicable exclusions array. Then process those arrays to set the
            #   appropriate properties on the Typography class.
            if (!empty($this->exclusions)) {
                $exclusions = trim(str_replace('  ', ' ', $this->exclusions));
                $exclusionsArray = (object) [
                    'elements' => [],
                    'classes' => [],
                    'identifiers' => [],
                ];
                foreach (explode(' ', $exclusions) as $exclusion) {
                    switch ($exclusion[0]) {
                        case '#':
                            $exclusionsArray->identifiers[] = ltrim($exclusion, '#');
                            break;
                        case '.':
                            $exclusionsArray->classes[] = ltrim($exclusion, '.');
                            break;
                        default:
                            $exclusionsArray->elements[] = $exclusion;
                            break;
                    }
                }
                $typographer->set_tags_to_ignore($exclusionsArray->elements);
                $typographer->set_classes_to_ignore($exclusionsArray->classes);
                $typographer->set_ids_to_ignore($exclusionsArray->identifiers);
            }
        }

        // Process and return the incoming text
        return $typographer->process($input);
    }

    /**
     * Convert a string to snake case
     * Used for converting module config options to their equivalent functions
     * @param $input
     */
    protected function snakeCase($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
    }

    /**
     * Generate and import the stylesheet
     * @param  array  $config
     * @return string
     */
    public function styles($config = [])
    {
        // Initialise the configuration array and merge it with the one provided
        $config = (object) array_merge([
            'caps' => '96%',
            'numbers' => '96%',
            'dquo' => '-.41em',
            'squo' => '-.06em',
        ], $config);

        // If caps or numbers is an integer/percentage, set the CSS attribute
        // to font-size. Otherwise, set to family and enquote.
        foreach (['caps', 'numbers'] as $type) {
            $varName = "{$type}Method";
            // if (strpos($config->$type, '.') === 0 || strpos($config->$type, '%') === 0) {
            if (preg_match('/\.?\d+%?/', $config->$type)) {
                $$varName = 'size';
            } else {
                $$varName = 'family';
                $config->$type = "'{$config->$type}'";
            }
        }

        $output = str_replace([' ', "\n"], '', trim("
            span.caps { font-{$capsMethod}: {$config->caps} }
            span.numbers { font-{$numbersMethod}: {$config->numbers} }
            span.dquo { margin-left: {$config->dquo} }
            span.squo { margin-left: {$config->squo} }
        "));

        return "<style type=\"text/css\">{$output}</style>";
    }
}
