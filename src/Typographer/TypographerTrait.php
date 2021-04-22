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

use PHP_Typography\Settings as TypographerSettings;

trait TypographerTrait
{
    /**
     * Current Settings instance
     * @var Settings
     */
    protected $typographerInstance;

    /**
     * Current Settings instance
     * @var Settings
     */
    protected $settingsInstance;

    /**
     * Basic options that are either bools or strings.
     * These are pulled in directly from the module's configuration.
     * @return void
     */
    protected function setBasicOptions()
    {
        foreach ($this->typographerInstance->basicOptions as $moduleOption) {
            $methodName = "set_{$this->snakeCase($moduleOption)}";
            $this->settingsInstance->$methodName($this->$moduleOption);
        }
    }

    /**
     * Explode the exclusions string to an array after trimming it and removing
     * any duplicate whitespace. Then process each entry, adding it to the
     * applicable exclusions array. Then process those arrays to set the
     * appropriate properties on the Typography class.
     * @return void
     */
    protected function setExclusions()
    {
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
            $this->settingsInstance->set_tags_to_ignore($exclusionsArray->elements);
            $this->settingsInstance->set_classes_to_ignore($exclusionsArray->classes);
            $this->settingsInstance->set_ids_to_ignore($exclusionsArray->identifiers);
        }
    }

    /**
     * If the hyphenation language is not empty, then we can set the language,
     * options, and word-exceptions. Otherwise, turn off hyphenation.
     * @return void
     */
    protected function setHyphenation()
    {
        if (!empty($this->hyphenationLanguage)) {
            $this->settingsInstance->set_hyphenation_language($this->hyphenationLanguage);
            foreach ($this->hyphenationOptions as $option) {
                $option = $this->snakeCase($option);
                $method = "set_hyphenate_{$option}";
                $this->settingsInstance->$method(true);
            }
            $this->settingsInstance->set_hyphenation_exceptions(explode(' ', $this->hyphenationExceptions));
        } else {
            $this->settingsInstance->set_hyphenation(false);
        }
    }

    /**
     * Options that require conversion from a property list
     * to an array. The Rockett\Traits\UtilityTrait\parsePropertyList()
     * description contains more information about how
     * property lists are formatted.
     * @return void
     */
    protected function setPropertyMappings()
    {
        foreach ($this->typographerInstance->propertyMappings as $config => $propertySeparator) {
            if (!empty($this->$config)) {
                $methodName = "set_{$this->snakeCase($config)}";
                $this->settingsInstance->$methodName($this->parsePropertyList($this->$config, $propertySeparator));
            }
        }
    }

    /**
     * This method creates a new Typographer and Settings instance,
     * sets options based on the module's configuration, and
     * proceeds to convert the input.
     * @param $input
     */
    protected function typographer($input)
    {
        $this->typographerInstance = new Typographer();
        $this->settingsInstance = new TypographerSettings();

        // Set configuration
        $this->setBasicOptions();
        $this->setPropertyMappings();
        $this->setHyphenation();
        $this->setExclusions();

        // Run hook to allow modification of the Settings instance
        $ettingsInstance = $this->customTypographerSettings($this->settingsInstance);

        // Process and return the incoming text
        return $this->typographerInstance->process($input, $ettingsInstance);
    }

    /**
     * Hook allow modifcation of the Typographer Settings instance
     * @return void
     */
    public function ___customTypographerSettings($settingsInstance)
    {
        return $settingsInstance;
    }
}
