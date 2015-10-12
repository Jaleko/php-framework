<?php
class UserController extends CrudController
{
	public function getModel()
	{
		return new UserModel();
	}

	public function getForm($data)
	{
		$form = new Form('user', '', $data);
		$form
			->hidden('id')
			->text('Username', 'username', true)
			->password('Password', 'password', true)
			->text('Email', 'email', true)
			->submit('Salva', 'submit')
			;

		return $form;
	}

	public function getEntityLabels()
	{
		return array('Utente', 'Utenti');
	}
}