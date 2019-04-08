<?php

$finder = PhpCsFixer\Finder::create()
    ->in('src/')
;

return PhpCsFixer\Config::create()
    ->setRules([
        '@PSR1' => true,
        '@PSR2' => true,
        '@Symfony' =>  true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,

        //Don't set this to true until it can be proven that strlen() is either
        //intended for byte count or that only ASCII is ever used.
        'mb_str_functions' => false,

        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'non_printable_character' => ['use_escape_sequences_in_strings' => true],
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'short_scalar_cast' => true,
        'single_quote' => true,

        //Setting this to true is breaking things
        'strict_comparison' => false,
        'concat_space' => ['spacing' => 'one'],
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
;
