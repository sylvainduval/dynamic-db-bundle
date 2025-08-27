<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Enum\MySql;

enum IndexType: string
{
	case Index = 'INDEX';
	case Unique = 'UNIQUE INDEX';
	case Fulltext = 'FULLTEXT INDEX';
	case Spacial = 'SPATIAL INDEX';
}
