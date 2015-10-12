<?php
	class ValidationException extends Exception
	{
		protected $property_name;

		public function __construct($property_name, $message)
		{
			parent::__construct($message);
			$this->property_name = $property_name;
		}

		public function getPropertyName()
		{
			return $this->property_name;
		}
	}