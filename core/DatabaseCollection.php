<?php
	class DatabaseCollection implements Iterator
	{
		protected $items;
		protected $index;
		protected $class_name;

		/*
		* @param string $query: una query di select per selezionare i record. per ottimizzare deve selezionare solo la colonna id
		* @param array $params
		*/
		public function __construct($obj, $query, $params)
		{
			$this->class_name = get_class($obj);
			if (is_null($params)) $params = array();

			$db = Database::getConnection();
			$sql = $db->prepare($query);
			$sql->execute($params);
			$this->items = $sql->fetchAll();
			$this->index = 0;
		}

		public function current()
		{
			$row = $this->items[$this->index];
			$model = new $this->class_name();
			return $model->load($row['id']);
		}
		
		public function key()
		{
			return $this->index;
		}

		public function next()
		{
			$this->index++;
		}

		public function rewind()
		{
			$this->index = 0;
		}

		public function valid()
		{
			return isset($this->items[$this->index]);
		}
	}
?>