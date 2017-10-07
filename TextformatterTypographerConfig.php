<?php

/**
 * TextformatterTypographer is a ProcessWire implemetation of PHP Typography by KINGdesk LLC.
 * Author: Mike Rockett
 * PhpTypography: http://kingdesk.com/projects/php-tyography/
 * @license PhpTypography: GNU GPL 2.0; TextformatterTypograher: MIT
 */

require_once __DIR__ . '/loader.php';

use Rockett\DebugTrait as Debugs;
use Rockett\FieldsTrait as UsesFields;

class TextformatterTypographerConfig extends ModuleConfig
{
    use UsesFields, Debugs;

    protected $savedConfig;

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
            'emailWrap' => false,
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
            'styleAmpersands' => false,
            'styleCaps' => false,
            'styleHangingPunctuation' => false,
            'styleInitialQuotes' => false,
            'styleNumbers' => false,
            'urlWrap' => false,
            'wrapHardHyphens' => true,
            'unitSpacing' => false,
            'numberedAbbreviationSpacing' => true,
            'dashSpacing' => true,
            'trueNoBreakNarrowSpace' => false,

            /*
             * The following basic options are reserved
             * for a future release.
             * @reserved
             */
            // 'units',
        ];
    }

    /**
     * Render input fields on config Page.
     * @return Inputfields
     */
    public function getInputFields()
    {
        // Require the Composer autoloader and prevent
        // the ProcessWire FileCompile from touching anything.
        // Note: this is only done at method-call-time as it this is
        // not an autoload module.
        require_once(/*NoCompile*/__DIR__ . '/vendor/autoload.php');

        // We need to fetch this now (for rendering checks and crosses)
        // as ModuleConfig will override the data array with the defaults
        // the moment parent::getInputfields() is called.
        $this->savedConfig = $this->data;

        // Start inputfields
        $inputfields = parent::getInputfields();

        // Smart Fieldset
        $fieldsetSmart = $this->buildInputField('Fieldset', [
            'label' => $this->_('Smart Options'),
            'collapsed' => Inputfield::collapsedNever,
        ]);

        // Smart Quotes Fieldset
        $fieldsetSmartQuotes = $this->buildInputField('Fieldset', [
            'label' => 'Quotes',
            'icon' => $this->stateIcon('smartQuotes|styleInitialQuotes|initialQuoteTags'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Smart Quotes
        $fieldsetSmartQuotes->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartQuotes',
            'description' => $this->_('This option will transform straight quotes and backticks into contextually-aware curly quotes, primes, apostrophes, and even double-low-9-quotemarks.'),
            'label' => $this->_('Enable Smart Quotes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 35,
        ]));

        // Initial Quote Wrapping
        $fieldsetSmartQuotes->add($this->buildInputField('Checkbox', [
            'name+id' => 'styleInitialQuotes',
            'description' => $this->_('When enabled, Smart Quotes will be wrapped in a span element for CSS styling, such as hanging punctuation.'),
            'label' => $this->_('Wrapping'),
            'label2' => $this->_('Wrap initial quotes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 30,
            'showIf' => 'smartQuotes=1',
        ]));

        // Initial Quote Tags
        $fieldsetSmartQuotes->add($this->buildInputField('Text', [
            'name+id' => 'initialQuoteTags',
            'description' => $this->_('In which block-level HTML elements may initial quotes be styled?'),
            'notes' => $this->_('Separate each with a space. Defaults are: `p h1 h2 h3 h4 h5 h6 blockquote li dd dt`'),
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
        $fieldsetSmartDashes = $this->buildInputField('Fieldset', [
            'label' => 'Dashes',
            'icon' => $this->stateIcon('smartDashes'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Dashes
        $fieldsetSmartDashes->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartDashes',
            'description' => $this->_('This option will transform minus-hyphens and multi-minus-hyphens into contextually aware en and em dashes and no-break-hyphens for phone numbers.'),
            'label' => $this->_('Enable Smart Dashes'),
            'label2' => $this->_('Enable Smart Dashes'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 30,
        ]));

        // Dashes Style
        $fieldsetSmartDashes->add($this->buildInputField('Radios', [
            'name+id' => 'smartDashesStyle',
            'description' => $this->_('Choose between international- and US-English dash styles.'),
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
        $fieldsetSmartDiacritics = $this->buildInputField('Fieldset', [
            'label' => 'Diacritics',
            'icon' => $this->stateIcon('smartDiacritics'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Diacritics
        $fieldsetSmartDiacritics->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartDiacritics',
            'description' => $this->_("This option will automatically search for any that should utilise diacritics, and ensures that they do. For example, *creme brulee* would transform to *crème brûlée*. Note that words that alternate in meaning when used with diacritics, like *resume*/*résumé*, *divorce*/*divorcé* and *expose*/*exposé*, will not be transformed."),
            'label' => $this->_('Enable Smart Diacritics'),
            'label2' => $this->_('Enable Smart Diacritics'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 50,
            'autocheck' => true,
        ]));

        // Smart Diacritics Language
        $fieldsetSmartDiacritics->add($this->buildInputField('Select', [
            'name+id' => 'diacriticLanguage',
            'description' => $this->_('Select the diacritic language.'),
            'notes' => $this->_('**Note:** Note that the difference between the two is small. See the `**/vendor/mundschenk-at/php-typography/src/diacritics**` directory to see how different they are. **Sensible Default:** English (United States)'),
            'label' => $this->_('Language'),
            'options' => \Typographer\Typographer::get_diacritic_languages(),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
            'required' => true,
            'showIf' => 'smartDiacritics=1',
        ]));

        // Diacritic Custom Replacements
        $fieldsetSmartDiacritics->add($this->buildInputField('Textarea', [
            'name+id' => 'diacriticCustomReplacements',
            'description' => $this->_('If you would like to add your own custom diacritic replacements, specify them below in the format `**word=replacement**`, with each set on its own line.'),
            'notes' => $this->_('You can also use a colon as a separator, and either separator may be surrounded by whitespace.'),
            'label' => $this->_('Custom Replacements'),
            'collapsed' => Inputfield::collapsedBlank,
            'autocheck' => true,
            'showIf' => 'smartDiacritics=1',
        ]));

        // Add Smart Diacritics Fieldset
        $fieldsetSmart->add($fieldsetSmartDiacritics);

        // Smart Fractions & Math Fieldset
        $fieldsetSmartMath = $this->buildInputField('Fieldset', [
            'label' => 'Fractions & Math',
            'icon' => $this->stateIcon('smartFractions|fractionSpacing|smartMath|smartExponents'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Enable Smart Fractions
        $fieldsetSmartMath->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartFractions',
            'description' => $this->_('This option will identify fractions and then raise the numerator, lower the denominator and replace the forward-slash with a fraction-slash.'),
            'label' => $this->_('Enable Smart Fractions'),
            'label2' => $this->_('Enable Smart Fractions'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Fraction Spacing
        $fieldsetSmartMath->add($this->buildInputField('Checkbox', [
            'name+id' => 'fractionSpacing',
            'description' => $this->_('When enabled, fractions will be spaced use a normal non-breaking space instead of a thin-space. This may be set even if smart fractions are not enabled.'),
            'label' => $this->_('Fraction Spacing'),
            'label2' => $this->_('Enable fraction spacing'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Smart Math
        $fieldsetSmartMath->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartMath',
            'description' => $this->_('This option will correctly transform minus, division, and multiplication marks into their proper equivalents.'),
            'label' => $this->_('Smart Math'),
            'label2' => $this->_('Enable Smart Math'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Smart Exponents
        $fieldsetSmartMath->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartExponents',
            'description' => $this->_('This option will identify exponents and superscript them accordingly.'),
            'label' => $this->_('Smart Exponents'),
            'label2' => $this->_('Enable Smart Exponents'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Add Smart Fractions Fieldset
        $fieldsetSmart->add($fieldsetSmartMath);

        // Smart Ellipses
        $fieldsetSmart->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartEllipses',
            'description' => $this->_('This option will transform three consecutive periods to an ellipsis character.'),
            'label' => $this->_('Ellipses'),
            'label2' => $this->_('Enable Smart Ellipses'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
            'icon' => $this->stateIcon('smartEllipses'),
        ]));

        // Smart Marks
        $fieldsetSmart->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartMarks',
            'description' => $this->_('This option will transform copyright-, trade-, and service-marks to their proper marks. (r), (c), (tm), (sm), and (p) are transformed to ®, ©, ™, ℠, and ℗, respectively.'),
            'label' => $this->_('Marks'),
            'label2' => $this->_('Enable Smart Marks'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
            'icon' => $this->stateIcon('smartMarks'),
        ]));

        // Smart Ordinals
        $fieldsetSmart->add($this->buildInputField('Checkbox', [
            'name+id' => 'smartOrdinalSuffix',
            'description' => $this->_('This option will identify numbers followed by an ordinal suffix and then superscript the ordinal.'),
            'label' => $this->_('Ordinals'),
            'label2' => $this->_('Enable Smart Ordinals'),
            'collapsed' => Inputfield::collapsedYes,
            'autocheck' => true,
            'icon' => $this->stateIcon('smartOrdinalSuffix'),
        ]));

        // Widows and Single Characters Fieldset
        $fieldsetWidows = $this->buildInputField('Fieldset', [
            'label' => $this->_('Dewidowing'),
            'collapsed' => Inputfield::collapsedYes,
            'notes' => $this->_('Note that dewidowing takes precedence over other spacing (defined in the next section). If it is enabled, spacing options below could be overriden for widows.'),
            'icon' => $this->stateIcon('dewidow|singleCharacterWordSpacing'),
        ]);

        // Widow Protection
        $fieldsetWidows->add($this->buildInputField('Checkbox', [
            'name+id' => 'dewidow',
            'description' => $this->_('When enabled, word protection will attempt to protect widows, the last words in block level texts that fall to their own line. Widows get lonely when they are all alone. When word protection is turned on, widows are kept company by the injection of a non-​breaking space between them and the previous word, causing both words to drop to the next line together. No more loneliness.'),
            'label' => $this->_('Word Protection'),
            'label2' => $this->_('Protect widows'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Single Character Protection
        $fieldsetWidows->add($this->buildInputField('Checkbox', [
            'name+id' => 'singleCharacterWordSpacing',
            'description' => $this->_('Similar in behaviour to word protection, this will prevent single-character words from wrapping over to a new line.'),
            'label' => $this->_('Single-Character Word Proection'),
            'label2' => $this->_('Protect single-word characters'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Add Widows and Single Characters fieldset
        $fieldsetSmart->add($fieldsetWidows);

        // Smart Spacing Fieldset
        $fieldsetSmartSpacing = $this->buildInputField('Fieldset', [
            'label' => 'Other Spacing',
            'icon' => $this->stateIcon('unitSpacing|dashSpacing|numberedAbbreviationSpacing|frenchPunctuationSpacing|spaceCollapse|trueNoBreakNarrowSpace'),
            'notes' => $this->_('Note that dewidowing takes precedence over these options. If it is enabled, spacing options below could be overriden for, but only for widows.'),
            'collapsed' => Inputfield::collapsedYes,
        ]);

        // Unit Spacing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'unitSpacing',
            'description' => $this->_("When checked, this will ensure values are glued to their units. For example, **1 KB** would be converted to **1KB**."),
            'label' => $this->_('Revert Unit Spacing'),
            'label2' => $this->_('Glue units to their values'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Dash Spacing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'dashSpacing',
            'description' => $this->_("When checked, em- and en- dashes will be wrapped in a thin space."),
            'label' => $this->_('Dash Spacing'),
            'label2' => $this->_('Wrap dashes in a thin space'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Numbered Abbreviation Spacing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'numberedAbbreviationSpacing',
            'description' => $this->_("When checked, this will separate numbered abbreviations (such as **ISO 9000**) with a non-breaking space."),
            'label' => $this->_('Numbered Abbreviation Spacing'),
            'label2' => $this->_('Use a non-breaking space for numbered abbreviations.'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // French Punctuation Spacing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'frenchPunctuationSpacing',
            'description' => $this->_('When enabled, this adds whitespace before certain punctuation marks, such as a colon, as in French custom.'),
            'label' => $this->_('French Punctuation Spacing'),
            'label2' => $this->_('Enable French punctuation spacing'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Space Collapsing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'spaceCollapse',
            'description' => $this->_('Collapse extra contiguous spaces into a single space.'),
            'label' => $this->_('Space Collapsing'),
            'label2' => $this->_('Collapse extra spacing'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Space Collapsing
        $fieldsetSmartSpacing->add($this->buildInputField('Checkbox', [
            'name+id' => 'trueNoBreakNarrowSpace',
            'description' => $this->_('When enabled, a non-breaking narrow space (nnbsp) will be used instead of a normal non-breaking space (nbsp)'),
            'label' => $this->_('Non-breaking Space Type'),
            'label2' => $this->_('Use non-breaking narrow spaces'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
            'columnWidth' => 50,
        ]));

        // Add Smart Spacing fieldset
        $fieldsetSmart->add($fieldsetSmartSpacing);

        // Add Smart fieldset
        $inputfields->add($fieldsetSmart);

        // Hyphenation Fieldset
        $fieldsetHyphenation = $this->buildInputField('Fieldset', [
            'label' => $this->_('Hyphenation'),
            'collapsed' => Inputfield::collapsedYes,
            'notes' => $this->_('Hyphenation is not yet language-aware. If your site is multi-language, then a sensible default would be to set hyphenation to your default language.'),
            'icon' => $this->stateIcon('hyphenationLanguage'),
        ]);

        // Hyphenation Language (blank to disable)
        $fieldsetHyphenation->add($this->buildInputField('Select', [
            'name+id' => 'hyphenationLanguage',
            'label' => $this->_('Language Code'),
            'description' => $this->_('Set the hyphenation language, or set blank to turn off hyphenation.'),
            'notes' => $this->_('Default is **English (United Kingdom)**.'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
            'options' => \Typographer\Typographer::get_hyphenation_languages(),
        ]));

        // Hyphenatation Options
        $fieldsetHyphenation->add($this->buildInputField('Checkboxes', [
            'name+id' => 'hyphenationOptions',
            'label' => $this->_('Options'),
            'description' => $this->_('Hyphenate the following:'),
            'options' => [
                'headings' => 'Headings',
                'titleCase' => 'Title Case Words',
                'allCaps' => 'ALL CAPS Words',
                'compounds' => 'Compound-words',
            ],
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 20,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Hyphenation Exceptions
        $fieldsetHyphenation->add($this->buildInputField('Text', [
            'name+id' => 'hyphenationExceptions',
            'label' => $this->_('Word Exceptions'),
            'description' => $this->_('If you would like to exclude specific words from being hyphenated, provide them below.'),
            'notes' => $this->_('Separate multiple words with a space.'),
            'collapsed' => Inputfield::collapsedNever,
            'columnWidth' => 40,
            'showIf' => "hyphenationLanguage!=''",
        ]));

        // Add Hyphenation fieldset
        $inputfields->add($fieldsetHyphenation);

        // Styles & Wrappers Fieldset
        $fieldsetStylesWrappers = $this->buildInputField('Fieldset', [
            'label' => $this->_('Styles & Wrappers'),
            'collapsed' => Inputfield::collapsedYes,
            'icon' => $this->stateIcon('styleHangingPunctuation|styleCaps|styleNumbers|styleAmpersands|wrapHardHyphens|emailWrap|urlWrap'),
        ]);

        // Style hanging punctuation
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'styleHangingPunctuation',
            'description' => $this->_('This option enables the wrapping of certain punctuation and wide characters in push/pull spans. This feature is similar to optical margins.'),
            'note' => $this->_('Please note that this does not affect the setting to wrap initial quotes. See the stylesheet for the difference.'),
            'label' => $this->_('Hanging Punctuation'),
            'label2' => $this->_('Enable hanging punctuation'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Style CAPS
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'styleCaps',
            'description' => $this->_('This option wraps consecutive capital letters in a span element for CSS styling.'),
            'notes' => $this->_('The capital letters will be wrapped in `<span class="caps"></span>`.'),
            'label' => $this->_('Capital Letter Styiling'),
            'label2' => $this->_('Wrap capital letters in span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Style Numbers
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'styleNumbers',
            'description' => $this->_('This option wraps numbers in a span element for CSS styling.'),
            'notes' => $this->_('The numbers will be wrapped in `<span class="numbers"></span>`.'),
            'label' => $this->_('Number Styling'),
            'label2' => $this->_('Wrap numbers in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Ampersands
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'styleAmpersands',
            'description' => $this->_('This option wraps ampersands (&amp;) in a span element for CSS styling.'),
            'notes' => $this->_('The ampersand will be wrapped in `<span class="amp"></span>`.'),
            'label' => $this->_('Ampersand Styling'),
            'label2' => $this->_('Wrap ampersands in a span element'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Hard Hyphens
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'wrapHardHyphens',
            'description' => $this->_("Some browsers do not allow words such as *mother-in-law* to wrap after a hard hyphen at line’s end. Enabling this option will allow such wrapping by inserting a zero-width-space."),
            'label' => $this->_('Hard Hyphen Wrapping'),
            'label2' => $this->_('Wrap hard hyphens'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap Email Addresses
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'emailWrap',
            'description' => $this->_("Email addresses can get fairly lengthy, and not all browsers will allow them break apart at line’s end for efficient line-wrapping. This method will enable wrapping of email addresses by strategically inserting a zero-width-space after every non-alphanumeric character in the email address."),
            'label' => $this->_('Email Address Wrapping'),
            'label2' => $this->_('Wrap email addresses'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Wrap URLs
        $fieldsetStylesWrappers->add($this->buildInputField('Checkbox', [
            'name+id' => 'urlWrap',
            'description' => $this->_("Much like email addresses, URLs can get fairly lengthy, and not all browsers will allow them break apart at line’s end for efficient line-wrapping. This method will enable wrapping of URLs by strategically inserting zero-width-spaces. Wrapping points are conservatively inserted into the domain portion of the URL, and aggressively added to the subsequent path."),
            'label' => $this->_('URL Wrapping'),
            'label2' => $this->_('Wrap URLs'),
            'collapsed' => Inputfield::collapsedNever,
            'autocheck' => true,
        ]));

        // Add Wrappers fieldset
        $inputfields->add($fieldsetStylesWrappers);

        // Exclude elements, classes, and IDs
        $inputfields->add($this->buildInputField('Text', [
            'name+id' => 'exclusions',
            'description' => $this->_('Do not process the content of the following elements and elements with specific classes or identifiers:'),
            'notes' => $this->_("Separate each with a space. Elements must be named without their wrappers. Each class must be prefixed with a period (`.`). Identifiers must be prefixed with a hash (`#`).\nDefault is: `code head kbd object option pre samp var math .vCard .noTypographer`"),
            'label' => $this->_('Exclusions'),
            'collapsed' => Inputfield::collapsedYes,
            'monospace' => true,
            'icon' => $this->stateIcon('exclusions'),
        ]));

        $supportText = $this->wire('sanitizer')->entitiesMarkdown($this->_('Typographer is proudly [open-source](http://opensource.com/resources/what-open-source) and is [free to use](https://en.wikipedia.org/wiki/Free_software) for personal and commercial projects. Please consider [making a small donation](https://rockett.pw/donate) in support of the development of Typographer and other modules.'), ['fullMarkdown' => true]);
        $inputfields->add($this->buildInputField('Markup', [
            'id' => 'support_development',
            'label' => $this->_('Support Development'),
            'value' => $supportText,
            'icon' => 'paypal',
            'collapsed' => Inputfield::collapsedYes,
        ]));

        $this->config->scripts->add($this->urls->httpSiteModules . 'TextformatterTypographer/assets/config.js');

        return $inputfields;
    }

    /**
     * Select a FontAwesome icon based on the state of a
     * configuration value (true/false).
     * Also supports multiple configuration values, separated
     * by a pipe. If some are true and others are false, an
     * indeterminate icon should be used.
     * @param  boolean $configName
     * @return mixed
     */
    protected function stateIcon($configName, $icons = ['check-square-o', 'square-o', 'minus-square-o'])
    {
        $configs = explode('|', $configName);

        // If multiple configs are defined, then we need
        // to loop through them.
        if (count($configs) > 1) {
            $fetchedConfigs = array_intersect_key(
                $this->savedConfig,
                array_flip($configs)
            );
            if (count(array_unique($fetchedConfigs)) === 1) {
                return $icons[(int) !current($fetchedConfigs)];
            }
            return $icons[2];
        }

        // Otherwise, we're just getting the icon for one value.
        if (!isset($this->savedConfig[$configName])) {
            return 'warning';
        }

        return $icons[(int) !$this->savedConfig[$configName]];
    }
}
