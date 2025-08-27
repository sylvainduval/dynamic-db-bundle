<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Field;

/**
 * @internal
 */
interface FieldInterface
{
	public function getName(): string;
}
