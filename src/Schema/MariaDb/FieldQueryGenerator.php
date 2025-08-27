<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema\MariaDb;

use SylvainDuval\DynamicDbBundle\Schema\FieldGeneratorFactoryInterface;
use SylvainDuval\DynamicDbBundle\Schema\MySql\FieldQueryGenerator as MySqlFieldQueryGenerator;

/**
 * @internal
 */
final class FieldQueryGenerator extends MySqlFieldQueryGenerator
{
	protected FieldGeneratorFactoryInterface $fieldGeneratorFactory;

	public function __construct()
	{
		$this->fieldGeneratorFactory = new FieldGeneratorFactory();
	}
}
