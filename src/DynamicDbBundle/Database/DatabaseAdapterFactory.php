<?php

declare(strict_types=1);

namespace SylvainDuval\DynamicDbBundle\Database;

use PDO;
use SylvainDuval\DynamicDbBundle\Connection\MysqliConnection;
use SylvainDuval\DynamicDbBundle\Connection\PdoConnection;
use SylvainDuval\DynamicDbBundle\Database\Mysqli\MysqliAdapter;
use SylvainDuval\DynamicDbBundle\Database\Pdo\PdoAdapter;
use SylvainDuval\DynamicDbBundle\Domain\Configuration;
use SylvainDuval\DynamicDbBundle\Domain\Enum\Server;
use SylvainDuval\DynamicDbBundle\Exception\ConnectionException;

/**
 * @internal
 */
final class DatabaseAdapterFactory
{
	public static function create(Configuration $configuration): DatabaseAdapterInterface
	{
		if (PdoConnection::support()) {
			$pdo = new PdoConnection($configuration)->connect();
			$driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);

			$server = null;
			if ($driver === 'pgsql') {
				$server = $driver;
			} elseif ($driver === 'mysql') {
				$statement = $pdo->query('SELECT VERSION()');
				if ($statement !== false) {
					$version = $statement->fetchColumn();
					if (\is_string($version)) {
						$server = $version;
					}
				}
			}

			$server = self::getServerFromString($server);
			if ($server === null) {
				throw new ConnectionException('Unknown pdo server type');
			}


			return new PdoAdapter($pdo, $server);
		} elseif (MysqliConnection::support()) {
			$mysqli = new MysqliConnection($configuration)->connect();
			$server = self::getServerFromString($mysqli->server_info);
			if ($server === null) {
				throw new ConnectionException('Unknown mysqli server type');
			}

			return new MysqliAdapter($mysqli, $server);
		}

		throw new ConnectionException('Aucune méthode de connexion supportée (PDO, MySQLi).');
	}

	private static function getServerFromString(?string $server): ?Server
	{
		if ($server === null) {
			return null;
		}

		if ($server === 'pgsql') {
			return Server::PostgreSQL;
		}

		if (\stripos($server, 'mariadb') !== false) {
			return Server::MariaDB;
		}

		if (\stripos($server, 'mysql') !== false || \preg_match('/^\d+\.\d+\.\d+/', $server)) {
			return Server::MySQL;
		}

		return null;
	}
}
