<?php

namespace Typographer;

use PHP_Typography\Settings;

trait TypographerTrait
{
  protected Typographer $typographerInstance;
  protected Settings $settingsInstance;

  protected function setBasicOptions(): void
  {
    foreach ($this->typographerInstance->basicOptions as $moduleOption) {
      $methodName = "set_{$this->snakeCase($moduleOption)}";
      $this->settingsInstance->$methodName($this->$moduleOption);
    }
  }

  protected function setExclusions(): void
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

  protected function setHyphenation(): void
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

  protected function setPropertyMappings(): void
  {
    foreach ($this->typographerInstance->propertyMappings as $config => $propertySeparator) {
      if (!empty($this->$config)) {
        $methodName = "set_{$this->snakeCase($config)}";
        $this->settingsInstance->$methodName($this->parsePropertyList($this->$config, $propertySeparator));
      }
    }
  }

  protected function typographer(string $input): string
  {
    $this->typographerInstance = new Typographer();
    $this->settingsInstance = new Settings();

    $this->setBasicOptions();
    $this->setPropertyMappings();
    $this->setHyphenation();
    $this->setExclusions();

    $settingsInstance = $this->customTypographerSettings($this->settingsInstance);

    return $this->typographerInstance->process($input, $settingsInstance);
  }

  /**
   * @param Settings $settingsInstance
   */
  public function ___customTypographerSettings($settingsInstance)
  {
    return $settingsInstance;
  }
}
