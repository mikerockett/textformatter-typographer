<?php

class Typographer extends PHP_Typography\PHP_Typography
{
    /**
     * CSS classes
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
}
