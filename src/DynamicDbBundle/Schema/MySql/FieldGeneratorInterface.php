<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MySql;

use SylvainDuval\DynamicDbBundle\Domain\Field\FieldInterface;

interface FieldGeneratorInterface
{
	public function generateFieldDefinition(FieldInterface $field): string;
}
