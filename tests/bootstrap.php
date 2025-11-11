<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

$envFile = __DIR__ . '/../.env.test';
if (file_exists($envFile)) {
	$lines = parse_ini_file($envFile, false, INI_SCANNER_RAW);
	foreach ($lines as $key => $value) {
		if (!getenv($key)) {
			putenv("$key=$value");
		}
	}
}
