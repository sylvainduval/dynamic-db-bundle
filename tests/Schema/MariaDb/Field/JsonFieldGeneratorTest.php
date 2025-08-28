<?php

declare(strict_types=1);

namespace Tests\SylvainDuval\DynamicDbBundle\Schema\MariaDb\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Json;
use SylvainDuval\DynamicDbBundle\Schema\MariaDb\Field\JsonFieldGenerator;

final class JsonFieldGeneratorTest extends TestCase
{
	private readonly JsonFieldGenerator $generator;

	public function setUp(): void
	{
		$this->generator = new JsonFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default' => [
				new Json('foo'),
				'foo LONGTEXT NOT NULL CHECK (JSON_VALID(foo))',
			],
			'nullable without default' => [
				new Json('foo', true),
				'foo LONGTEXT NULL DEFAULT NULL CHECK (JSON_VALID(foo))',
			],
			'nullable with default' => [
				new Json('foo', true, ['a' => 'b\'c', 'c' => ['d']]),
				'foo LONGTEXT NULL DEFAULT \'{"a":"b\\\'c","c":["d"]}\' CHECK (JSON_VALID(foo))',
			],
			'not nullable with default' => [
				new Json('foo', false, []),
				'foo LONGTEXT NOT NULL DEFAULT \'[]\' CHECK (JSON_VALID(foo))',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Json $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
