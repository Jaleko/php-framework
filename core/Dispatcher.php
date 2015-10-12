<?php

class Dispatcher
{
	protected static $controller;

	public static function run()
	{
		$controllerClassName = self::get('controller').'Controller';
		$action = self::get('action').'Action';

		self::$controller = new $controllerClassName();
		call_user_func_array(array(self::$controller, $action), array());
	}

	public static function get($key)
	{
		$name = $_GET[$key];
		$name = str_replace('-', ' ', $name);
		$name = ucfirst($name);
		$name = str_replace(' ', '', $name);
		return $name;
	}

	public static function getController()
	{
		return self::$controller;
	}
}
