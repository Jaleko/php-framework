<?php
	ini_set('display_errors', '1');
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT);

	include_once('config/defines.php');

	function __autoload($className)
	{
		$dirs = array(
			'core',
			'models',
			'controllers',
			'core/form'
		);

		foreach ($dirs as $dir)
		{
			$file = $dir.'/'.$className.'.php';
			if (file_exists($file)) include_once($file);
		}
	}

	$database = new Database();

	session_start();
	Dispatcher::run();
?>