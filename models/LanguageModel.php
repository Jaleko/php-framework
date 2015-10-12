<?php
	class LanguageModel extends DatabaseModel
	{
		protected $table_name = 'languages';

		public function __save()
		{
			$this->defaultSave();
		}

		public function __delete()
		{
			$this->defaultDelete();
		}
	}