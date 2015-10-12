<?php
abstract class CrudController extends Controller
{
	abstract function getModel();
	/*
	{
		return new UserModel();
	}
	*/

	abstract function getForm($data);
	/*
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
	*/

	abstract function getEntityLabels();
	/*
	{
		return array('Utente', 'Utenti');
	}
	*/

	public function deleteAction()
	{
		$params = $this->getParams();
		$success = $this->getModel()->load($params['id'])->delete();
		$message = 'Cancellazione NON effettuata';
		if ($success) $message = 'Cancellazione effettuata';
		echo json_encode(array('success' => $success, 'message' => $message));
	}

	public function updateAction()
	{
		$model = $this->getModel();
		$post = $this->getPost();
		$params = $this->getParams();
		if (count($post) > 0)
		{
			try
			{
				//da eseguire in caso di errore di validazione
				//throw new ValidationException('iso_code', 'Errore 1');

				$success = $model->setFromArray($post)->save();

				echo json_encode(array(
					'success' => $success, 
					'message' => $success ? 'I dati sono stati salvati' : 'I dati NON sono stati salvati',
					'id' => $model->id
				));
			}
			catch (ValidationException $e)
			{
				echo json_encode(array(
					'success' => false, 
					'message' => $e->getMessage(),
					'wrong_label' => $e->getPropertyName()
				));
			}
			exit();
		}
		else if (isset($params['id']) and $params['id'] > 0)
		{
			$title = 'Modifica';
			$data = $model->load($params['id'])->toArray();	
		}
		else
		{
			$title = 'Nuovo';
			$data = array();
		}

		$labels = $this->getEntityLabels();
		$layout = $this->layout;
		$layout->setVar('title', $title.' '.$labels[0]);
		$layout->setChild('body', $this->getForm($data));
		$layout->render();
	}

	public function indexAction()
	{
		$collection = $this->getModel()->getCollection();

		$params = $this->getParams();
		$list = new Block('templates/'.$params['controller'].'/index.php');
		$list->setVar('collection', $collection);


		$labels = $this->getEntityLabels();
		$layout = $this->layout;
		$layout->setVar('title', $labels[1]);
		$layout->setChild('body', $list);
		$layout->render();
	}
}