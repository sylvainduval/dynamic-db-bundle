<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MariaDb;

use RuntimeException;
use SylvainDuval\DynamicDbBundle\Domain\Field;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;
use SylvainDuval\DynamicDbBundle\Schema\FieldGeneratorFactoryInterface;
use SylvainDuval\DynamicDbBundle\Schema\MariaDb;
use SylvainDuval\DynamicDbBundle\Schema\MySql;

final class FieldGeneratorFactory implements FieldGeneratorFactoryInterface
{
	/** @var array<class-string<Field\FieldInterface>, FieldDefinitionGeneratorInterface> */
	private array $generators = [];

	/** @var array<string, string> */
	private array $map = [
		Field\Boolean::class => MySql\Field\BooleanFieldGenerator::class,
		Field\Json::class => MariaDb\Field\JsonFieldGenerator::class,
		Field\Numeric::class => MySql\Field\NumericFieldGenerator::class,
		Field\Text::class => MySql\Field\TextFieldGenerator::class,
		Field\Uuid::class => MariaDb\Field\UuidFieldGenerator::class,
   ];

	public function getGenerator(Field\FieldInterface $field): FieldDefinitionGeneratorInterface
	{
		/** @var class-string<Field\FieldInterface> $fieldClass */
		foreach ($this->map as $fieldClass => $generatorClass) {
			if ($field instanceof $fieldClass) {
				if (!isset($this->generators[$fieldClass])) {
					/** @var FieldDefinitionGeneratorInterface $generator */
					$generator = new $generatorClass();
					$this->generators[$fieldClass] = $generator;
				}
				return $this->generators[$fieldClass];
			}
		}

		throw new RuntimeException('Field not supported');
	}
}
