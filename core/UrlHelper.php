<?php
class UrlHelper
{
	public static function get($params, $print = true)
	{
		$get = Dispatcher::getController()->getParams();
		foreach ($params as $key => $value)
		{
			if (is_null($value)) unset($get[$key]);
			else $get[$key] = $value;
		}

		$query = '';
		if (count($get) > 0) $query = '?'.http_build_query($get);

		$url = 'index.php'.$query;
		if ($print) echo $url;
		else return $url;
	}

	public static function redirect($params)
	{
		$url = self::get($params, false);
		header('Location: '.$url);
		exit();
	}
}