<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres;

use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;
use SylvainDuval\DynamicDbBundle\Schema\FieldDefinitionGeneratorInterface;

interface FieldGeneratorInterface extends FieldDefinitionGeneratorInterface
{
	public function generateFieldDefaultValue(FieldInterface $field): string;

	public function generateFieldType(FieldInterface $field): string;
}
