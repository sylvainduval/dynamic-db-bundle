<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Boolean;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\BooleanFieldGenerator;

final class BooleanFieldGeneratorTest extends TestCase
{
	private readonly BooleanFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new BooleanFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Boolean('foo'),
				'foo BOOLEAN NOT NULL',
			],
			'nullable without default' => [
				new Boolean('foo', true),
				'foo BOOLEAN NULL DEFAULT NULL',
			],
			'nullable with default' => [
				new Boolean('foo', true, true),
				'foo BOOLEAN NULL DEFAULT TRUE',
			],
			'not nullable with default' => [
				new Boolean('foo', false, false),
				'foo BOOLEAN NOT NULL DEFAULT FALSE',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Boolean $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
