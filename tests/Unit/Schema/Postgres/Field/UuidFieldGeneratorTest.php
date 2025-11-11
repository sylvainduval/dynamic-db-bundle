<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Uuid;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\UuidFieldGenerator;

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
				'foo UUID NOT NULL',
			],
			'nullable without default' => [
				new Uuid('foo', true),
				'foo UUID NULL DEFAULT NULL',
			],
			'nullable with default' => [
				new Uuid('foo', true, '49c966b5-7d30-4f1d-a3d7-cf117c767718'),
				'foo UUID NULL DEFAULT \'49c966b5-7d30-4f1d-a3d7-cf117c767718\'',
			],
			'not nullable with default' => [
				new Uuid('foo', false, '49c966b5-7d30-4f1d-a3d7-cf117c767718'),
				'foo UUID NOT NULL DEFAULT \'49c966b5-7d30-4f1d-a3d7-cf117c767718\'',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Uuid $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
