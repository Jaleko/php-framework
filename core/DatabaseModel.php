<?php
	abstract class DatabaseModel extends Model
	{
		protected $db;
		protected $table_name;

		public function __construct()
		{
			parent::__construct();
			$this->db = Database::getConnection();
		}

		/*
		* @param string $table_name
		* @param array $primary_keys: array indicizzato con il nome delle colonne e valore delle primary keys
		* @param array $values: array indicizzato con il nome delle colonne e valore delle colonne rimanenti
		*/
		final protected static function saveStatic($table_name, $primary_keys, $values)
		{
			$db = Database::getConnection();
			$columns = array();
			$column_values = array();
			$data = array();
			$update_where = array();
			foreach ($primary_keys as $key => $value) {
				$update_where[] = '`'.$key.'` = :'.$key;
				$data[':'.$key] = $value;
			}

			if (isset($primary_keys['id']))
			{
				$is_update = !empty($primary_keys['id']);
				if (!$is_update) unset($data[':id']);
			}
			else
			{
				$query = 'select * from `'.$table_name.'` where '.implode(' and ', $update_where);
				$sql = $db->prepare($query);
				$sql->execute($data);
				$is_update = $sql->rowCount() > 0;
			}

			if ($is_update)
			{
				foreach ($values as $key => $value) {
					$columns[] = '`'.$key.'` = :'.$key;
					$data[':'.$key] = $value;
				}
				$query = 'update `'.$table_name.'` set '.implode(', ', $columns).' where '.implode(' and ', $update_where);
			} else {
				foreach ($values as $key => $value) {
					$columns[] = '`'.$key.'`';
					$column_values[] = ':'.$key;
					$data[':'.$key] = $value;
				}
				$query = 'insert into `'.$table_name.'` ('.implode(', ', $columns).') values ('.implode(', ', $column_values).')';
			}

			$sql = $db->prepare($query);
			$sql->execute($data);
		}

		public function save()
		{
			$success = true;
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			$db->beginTransaction();
			try
			{
				$this->__save();
				$db->commit();
			}
			catch (PDOException $e) //PDOException perchè altrimenti catturerebbe anche ValidationException
			{
				$db->rollBack();
				$success = false;
			}


			$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
			return $success;
		}

		public function delete()
		{
			$success = true;
			$db = $this->db;
			$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 0);
			$db->beginTransaction();
			try
			{
				$this->__delete();
				$db->commit();
			}
			catch (PDOException $e) //PDOException perchè altrimenti catturerebbe anche ValidationException
			{
				$db->rollBack();
				$success = false;
			}
			$db->setAttribute(PDO::ATTR_AUTOCOMMIT, 1);
			return $success;
		}

		protected final function defaultSave()
		{
			if (empty($this->table_name)) throw new PDOException('Table name is empty');
			$values = $this->toArray();
			$id = $values['id'];
			unset($values['id']);
			self::saveStatic($this->table_name, array('id' => $id), $values);
			if (empty($id))
				$this->id = $this->db->lastInsertId();
		}

		protected final function defaultDelete()
		{
			if (empty($this->table_name)) throw new PDOException('Table name is empty');
			$sql = $this->db->prepare('delete from `'.$this->table_name.'` where id = ?');
			$sql->execute(array(
				$this->id
			));
		}

		public function load($id)
		{
			if (empty($this->table_name)) throw new PDOException('Table name is empty');
			$sql = $this->db->prepare('select * from `'.$this->table_name.'` where id = ?');
			$sql->execute(array($id));
			$this->setFromArray($sql->fetch(PDO::FETCH_ASSOC));
			return $this;
		}

		/*
		* Validazione e salvataggio dei dati
		* In caso di errore di validazione deve lanciare l'eccezione throw new ValidationException('attributo for del tag label', "messaggio di errore");
		*/
		abstract protected function __save();

		abstract public function __delete();

		protected function __getCollection($query, $params = NULL)
		{
			return new DatabaseCollection($this, $query, $params);
		}

		public function getCollection()
		{
			if (empty($this->table_name)) throw new PDOException('Table name is empty');
			return $this->__getCollection('select id from `'.$this->table_name.'`');
		}
	}