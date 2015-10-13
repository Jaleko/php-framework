<?php
class Controller
{
	protected $vars = array();
	protected $layout;
	protected $post;
	protected $params;

	public function __construct()
	{
		$this->layout = new Layout('templates/default.php');
		$this->layout->addCss('/templates/css/global.css');
		$this->layout->addCss('https://code.jquery.com/ui/1.11.4/themes/start/jquery-ui.css');
		$this->layout->addJs('/templates/js/jquery.js');
		$this->layout->addJs('https://code.jquery.com/ui/1.11.3/jquery-ui.min.js');
		$this->layout->addJs('/templates/js/global.js');


		$this->layout->addCss('/templates/third-parts/sweetalert/sweetalert.css');
		$this->layout->addJs('/templates/third-parts/sweetalert/sweetalert.min.js');
		$this->layout->addJs('/templates/js/messenger.js');

		$this->layout->addCss('/templates/third-parts/font-awesome-4.4.0/css/font-awesome.min.css');
		

		$this->post = $_POST;
		unset($_POST);
		$this->params = $_GET;
		unset($_GET);
	}

	public function setVar($name, $value)
	{
		$this->vars[$name] = $value;
		return $this;
	}

	public function getVar($name)
	{
		return $this->vars[$name];
	}

	public function getPost()
	{
		return $this->post;
	}

	public function getParams()
	{
		return $this->params;
	}

	public function getLayout()
	{
		return $this->layout;
	}

	public function getLocale()
	{
		setlocale(LC_ALL, 'it_IT');
		return array(
			'jquery_ui_datepicker_user_date_format' => 'dd-mm-yy', //formato data di jquery ui datepicker per gli utenti
			'php_user_date_format' => 'd-m-Y' //serve nel form per datepicker. deve corrispondere allo stesso formato di jquery_ui_datepicker_user_date_format
		);
	}
}