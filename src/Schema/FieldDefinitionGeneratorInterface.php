<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;

/**
 * @internal
 */
interface FieldDefinitionGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string;
}
