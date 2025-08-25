<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;

interface FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string;

	public function generateFieldDefaultValue(FieldInterface $field): string;

	public function generateFieldType(FieldInterface $field): string;
}
