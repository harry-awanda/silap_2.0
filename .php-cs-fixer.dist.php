<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
  ->in(__DIR__)
  ->exclude(['vendor', 'node_modules', 'public/assets', 'bootstrap/cache', 'storage', 'plan'])
  ->notName('*.blade.php')
  ->append([__FILE__, __DIR__.'/artisan']);

return (new Config())
  ->setIndent('  ')
  ->setLineEnding("\n")
  ->setCacheFile(__DIR__.'/storage/framework/cache/php-cs-fixer.cache')
  ->setRules([
    '@PER-CS2.0' => true,
    'braces_position' => [
      'classes_opening_brace' => 'same_line',
      'functions_opening_brace' => 'same_line',
      'control_structures_opening_brace' => 'same_line',
      'anonymous_functions_opening_brace' => 'same_line',
      'anonymous_classes_opening_brace' => 'same_line',
    ],
    'array_syntax' => ['syntax' => 'short'],
    'array_indentation' => true,
    'concat_space' => ['spacing' => 'none'],
    'not_operator_with_successor_space' => true,
    'ordered_imports' => ['sort_algorithm' => 'alpha'],
    'no_unused_imports' => true,
    'single_quote' => true,
  ])
  ->setFinder($finder);
