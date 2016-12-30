<?php

/**
 * TextformatterTypographer is a ProcessWire implemetation of PHP Typography by KINGdesk LLC.
 * Author: Mike Rockett
 * PhpTypography: http://kingdesk.com/projects/php-tyography/
 * @license PhpTypography: GNU GPL 2.0; TextformatterTypograher: MIT
 */

class TextformatterTypographerConfig extends ModuleConfig
{
    /**
     * Get default configuration, automatically passed to input fields.
     * @return array
     */
    public function getDefaults()
    {
        return [
            'enabled' => true,
            'defaults' => true,
            'dewidow' => true,
            'emailWrap' => true,
            'initialQuoteTags' => 'p h1 h2 h3 h4 h5 h6 blockquote li dd dt',
            'smartDashes' => true,
            'smartDiacritics' => true,
            'smartEllipses' => true,
            'smartExponents' => true,
            'smartFractions' => true,
            'smartMarks' => true,
            'smartMath' => true,
            'smartOrdinalSuffix' => true,
            'smartQuotes' => true,
            'styleAmpersands' => true,
            'styleCaps' => true,
            'styleInitialQuotes' => true,
            'styleNumbers' => true,
            'urlWrap' => true,
            'wrapHardHyphens' => true,
            'hyphenationOptions' => ['headings', 'titleCase', 'allCaps'],
            'hyphenationLanguage' => 'en-US',
            'hyphenationExceptions' => 'ProcessWire',
            'exclusions' => 'code head kbd object option pre samp var math .vCard .noTypographer',
        ];
    }

    /**
     * Render input fields on config Page.
     * @return Inputfields
     */
    public function getInputFields()
    {
        // Start inputfields
        $inputfields = parent::getInputfields();

        // Disabler
        $inputfields->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'enabled',
            'label' => $this->_('Enable Typographer'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Information
        $inputfields->add($this->buildInputField('InputfieldMarkup', array(
            'id' => 'information',
            'value' => <<<HTML
                <p>This textformatter is a ProcessWire wrapper for the awesome PHP Typography class by KINGdesk LLC. Like Smartypants, it supercharges text fields with enhanced typography, such as Smart Quotes, hyphenation, and much more. For more details about what the typographer does, uncheck 'Use Typographer Defaults' below and read the configuration field descriptions that follow.</p>
                <p><b>Stylesheet:</b> You should make use of the stylesheet included with the module. In your template, simply call <code>print \$modules->TextformatterTypographer->styles();</code>. Alternatively, you are welcome to use your own stylesheet based on the original, giving consideration to your font-family and size.</p>
                <p><b>Note:</b> It is recommended that this be the last Textformatter on the list for your fields.</p>
HTML
            ,
            'collapsed'=>Inputfield::collapsedNo,
        )));

        // Use Defaults
        $inputfields->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'defaults',
            'label' => $this->_('Use Typographer Defaults (enable everything)'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'showIf' => 'enabled=1',
        ]));

