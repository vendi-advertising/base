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
        'mb_str_functions' => true,
        'no_php4_constructor' => true,
        'no_short_echo_tag' => true,
        'non_printable_character' => ['use_escape_sequences_in_strings' => true],
        'ordered_imports' => ['sortAlgorithm' => 'alpha'],
        'short_scalar_cast' => true,
        'single_quote' => true,
        'strict_comparison' => true,
    ])
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
;
