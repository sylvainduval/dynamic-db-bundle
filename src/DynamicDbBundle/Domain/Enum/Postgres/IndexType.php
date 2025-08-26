<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Domain\Enum\Postgres;

enum IndexType: string
{
	case Btree = 'btree';
	case Hash = 'hash';
	case GIN = 'gin';
	case GiST = 'gist';
	case SPGiST = 'spgist';
	case BRIN = 'brin';
}
