<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

/**
 * @internal
 */
trait FieldGeneratorTrait
{
	private function escapeSingleQuote(string $string): string
	{
		return \str_replace('\'', '\'\'', $string);
	}
}
