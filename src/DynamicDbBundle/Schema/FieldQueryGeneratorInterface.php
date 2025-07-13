<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Schema;

use SylvainDuval\DynamicDbBundle\Domain;

/**
 * @internal
 */
interface FieldQueryGeneratorInterface
{
	public function generateCreateField(Domain\Table $table, Domain\Field\FieldInterface $field): string;

	public function generateRenameField(Domain\Table $table, string $fromFieldName, string $toFieldName): string;

	public function generateChangeField(Domain\Table $table, Domain\Field\FieldInterface $fromField, Domain\Field\FieldInterface $toField): string;

	public function generateFieldDefinition(Domain\Field\FieldInterface $field): string;

	public function generateDeleteField(Domain\Table $table, string $fieldName): string;
}
