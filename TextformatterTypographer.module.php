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
        // Require and instatiate Typography subclass
        require_once __DIR__ . '/Typographer.php';
        $typographer = new Typographer();

        // BASIC OPTIONS
        //
        // Options that are either bools or strings.
        // These are pulled in directly from the module's configuration.
        foreach ([
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
            // 'dashSpacing', #reserved
            // 'unitSpacing', #reserved
            // 'units', #reserved
        ] as $moduleOption) {
            $packageOption = $this->snakeCase($moduleOption);
            $methodName = "set_{$packageOption}";
            $typographer->$methodName($this->$moduleOption);
        }

        // PROPERTY LIST OPTIONS
        //
        // Options that require conversion from a property list
        // to an array. The parsePropertyList() description contains
        // more information about how property lists are formatted.
        $propertyMappings = [
            'diacriticCustomReplacements' => "\n", # ==
        ];
        foreach ($propertyMappings as $config => $propertySeparator) {
            if (!empty($this->$config)) {
                $settingName = $this->snakeCase($config);
                $methodName = "set_{$settingName}";
                $typographer->$methodName($this->parsePropertyList($this->$config, $propertySeparator));
            }
        }

        // HYPHENATION
        //
        // If the language is not empty, then we can set the language,
        // options, and word-exceptions. Otherwise, turn off hyphenation.
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

        // EXCLUSIONS
        //
        // Explode the exclusions string to an array after trimming it and removing
        // any duplicate whitespace. Then process each entry, adding it to the
        // applicable exclusions array. Then process those arrays to set the
        // appropriate properties on the Typography class.
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
                    case '.':
                        $exclusionsArray->identifiers[] = ltrim($exclusion, $exclusion[0]);
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

        // Process and return the incoming text
        return $typographer->process($input);
    }

    /**
     * Parse a property list
     * Valid separators are "=" and ":"
     * Separators may be surrounded by whitespace - this will be trimmed. Ex:
     *     MSFT=Microsoft
     *     HP: Hewlett Packard
     *     SKU = Stock Keeping Unit
     * @param  $list
     * @return Array
     */
    protected function parsePropertyList($list, $propertySeparator = "\n")
    {
        $properties = explode($propertySeparator, $list);
        foreach ($properties as $property) {
            $separator = strpbrk($property, '=:');
            list($key, $value) = explode($separator[0], $property);
            $propertiesArray[trim($key)] = trim($value);
        }

        return $propertiesArray;
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
}
