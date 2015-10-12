<?php
class LanguageController extends CrudController
{
	public function getModel()
	{
		return new LanguageModel();
	}

	public function getForm($data)
	{
		$form = new Form('language', '', $data);
		$form
			->hidden('id')
			->text('ISO Code', 'iso_code', true)
			->text('Locale Code', 'locale_code', true)
			->text('Nome', 'label', true)
			//->text('ttttt', 'pippo', true)
			->datepicker('Formato data PHP', 'php_date_format', true)
			->text('Formato data calendario', 'datepicker_date_format', true)
			->submit('Salva', 'submit')
			;

		return $form;
	}

	public function getEntityLabels()
	{
		return array('Lingua', 'Lingue');
	}
}