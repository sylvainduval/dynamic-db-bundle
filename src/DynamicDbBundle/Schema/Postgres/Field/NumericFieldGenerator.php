<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\Numeric;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\FieldGeneratorInterface;

/**
 * @internal
 */
final class NumericFieldGenerator implements FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof Numeric) {
			throw new InvalidArgumentException('Expected Numeric, found ' . $field::class);
		}

		$definition = $field->name . ' ' . $this->generateFieldType($field);

		if (!$field->nullable) {
			$definition .= ' NOT NULL';
		}

		$definition .= $this->generateFieldDefaultValue($field);

		return $definition;
	}

	public function generateFieldDefaultValue(FieldInterface $field): string
	{
		if (!$field instanceof Numeric) {
			throw new InvalidArgumentException('Expected Numeric, found ' . $field::class);
		}

		if ($field->default === null && $field->nullable) {
			return ' DEFAULT NULL';
		}
		if ($field->default !== null) {
			return ' DEFAULT ' . $field->default;
		}

		return '';
	}

	public function generateFieldType(FieldInterface $field): string
	{
		if (!$field instanceof Numeric) {
			throw new InvalidArgumentException('Expected Numeric, found ' . $field::class);
		}

		if ($field->autoIncrement) {
			return $this->getAutoIncrementType($field->min, $field->max);
		}

		if ($field->decimals > 0) {
			return \sprintf('NUMERIC(%d, %d)', $this->getPrecision($field), $field->decimals);
		}

		$min = $field->min;
		$max = $field->max;

		return match (true) {
			$min >= -32768 && $max <= 32767 => 'SMALLINT',
			$min >= -2147483648 && $max <= 2147483647 => 'INTEGER',
			default => 'BIGINT',
		};
	}

	private function getAutoIncrementType(int $min, int $max): string
	{
		return match (true) {
			$min >= -32768 && $max <= 32767 => 'SMALLSERIAL',
			$min >= -2147483648 && $max <= 2147483647 => 'SERIAL',
			default => 'BIGSERIAL',
		};
	}

	private function getPrecision(Numeric $field): int
	{
		return \strlen((string) $field->max) + $field->decimals;
	}
}
