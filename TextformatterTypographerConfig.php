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
            'dewidow' => true,
            'diacriticCustomReplacements' => '',
            'diacriticLanguage' => 'en-US',
            'emailWrap' => true,
            'exclusions' => 'code head kbd object option pre samp var math .vCard .noTypographer',
            'fractionSpacing' => false,
            'frenchPunctuationSpacing' => false,
            'hyphenationExceptions' => 'ProcessWire',
            'hyphenationLanguage' => 'en-GB',
            'hyphenationOptions' => ['headings', 'titleCase', 'allCaps'],
            'initialQuoteTags' => 'p h1 h2 h3 h4 h5 h6 blockquote li dd dt',
            'singleCharacterWordSpacing' => true,
            'smartDashes' => true,
            'smartDashesStyle' => 'international',
            'smartDiacritics' => true,
            'smartEllipses' => true,
            'smartExponents' => true,
            'smartFractions' => true,
            'smartMarks' => true,
            'smartMath' => true,
            'smartOrdinalSuffix' => true,
            'smartQuotes' => true,
            'spaceCollapse' => true,
            'styleAmpersands' => true,
            'styleCaps' => true,
            'styleHangingPunctuation' => true,
            'styleInitialQuotes' => true,
            'styleNumbers' => true,
            'urlWrap' => true,
            'wrapHardHyphens' => true,
            // 'dashSpacing', #reserved
            // 'unitSpacing' => true, #reserved
            // 'units' => true, #reserved
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

        // Information
        $inputfields->add($this->buildInputField('InputfieldMarkup', [
            'id' => 'information',
            'value' => <<<HTML
                <p>This textformatter is a ProcessWire wrapper for the awesome PHP Typography class, originally authored by KINGdesk LLC and enhanced by Peter Putzer. Like Smartypants, it supercharges text fields with enhanced typography and typesetting, such as smart quotations, hyphenation in 59 languages, ellipses, copyright-, trade-, and service-marks, math symbols, and more. It is also able to wrap certain parts of your text in <code>span</code> tags for the purposes of styling them. A good example of this would be hanging punctuation for paragraphs that begin with quotation marks.</p>
                <p>To learn more about how all these features work, see the descriptions in the configuration-fields below.</p>
                <p><b>Stylesheet:</b> You should make use of the stylesheet included with the module if you are using initial quote-wrapping, hanging punctuation, or capital/number/character-wrapping. The stylesheet can be found in the module's directory. It is recommended that you copy this stylesheet to your assets directory or include in your main stylesheet, and then modify it according to your font family and size.</p>
                <p><b>Notes:</b></p>
                <ol>
                    <li style="margin:0 1em;padding-left:.5em">This textformatter can become quite heavy on resources which, in effect, slows down response-times. It is <b>highly recommended</b> that you cache your templates or use the commercial <a href="https://processwire.com/api/modules/procache/" title="Go to the API docs for ProCache">ProCache</a> by Ryan.</li>
                    <li style="margin:0 1em;padding-left:.5em">It is recommended that this be the last Textformatter on the list for your fields.</li>
                    <li style="margin:0 1em;padding-left:.5em">If you are using CKEditor, you should disable auto-correct features that enhance typography and typesetting.</li>
                </ol>
HTML
            ,
            'collapsed'=>Inputfield::collapsedNo,
        ]));

        // Smart Fieldset
        $fieldsetSmart = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Smart Options'),
            'collapsed' => Inputfield::collapsedNever,
        ]);

        // Smart Quotes Fieldset
        $fieldsetSmartQuotes = $this->buildInputField('InputfieldFieldset', [
            'label' => 'Quotes',
            'collapsed' => Inputfield::collapsedYes,
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
            'monospace' => true,
        ]));

        // Add Smart Quotes Fieldset
        $fieldsetSmart->add($fieldsetSmartQuotes);

        // Smart Dashes Fieldset
        $fieldsetSmartDashes = $this->buildInputField('InputfieldFieldset', [
            'label' => 'Dashes',
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Dashes
        $fieldsetSmartDashes->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartDashes',
            'description' => $this->_("This option will transform minus-hyphens and multi-minus-hyphens into contextually aware en and em dashes and no-break-hyphens for phone numbers."),
            'label' => $this->_('Enable Smart Dashes'),
            'label2' => $this->_('Enable Smart Dashes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 30,
        ]));

        // Dashes Style
        $fieldsetSmartDashes->add($this->buildInputField('InputfieldRadios', [
            'name+id' => 'smartDashesStyle',
            'description' => $this->_("Choose between international- and US-English dash styles."),
            'notes' => $this->_('The international dash style uses en-dashes in both the parenthetical and interval contexts, whereas the US dash style uses an em-dash in the parenthetical context and an en-dash in the interval-context. In terms if spacing, the international dash style uses a normal space in the parenthetical context and hair-space in the interval context, where as the US dash style uses a thin-space in both contexts. **Default:** International-English'),
            'label' => $this->_('Dash Style'),
            'options' => [
                'international' => 'International-English',
                'traditionalUS' => 'US-English',
            ],
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 70,
            'showIf' => 'smartDashes=1',
        ]));

        // Add Smart Dashes Fieldset
        $fieldsetSmart->add($fieldsetSmartDashes);

        // Smart Diacritics Fieldset
        $fieldsetSmartDiacritics = $this->buildInputField('InputfieldFieldset', [
            'label' => 'Diacritics',
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Diacritics
        $fieldsetSmartDiacritics->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartDiacritics',
            'description' => $this->_("This option will automatically search for any that should utilise diacritics, and ensures that they do. For example, 'creme brulee' would transform to 'crème brûlée'. Note that words that alternate in meaning when used with diacritics, like resume/résumé, divorce/divorcé and expose/exposé, will not be transformed."),
            'label' => $this->_('Enable Smart Diacritics'),
            'label2' => $this->_('Enable Smart Diacritics'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 50,
            'autocheck' => true,
        ]));

        // Smart Diacritics Language
        $fieldsetSmartDiacritics->add($this->buildInputField('InputfieldRadios', [
            'name+id' => 'diacriticLanguage',
            'description' => $this->_("Select the diacritic language."),
            'notes' => $this->_('**Note:** Note that the difference between the two is small. See the `**/lib/diacritics**` directory to see how different they are. **Sensible Default:** US-English'),
            'label' => $this->_('Language'),
            'options' => [
                'en-US' => 'US-English',
                'de-DE' => 'German (Standard)',
            ],
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
            'showIf' => 'smartDiacritics=1',
        ]));

        // Diacritic Custom Replacements
        $fieldsetSmartDiacritics->add($this->buildInputField('InputfieldTextarea', [
            'name+id' => 'diacriticCustomReplacements',
            'description' => $this->_("If you would like to add your own custom diacritic replacements, specify them below in the format `**word=replacement**`, with each set on its own line."),
            'notes' => $this->_('You can also use a colon as a separator, and either separator may be surrounded by whitespace.'),
            'label' => $this->_('Custom Replacements'),
            'collapsed' => Inputfield::collapsedBlank,
            'autocheck' => true,
            'showIf' => 'smartDiacritics=1',
        ]));

        // Add Smart Diacritics Fieldset
        $fieldsetSmart->add($fieldsetSmartDiacritics);

        // Smart Fractions & Math Fieldset
        $fieldsetSmartMath = $this->buildInputField('InputfieldFieldset', [
            'label' => 'Fractions & Math',
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Fractions
        $fieldsetSmartMath->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartFractions',
            'description' => $this->_("This option will identify fractions and then raise the numerator, lower the denominator and replace the forward-slash with a fraction-slash."),
            'label' => $this->_('Enable Smart Fractions'),
            'label2' => $this->_('Enable Smart Fractions'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Fraction Spacing
        $fieldsetSmartMath->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'fractionSpacing',
            'description' => $this->_("When enabled, fractions will be spaced use a normal non-breaking space instead of a thin-space. This may be set even if smart fractions are not enabled."),
            'label' => $this->_('Fraction Spacing'),
            'label2' => $this->_('Enable fraction spacing'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Smart Math
        $fieldsetSmartMath->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartMath',
            'description' => $this->_("This option will correctly transform minus, division, and multiplication marks into their proper equivalents."),
            'label' => $this->_('Smart Math'),
            'label2' => $this->_('Enable Smart Math'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Smart Exponents
        $fieldsetSmartMath->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartExponents',
            'description' => $this->_("This option will identify exponents and superscript them accordingly."),
            'label' => $this->_('Smart Exponents'),
            'label2' => $this->_('Enable Smart Exponents'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Add Smart Fractions Fieldset
        $fieldsetSmart->add($fieldsetSmartMath);

        // Smart Ellipses
        $fieldsetSmart->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartEllipses',
            'description' => $this->_("This option will transform three consecutive periods to an ellipsis character."),
            'label' => $this->_('Ellipses'),
            'label2' => $this->_('Enable Smart Ellipses'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
        ]));

        // Smart Marks
        $fieldsetSmart->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartMarks',
            'description' => $this->_("This option will transform copyright-, trade-, and service-marks to their proper marks. (r), (c), (tm), (sm), and (p) are transformed to ®, ©, ™, ℠, and ℗, respectively."),
            'label' => $this->_('Marks'),
            'label2' => $this->_('Enable Smart Marks'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
        ]));

        // Smart Ordinals
        $fieldsetSmart->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'smartOrdinalSuffix',
            'description' => $this->_("This option will identify numbers followed by an ordinal suffix and then superscript the ordinal."),
            'label' => $this->_('Ordinals'),
            'label2' => $this->_('Enable Smart Ordinals'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
        ]));

        // French Punctuation Spacing
        // (placed in smart fieldset... for now.)
        $fieldsetSmart->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'frenchPunctuationSpacing',
            'description' => $this->_("When enabled, this adds whitespace before certain punctuation marks, such as a colon, as in French custom."),
            'label' => $this->_('French Punctuation Spacing'),
            'label2' => $this->_('Enable French punctuation spacing'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
        ]));

        // Space Collapsing
        // (placed in smart fieldset... for now.)
        $fieldsetSmart->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'spaceCollapse',
            'description' => $this->_("Collapse extra spaces into one space."),
            'label' => $this->_('Space Collapsing'),
            'label2' => $this->_('Collapse extra spacing'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
        ]));

        // Add Smart fieldset
        $inputfields->add($fieldsetSmart);

        // Hyphenation Fieldset
        $fieldsetHyphenation = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Hyphenation'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Hyphenation Language (blank to disable)
        $fieldsetHyphenation->add($this->buildInputField('InputfieldText', [
            'name+id' => 'hyphenationLanguage',
            'label' => $this->_('Language Code'),
            'description' => $this->_('Set the hyphenation language, or set blank to turn off hyphenation.'),
            'notes' => $this->_('See the `**/lib/lang**` directory for a list of the 59 languages that hyphenation supports. Default is `en-GB`.'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
        ]));

        // Hyphenatation Options
        $fieldsetHyphenation->add($this->buildInputField('InputfieldCheckboxes', [
            'name+id' => 'hyphenationOptions',
            'label' => $this->_('Options'),
            'description' => $this->_('Hyphenate the following:'),
            'options' => [
                'headings' => 'Headings',
                'titleCase' => 'Title Case Words',
                'allCaps' => 'ALL CAPS Words',
                'compunds' => 'Compound-words',
            ],
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 20,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Hyphenation Exceptions
        $fieldsetHyphenation->add($this->buildInputField('InputfieldText', [
            'name+id' => 'hyphenationExceptions',
            'label' => $this->_('Word Exceptions'),
            'description' => $this->_('If you would like to exclude specific words from being hyphenated, provide them below.'),
            'notes' => $this->_('Separate multiple words with a pipe: `|`'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Add Hyphenation fieldset
        $inputfields->add($fieldsetHyphenation);

        // Widows and Single Characters Fieldset
        $fieldsetWidows = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Widows and Single Characters'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Widow Protection
        $fieldsetWidows->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'dewidow',
            'description' => $this->_("When enabled, widow protection will attempt to protect widows, the last words in block level texts that fall to their own line. Widows get lonely when they are all alone. When widow protection is turned on, widows are kept company by the injection of a non-​breaking space between them and the previous word, causing both words to drop to the next line together. No more loneliness."),
            'label' => $this->_('Widow Protection'),
            'label2' => $this->_('Protect widows'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Single Character Protection
        $fieldsetWidows->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'singleCharacterWordSpacing',
            'description' => $this->_("Similar in behaviour to widow protection, this will prevent single-character words from wrapping over to a new line."),
            'label' => $this->_('Single-word Character Proection'),
            'label2' => $this->_('Protect single-word characters'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Add Widows and Single Characters fieldset
        $inputfields->add($fieldsetWidows);

        # RESERVED
        // // Units Fieldset
        // $fieldsetUnits = $this->buildInputField('InputfieldFieldset', [
        //     'label' => $this->_('Units and Values'),
        //     'collapsed' => Inputfield::collapsedYes,
        // ]);

        // // Disable Unit Spacing
        // $fieldsetUnits->add($this->buildInputField('InputfieldCheckbox', [
        //     'name+id' => 'unitSpacing',
        //     'description' => $this->_("When checked, this will ensure values are glued to their units. For example, '1 KB' would be converted to '1KB'."),
        //     'label' => $this->_('Unit Spacing'),
        //     'label2' => $this->_('Glue units to their values'),
        //     'collapsed' => Inputfield::collapsedNever,
        //     'autocheck' => true,
        //     'columnWidth' => 50,
        // ]));

        // // Add Units fieldset
        // $inputfields->add($fieldsetUnits);
        # /RESERVED

        // Styles & Wrappers Fieldset
        $fieldsetStylesWrappers = $this->buildInputField('InputfieldFieldset', [
            'label' => $this->_('Styles & Wrappers'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Style hanging punctuation
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleHangingPunctuation',
            'description' => $this->_("This option enables the wrapping of certain punctuation and wide characters in push/pull spans. This feature is similar to optical margins."),
            'note' => $this->_("Please note that this does not affect the setting to wrap initial quotes. See the stylesheet for the difference."),
            'label' => $this->_('Hanging Punctuation'),
            'label2' => $this->_('Enable hanging punctuation'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Style CAPS
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleCaps',
            'description' => $this->_("This option wraps consecutive capital letters in a span element for CSS styling."),
            'notes' => $this->_('The capital letters will be wrapped in `<span class="caps"></span>`.'),
            'label' => $this->_('Capital Letter Styiling'),
            'label2' => $this->_('Wrap capital letters in span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Style Numbers
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleNumbers',
            'description' => $this->_("This option wraps numbers in a span element for CSS styling."),
            'notes' => $this->_('The numbers will be wrapped in `<span class="numbers"></span>`.'),
            'label' => $this->_('Number Styling'),
            'label2' => $this->_('Wrap numbers in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Ampersands
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'styleAmpersands',
            'description' => $this->_("This option wraps ampersands (&amp;) in a span element for CSS styling."),
            'notes' => $this->_('The ampersand will be wrapped in `<span class="amp"></span>`.'),
            'label' => $this->_('Ampersand Styling'),
            'label2' => $this->_('Wrap ampersands in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Hard Hyphens
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'wrapHardHyphens',
            'description' => $this->_("Some browsers do not allow words such as 'mother-in-law' to wrap after a hard hyphen at line's end. Enabling this option will allow such wrapping by inserting a zero-width-space."),
            'label' => $this->_('Hard Hyphen Wrapping'),
            'label2' => $this->_('Wrap hard hyphens'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Email Addresses
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'emailWrap',
            'description' => $this->_("Email addresses can get fairly lengthy, and not all browsers will allow them break apart at line's end for efficient line-wrapping. This method will enable wrapping of email addresses by strategically inserting a zero-width-space after every non-alphanumeric character in the email address."),
            'label' => $this->_('Email Address Wrapping'),
            'label2' => $this->_('Wrap email addresses'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap URLs
        $fieldsetStylesWrappers->add($this->buildInputField('InputfieldCheckbox', [
            'name+id' => 'urlWrap',
            'description' => $this->_("Much like email addresses, URLs can get fairly lengthy, and not all browsers will allow them break apart at line's end for efficient line-wrapping. This method will enable wrapping of URLs by strategically inserting zero-width-spaces. Wrapping points are conservatively inserted into the domain portion of the URL, and aggressively added to the subsequent path."),
            'label' => $this->_('URL Wrapping'),
            'label2' => $this->_('Wrap URLs'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Add Wrappers fieldset
        $inputfields->add($fieldsetStylesWrappers);

        // Exclude elements, classes, and IDs
        $inputfields->add($this->buildInputField('InputfieldText', [
            'name+id' => 'exclusions',
            'description' => $this->_("Do not process the content of the following elements and elements with specific classes or identifiers:"),
            'notes' => $this->_("Separate each with a space. Elements must be named without their wrappers. Each class must be prefixed with a period (`.`). Identifiers must be prefixed with a hash (`#`).\nDefault is: `code head kbd object option pre samp var math .vCard .noTypographer`"),
            'label' => $this->_('Exclusions'),
            'collapsed' => Inputfield::collapsedYes,
            'monospace' => true,
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
                if ($metaName === 'monospace' && $metaInfo === true) {
                    $field->setAttribute('style',
                        "font-family:Menlo,Monaco,'Andale Mono','Lucida Console','Courier New',monospace;" .
                        "font-size:14px;padding:4px"
                    );
                }
            }
        }
        return $field;
    }
}
