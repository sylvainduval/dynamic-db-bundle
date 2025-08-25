<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql;

use RuntimeException;
use SylvainDuval\DynamicDbBundle\Domain\Field;
use SylvainDuval\DynamicDbBundle\Schema\MySql;

final class FieldGeneratorFactory
{
	/** @var array<class-string<Field\FieldInterface>, FieldGeneratorInterface> */
	private array $generators = [];

	/** @var array<string, string> */
	private array $map = [
		Field\NumericField::class => MySql\Field\NumericFieldGenerator::class,
		Field\TextField::class => MySql\Field\TextFieldGenerator::class,
   ];

	public function getGenerator(Field\FieldInterface $field): FieldGeneratorInterface
	{
		/** @var class-string<Field\FieldInterface> $fieldClass */
		foreach ($this->map as $fieldClass => $generatorClass) {
			if ($field instanceof $fieldClass) {
				if (!isset($this->generators[$fieldClass])) {
					/** @var FieldGeneratorInterface $generator */
					$generator = new $generatorClass();
					$this->generators[$fieldClass] = $generator;
				}
				return $this->generators[$fieldClass];
			}
		}

		throw new RuntimeException('Field not supported');
	}
}
