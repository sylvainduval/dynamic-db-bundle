<?php

$finder = (new PhpCsFixer\Finder())
	->in(__DIR__)
;

return (new PhpCsFixer\Config())
	->setRiskyAllowed(true)
	->setRules([
		'@PSR12' => true,
		'array_syntax' => ['syntax' => 'short'],
		'no_multiline_whitespace_around_double_arrow' => true,
		'no_whitespace_before_comma_in_array' => true,
		'class_definition' => true,
		'no_blank_lines_after_class_opening' => true,
		'ordered_class_elements' => true,
		'ordered_interfaces' => true,
        'ordered_imports' => true,
		'no_unused_imports' => true,
		'no_leading_import_slash' => true,
		'single_line_after_imports' => true,
		'combine_consecutive_issets' => true,
		'combine_consecutive_unsets' => true,
		'no_spaces_after_function_name' => true,
		'method_argument_space' => true,
		'function_declaration' => true,
		'trailing_comma_in_multiline' => true,
		'no_useless_else' => true,
		'elseif' => true,
		'single_line_comment_style' => true,
		'multiline_comment_opening_closing' => true,
		'short_scalar_cast' => true,
		'cast_spaces' => true,
		'class_reference_name_casing' => true,
		'single_line_empty_body' => true,
		'no_trailing_comma_in_singleline' => true,
		'no_multiple_statements_per_line' => true,
		'native_function_invocation' => [
			'include' => ['@all'],
			'scope' => 'namespaced',
			'strict' => true,
      ],
    ])
    ->setFinder($finder)
    ->setIndent("\t")
    ->setLineEnding("\r\n")
;