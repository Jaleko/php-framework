<?php
class FormHelper
{
	public static function encodeAttribute($value, $print = true)
	{
		$value = htmlentities($value, ENT_COMPAT, 'utf-8');
		if ($print) echo $value;
		else return $value;
	}
}