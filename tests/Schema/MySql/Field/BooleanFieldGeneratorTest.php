<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Boolean;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\BooleanFieldGenerator;

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
				'foo TINYINT(1) NOT NULL CHECK (foo IN (0, 1))',
			],
			'nullable without default' => [
				new Boolean('foo', true),
				'foo TINYINT(1) NULL DEFAULT NULL CHECK (foo IN (0, 1))',
			],
			'nullable with default' => [
				new Boolean('foo', true, true),
				'foo TINYINT(1) NULL DEFAULT 1 CHECK (foo IN (0, 1))',
			],
			'not nullable with default' => [
				new Boolean('foo', false, false),
				'foo TINYINT(1) NOT NULL DEFAULT 0 CHECK (foo IN (0, 1))',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Boolean $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
