<?php
	class Model
	{
		private $properties;

		public function __construct()
		{
			$this->properties = array();
		}

		public function __get($index)
		{
			return htmlentities($this->properties[$index], ENT_COMPAT, 'UTF-8');
		}

		public function raw($index)
		{
			return $this->properties[$index];
		}

		public function __set($index, $value)
		{
			$this->properties[$index] = $value;
			return $this;
		}

		public function __isset($index)
		{
			return isset($this->properties[$index]);
		}

		public function __unset($index)
		{
			unset($this->properties[$index]);
			return $this;
		}

		public function setFromArray($array)
		{
			foreach ($array as $index => $value)
			{
				$this->properties[$index] = $value;
			}
			return $this;
		}

		public function toArray()
		{
			return $this->properties;
		}
	}