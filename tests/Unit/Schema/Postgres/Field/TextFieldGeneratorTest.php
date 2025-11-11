<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Tests\Unit\Schema\Postgres\Field;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SylvainDuval\DynamicDbBundle\Domain\Field\Text;
use SylvainDuval\DynamicDbBundle\Schema\Postgres\Field\TextFieldGenerator;

final class TextFieldGeneratorTest extends TestCase
{
	private readonly TextFieldGenerator $generator;

	protected function setUp(): void
	{
		$this->generator = new TextFieldGenerator();
	}

	public static function casesProvider(): array
	{
		return [
			'default VARCHAR not null' => [
				new Text('foo', 100),
				'foo VARCHAR(100) NOT NULL',
			],
			'nullable VARCHAR with default' => [
				new Text('foo', 150, false, true, 'bar'),
				"foo VARCHAR(150) DEFAULT 'bar'",
			],
			'nullable VARCHAR with default with quote' => [
				new Text('foo', 150, false, true, 'l\'apostrophe'),
				"foo VARCHAR(150) DEFAULT 'l''apostrophe'",
			],
			'nullable with null default' => [
				new Text('foo', 200, false, true, null),
				'foo VARCHAR(200) DEFAULT NULL',
			],
			'fixed length CHAR' => [
				new Text('foo', 20, true),
				'foo CHAR(20) NOT NULL',
			],
			'fixed length nullable CHAR with default' => [
				new Text('foo', 10, true, true, 'X'),
				"foo CHAR(10) DEFAULT 'X'",
			],
			'TEXT over 10MB' => [
				new Text('foo', 11 * 1024 * 1024),
				'foo TEXT NOT NULL',
			],
		];
	}

	#[DataProvider('casesProvider')]
	public function testGenerateFieldDefinition(Text $field, string $expected): void
	{
		$this->assertSame($expected, $this->generator->generateFieldDefinition($field));
	}
}
