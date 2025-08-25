<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use InvalidArgumentException;
use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Domain\Field\NumericField;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

/**
 * @internal
 */
final class NumericFieldGenerator implements FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string
	{
		if (!$field instanceof NumericField) {
			throw new InvalidArgumentException('Expected ' . $field::class);
		}

		$definition = $field->name . ' ' . $this->generateFieldType($field);

		$definition .= $field->nullable ? ' NULL' : ' NOT NULL';

		if ($field->default === null && $field->nullable) {
			$definition .= ' DEFAULT NULL';
		} elseif ($field->default !== null) {
			$definition .= ' DEFAULT ' . $field->default;
		}

		if ($field->autoIncrement) {
			//TODO: may be UNIQUE and not primary key
			$definition .= ' AUTO_INCREMENT PRIMARY KEY';
		}

		return $definition;
	}

	private function generateFieldType(NumericField $field): string
	{
		$min = $field->min;
		$max = $field->max;

		if ($min > $max) {
			throw new InvalidArgumentException('min value must be less than max');
		}

		if ($field->decimals > 0) {
			return \sprintf('DECIMAL(%d, %d)', $this->getPrecision($field), $field->decimals);
		}

		if ($min >= 0) {
			return match (true) {
				$max <= 255 => 'TINYINT UNSIGNED',
				$max <= 65535 => 'SMALLINT UNSIGNED',
				$max <= 16777215 => 'MEDIUMINT UNSIGNED',
				$max <= 4294967295 => 'INT UNSIGNED',
				default => 'BIGINT UNSIGNED',
			};
		}

		return match (true) {
			$min >= -128 && $max <= 127 => 'TINYINT',
			$min >= -32768 && $max <= 32767 => 'SMALLINT',
			$min >= -8388608 && $max <= 8388607 => 'MEDIUMINT',
			$min >= -2147483648 && $max <= 2147483647 => 'INT',
			default => 'BIGINT',
		};
	}

	private function getPrecision(NumericField $field): int
	{
		return \strlen((string) $field->max) + $field->decimals;
	}
}