        // Smart Fieldset
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Smart Options'),
            'showIf' => 'defaults=0,enabled=1',
        ]);

        // Smart Quote Fieldset
        $fieldsetSmartQuotes = $this->buildInputField('InputfieldFieldset', [
            'label' => 'Smart Quotes',
            'collapsed' => Inputfield::collapsedNever,
        ]);

        // Smart Quotes
        $fieldsetSmartQuotes->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartQuotes',
            'description' => $this->_("This option will transform straight quotes and backticks into contextually-aware curly quotes, primes, apostrophes, and even double-low-9-quotemarks."),
            'label' => $this->_('Enable Smart Quotes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 35,
        ]));

        // Initial Quote Wrapping
        $fieldsetSmartQuotes->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleInitialQuotes',
            'description' => $this->_("When enabled, Smart Quotes will be wrapped in a span element for CSS styling, such as hanging punctuation."),
            'label' => $this->_('Wrapping'),
            'label2' => $this->_('Wrap initial quotes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 30,
            'showIf' => 'smartQuotes=1',
        ]));

        // Initial Quote Tags
        $fieldsetSmartQuotes->add($this->buildInputField('InputfieldText', [
            'name+id' => 'initialQuoteTags',
            'description' => $this->_("In which block-level HTML elements may initial quotes be styled?"),
            'notes' => $this->_("Separate each with a space. Defaults are: `p h1 h2 h3 h4 h5 h6 blockquote li dd dt`"),
            'label' => $this->_('Elements'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 35,
            'showIf' => 'smartQuotes=1',
        ]));

        // Add Smart Quotes Fieldset
        $fieldset->add($fieldsetSmartQuotes);

        // Smart Dashes
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartDashes',
            'description' => $this->_("This option will transform minus-hyphens and multi-minus-hyphens into contextually aware en and em dashes and no-break-hyphens for phone numbers."),
            'label' => $this->_('Smart Dashes'),
            'label2' => $this->_('Enable Smart Dashes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Ellipses
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartEllipses',
            'description' => $this->_("This option will transform three consecutive periods to an ellipsis character."),
            'label' => $this->_('Smart Ellipses'),
            'label2' => $this->_('Enable Smart Ellipses'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Diacritics
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartDiacritics',
            'description' => $this->_("This option will automatically search for any that should utilise diacritics, and ensures that they do. For example, 'creme brulee' would transform to 'crème brûlée'. Note that words that alternate in meaning when used with diacritics, like resume/résumé, divorce/divorcé and expose/exposé, will not be transformed."),
            'notes' => $this->_('Note that diacritics are only available in the default `en-US` language. More languages will be made available soon.'),
            'label' => $this->_('Smart Diacritics'),
            'label2' => $this->_('Enable Smart Diacritics'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Marks
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartMarks',
            'description' => $this->_("This option will transform mark codes to their proper marks. (r), (c), (tm), (sm), and (p) are transformed to ®, ©, ™, ℠, and ℗, respectively."),
            'label' => $this->_('Smart Marks'),
            'label2' => $this->_('Enable Smart Marks'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Math
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartMath',
            'description' => $this->_("This option will correctly transform minus, division, and multiplication marks into their proper equivalents."),
            'label' => $this->_('Smart Math'),
            'label2' => $this->_('Enable Smart Math'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Exponents
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartExponents',
            'description' => $this->_("This option will identify exponents and superscript them accordingly."),
            'label' => $this->_('Smart Exponents'),
            'label2' => $this->_('Enable Smart Exponents'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Fractions
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartFractions',
            'description' => $this->_("This option will identify fractions and then raise the numerator, lower the denominator and replace the forward-slash with a fraction-slash."),
            'label' => $this->_('Smart Fractions'),
            'label2' => $this->_('Enable Smart Fractions'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Smart Ordinals
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartOrdinalSuffix',
            'description' => $this->_("This option will identify numbers followed by an ordinal suffix and then superscript the ordinal."),
            'label' => $this->_('Smart Ordinals'),
            'label2' => $this->_('Enable Smart Ordinals'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Add Smart fieldset
        $inputfields->add($fieldset);

        // Hyphenation Fieldset
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Hyphenation'),
            'showIf' => 'defaults=0,enabled=1',
        ]);

        // Hyphenation Language (blank to disable)
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'hyphenationLanguage',
            'label' => $this->_('Language Code'),
            'description' => $this->_('Set the hyphenation language, or set blank to turn off hyphenation.'),
            'notes' => $this->_('See the `**\vendor\debach\php-typography\lang**` directory for a list of languages that hyphenation supports.Default is `en-US`.'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
        ]));

        // Hyphenatation Options
        $fieldset->add($this->buildInputField('InputfieldCheckboxes', [
            'name+id' => 'hyphenationOptions',
            'label' => $this->_('Options'),
            'description' => $this->_('Hyphenate the following:'),
            'options' => [
                'headings' => 'Headings',
                'titleCase' => 'Title Case Words',
                'allCaps' => 'ALL CAPS Words',
            ],
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 20,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Hyphenation Exceptions
        $fieldset->add($this->buildInputField('InputfieldText', [
            'name+id' => 'hyphenationExceptions',
            'label' => $this->_('Word Exceptions'),
            'description' => $this->_('If you would like to exclude specific words from being hyphenated, provide them below.'),
            'notes' => $this->_('Separate multiple words with a pipe: `|`'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Add Hyphenation fieldset
        $inputfields->add($fieldset);

        // Widow Protection
        $inputfields->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'dewidow',
            'description' => $this->_("When enabled, Widow Protection will attempt to protect widows, the last words in block level texts that fall to their own line. Widows get lonely when they are all alone. When Widow Protection is turned on, widows are kept company by the injection of a non-​breaking space between them and the previous word, causing both words to drop to the next line together. No more loneliness."),
            'label' => $this->_('Widow Protection'),
            'label2' => $this->_('Protect widows'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'showIf' => 'defaults=0,enabled=1',
        ]));

        // Styles & Wrappers Fieldset
        $fieldset = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Stylers & Wrappers'),
            'showIf' => 'defaults=0,enabled=1',
        ]);

        // Style CAPS
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleCaps',
            'description' => $this->_("This option wraps consecutive capital letters in a span element for CSS styling."),
            'notes' => $this->_('The capital letters will be wrapped in `<span class="caps"></span>`.'),
            'label' => $this->_('Capital Letter Styiling'),
            'label2' => $this->_('Wrap capital letters in span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Style Numbers
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleNumbers',
            'description' => $this->_("This option wraps numbers in a span element for CSS styling."),
            'notes' => $this->_('The numbers will be wrapped in `<span class="numbers"></span>`.'),
            'label' => $this->_('Number Styling'),
            'label2' => $this->_('Wrap numbers in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Ampersands
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleAmpersands',
            'description' => $this->_("This option wraps ampersands (&amp;) in a span element for CSS styling."),
            'notes' => $this->_('The ampersand will be wrapped in `<span class="amp"></span>`.'),
            'label' => $this->_('Ampersand Styling'),
            'label2' => $this->_('Wrap ampersands in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Hard Hyphens
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'wrapHardHyphens',
            'description' => $this->_("Some browsers do not allow words such as 'mother-in-law' to wrap after a hard hyphen at line's end. Enabling this option will allow such wrapping by inserting a zero-width-space."),
            'label' => $this->_('Hard Hyphen Wrapping'),
            'label2' => $this->_('Wrap hard hyphens'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Email Addresses
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'emailWrap',
            'description' => $this->_("Email addresses can get fairly lengthy, and not all browsers will allow them break apart and line's end for efficient line-wrapping. This method will enable wrapping of email addresses by strategically inserting a zero-width-space after every non-alphanumeric character in the email address."),
            'label' => $this->_('Email Address Wrapping'),
            'label2' => $this->_('Wrap email addresses'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap URLs
        $fieldset->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'urlWrap',
            'description' => $this->_("Much like email addresses, URLs can get fairly lengthy, and not all browsers will allow them break apart and line's end for efficient line-wrapping. This method will enable wrapping of URLs by strategically inserting zero-width-spaces. Wrapping points are conservatively inserted into the domain portion of the URL, and aggressively added to the subsequent path."),
            'label' => $this->_('URL Wrapping'),
            'label2' => $this->_('Wrap URLs'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Add Wrappers fieldset
        $inputfields->add($fieldset);

        // Exclude elements, classes, and IDs
        $inputfields->add($this->buildInputField('InputfieldText', [
            'name+id' => 'exclusions',
            'description' => $this->_("Do not process the content of the following elements and elements with specific classes or identifiers:"),
            'notes' => $this->_("Separate each with a space. Elements must be named without their wrappers. Each class must be prefixed with a period (`.`). Identifiers must be prefixed with a hash (`#`).\nDefault is: `code head kbd object option pre samp var math .vCard .noTypographer`"),
            'label' => $this->_('Exclusions'),
            'collapsed' => Inputfield::collapsedNever,
            'showIf' => 'enabled=1,defaults=0',
        ]));

        return $inputfields;
    }

    /**
     * Given a fieldtype, create, populate, and return an Inputfield
     * @param  string       $fieldNameId
     * @param  array        $meta
     * @return Inputfield
     */
    protected function buildInputField($fieldNameId, $meta)
    {
        $field = wire('modules')->get($fieldNameId);
        foreach ($meta as $metaNames => $metaInfo) {
            $metaNames = explode('+', $metaNames);
            foreach ($metaNames as $metaName) {
                $field->$metaName = $metaInfo;
            }
        }
        return $field;
    }
}
