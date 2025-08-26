<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use RuntimeException;
use SylvainDuval\DynamicDbBundle\Domain\Field;
use SylvainDuval\DynamicDbBundle\Schema\Postgres;

final class FieldGeneratorFactory
{
	/** @var array<class-string<Field\FieldInterface>, FieldGeneratorInterface> */
	private array $generators = [];

	/** @var array<string, string> */
	private array $map = [
		Field\Boolean::class => Postgres\Field\BooleanFieldGenerator::class,
		Field\Geometry::class => Postgres\Field\GeometryFieldGenerator::class,
		Field\Json::class => Postgres\Field\JsonFieldGenerator::class,
		Field\Numeric::class => Postgres\Field\NumericFieldGenerator::class,
		Field\Point::class => Postgres\Field\PointFieldGenerator::class,
		Field\Text::class => Postgres\Field\TextFieldGenerator::class,
		Field\Uuid::class => Postgres\Field\UuidFieldGenerator::class,
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
