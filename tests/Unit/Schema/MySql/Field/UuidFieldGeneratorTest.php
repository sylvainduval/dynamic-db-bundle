<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Uuid;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\UuidFieldGenerator;

final class UuidFieldGeneratorTest extends TestCase
{
	private readonly UuidFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new UuidFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Uuid('foo'),
				'foo CHAR(36) NOT NULL',
			],
			'nullable without default' => [
				new Uuid('foo', true),
				'foo CHAR(36) NULL DEFAULT NULL',
			],
			'nullable with default' => [
				new Uuid('foo', true, '49c966b5-7d30-4f1d-a3d7-cf117c767718'),
				'foo CHAR(36) NULL DEFAULT \'49c966b5-7d30-4f1d-a3d7-cf117c767718\'',
			],
			'not nullable with default' => [
				new Uuid('foo', false, '49c966b5-7d30-4f1d-a3d7-cf117c767718'),
				'foo CHAR(36) NOT NULL DEFAULT \'49c966b5-7d30-4f1d-a3d7-cf117c767718\'',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Uuid $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
