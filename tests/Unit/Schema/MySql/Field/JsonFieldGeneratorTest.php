<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\MySql\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Json;
use SylvainDuval\DynamicDbBundle\Schema\MySql\Field\JsonFieldGenerator;

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
				'foo JSON NOT NULL',
			],
			'nullable' => [
				new Json('foo', true),
				'foo JSON NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Json $field, string $expected): void
	{
		$this->assertEquals($expected, $this->generator->generateFieldDefinition($field));
	}
}
