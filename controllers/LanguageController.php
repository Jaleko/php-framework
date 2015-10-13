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
			//->disableAppendChildren()
			->hidden('id')
			->file('ISO Code', 'iso_code', true)
			->password('Locale Code', 'locale_code', true)
			->text('Nome', 'label', true)
			->textarea('Nome', 'label2', true, true)
			//->text('ttttt', 'pippo', true)
			->datepicker('Formato data PHP', 'php_date_format', true)
			->text('Formato data calendario', 'datepicker_date_format', true)
			->checkbox('Pippo', 'pippo', array(1 => 'pippo', 2 => 'pluto'), true)
			->submit('Salva', 'submit')
			;

		return $form;
	}

	public function getEntityLabels()
	{
		return array('Lingua', 'Lingue');
	}
}