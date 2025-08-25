<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain\Field;

/**
 * @internal
 */
interface FieldGeneratorFactoryInterface
{
	public function getGenerator(Field\FieldInterface $field): FieldDefinitionGeneratorInterface;
}
