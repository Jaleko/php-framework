<?php
	class UserModel extends DatabaseModel
	{
		protected $table_name = 'users';
		//get user by username e password = login

		public function load($id)
		{
			parent::load($id);
			unset($this->password);
			return $this;
		}

		public function __save()
		{
			$db = $this->db;
			if ($this->id > 0)
			{
				if (strlen($this->password) > 0)
				{
					$sql = $db->prepare('update users set username = ?, password = aes_encrypt(?, ?), email = ? where id = ?');
					$sql->execute(array(
						$this->username,
						$this->password,
						CRYPT_KEY,
						$this->email,
						$this->id
					));
				}
				else
				{
					$sql = $db->prepare('update users set username = ?, email = ? where id = ?');
					$sql->execute(array(
						$this->username,
						$this->email,
						$this->id
					));
				}
			}
			else
			{
				$sql = $db->prepare('insert into users (username, password, email) values(?, aes_encrypt(?, ?), ?)');
				$sql->execute(array(
					$this->username,
					$this->password,
					CRYPT_KEY,
					$this->email
				));
				$this->id = $db->lastInsertId();
			}
			return $this;
		}

		public function __delete()
		{
			$this->defaultDelete();
		}
	}