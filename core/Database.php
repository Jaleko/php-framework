<?php
class Database
{
	protected static $connection;

	public static function getConnection()
	{
		if (is_null(self::$connection))
		{
			self::$connection = new PDO('mysql:host='.DATABASE_HOST.';port='.DATABASE_PORT.';dbname='.DATABASE_NAME, DATABASE_USER, DATABASE_PASSWORD);
			self::$connection->exec('set names utf8');
			self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return self::$connection;
	}
}