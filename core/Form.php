<?php
	class Form extends DOMDocument implements Printable
	{
		protected $form;
		protected $data;
		protected $elements;
		protected $append_children = true;

		/*
		* @param array $data: array indicizzato con i name dell'elemento del form e valore/valori da assegnare
		*/
		public function __construct($name, $action, $data = array(), $default_submit = true)
		{
			$layout = Dispatcher::getController()->getLayout();
			$layout->addCss('/templates/css/form.css');
			$layout->addJs('/templates/js/form.js');

			parent::__construct('1.0', 'UTF-8');
			$form = $this->createElement('form');
			$this->appendChild($form);

			$this->setAttribute($form, 'action', $action);
			$this->setAttribute($form, 'id', $name);
			$this->setAttribute($form, 'class', $name);
			$this->setAttribute($form, 'method', 'POST');

			if ($default_submit) $this->setAttribute($form, 'data-default-submit', 'yes');

			$this->form = $form;

			$this->data = $data;
		}

		public function disableAppendChildren()
		{
			$this->append_children = false;
		}

		protected function getContainer()
		{
			$e = $this->createElement('div');
			$attr = $this->createAttribute('class');
			$attr->value = 'input-item';
			$e->appendChild($attr);

			return $e;
		}

		protected function getLabel($label, $for, $required)
		{
			$e = $this->createElement('label', $label);
			$attr = $this->createAttribute('for');
			$attr->value = $for;
			$e->appendChild($attr);

			if ($required)
			{
				$attr = $this->createAttribute('class');
				$attr->value = 'required';
				$e->appendChild($attr);
			}

			return $e;
		}

		protected function getFieldset($label, $for, $required)
		{
			$e = $this->createElement('label', $label);
			$attr = $this->createAttribute('for');
			$attr->value = $for;
			$e->appendChild($attr);

			if ($required)
			{
				$attr = $this->createAttribute('class');
				$attr->value = 'required';
				$e->appendChild($attr);
			}

			$legend = $this->createElement('legend');
			$legend->appendChild($e);
			$fieldset = $this->createElement('fieldset');
			$fieldset->appendChild($legend);

			$attr = $this->createAttribute('class');
			$attr->value = 'input-item';
			$fieldset->appendChild($attr);

			return $fieldset;
		}

		protected function setAttribute($node, $name, $value)
		{
			$attr = $this->createAttribute($name);
			$attr->value = $value;
			$node->appendChild($attr);
		}

		protected function input($type, $name)
		{
			$input = $this->createElement('input');
			$this->setAttribute($input, 'type', $type);
			$this->setAttribute($input, 'name', $name);

			if (isset($this->data[$name]))
			{
				$this->setAttribute($input, 'value', $this->data[$name]);
			}

			return $input;
		}

		public function open()
		{
			//
		}

		public function close()
		{
			//
		}

		public function renderItem($name)
		{
			if (isset($this->elements[$name])) echo $this->elements[$name];
		}

		public function hidden($name)
		{
			$input = $this->input('hidden', $name);
			$this->setAttribute($input, 'id', $name);

			$this->elements[$name] = $input;
			if ($this->append_children)	$this->form->appendChild($input);

			return $this;
		}

		public function datepicker($label, $name, $required = false)
		{
			$input = $this->createElement('input');
			$this->setAttribute($input, 'type', 'text');
			$this->setAttribute($input, 'id', $name.'_alternate');

			$hidden = $this->input('hidden', $name);
			$this->setAttribute($input, 'id', $name);

			$label = $this->getLabel($label, $name, $required);

			$container = $this->getContainer();
			$container->appendChild($label);
			$container->appendChild($input);
			$container->appendChild($hidden);

			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);
			
			$locale = Dispatcher::getController()->getLocale();

			Dispatcher::getController()->getLayout()->addReadyJs('
				$("#'.$name.'_alternate").datepicker({
					altField: "#'.$name.'",
					altFormat: "yy-mm-dd",
					dateFormat: "'.$locale['user_date_format'].'"
				});
			');

			if (isset($this->data[$name]))
			{
				Dispatcher::getController()->getLayout()->addReadyJs('
					$("#'.$name.'_alternate").datepicker("setDate", "'.$this->data[$name].'");
				');
			}
			return $this;
		}

		public function text($label, $name, $required = false)
		{
			$input = $this->input('text', $name);
			$this->setAttribute($input, 'id', $name);

			$label = $this->getLabel($label, $name, $required);

			$container = $this->getContainer();
			$container->appendChild($label);
			$container->appendChild($input);

			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);

			return $this;
		}

		public function submit($label, $name)
		{
			$input = $this->input('submit', $name);
			$this->setAttribute($input, 'value', $label);
			$this->setAttribute($input, 'id', $name);

			$container = $this->getContainer();
			$container->appendChild($input);
			
			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);

			return $this;
		}

		public function password($label, $name, $required = false)
		{
			$input = $this->input('password', $name);
			$this->setAttribute($input, 'id', $name);

			$label = $this->getLabel($label, $name, $required);

			$container = $this->getContainer();
			$container->appendChild($label);
			$container->appendChild($input);
			
			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);

			return $this;
		}

		public function file($label, $name, $accept, $required = false)
		{
			$this->setAttribute($this->form, 'enctype', 'multipart/form-data');

			$input = $this->input('file', $name);
			$this->setAttribute($input, 'id', $name);
			$this->setAttribute($input, 'accept', $accept);

			$label = $this->getLabel($label, $name, $required);

			$container = $this->getContainer();
			$container->appendChild($label);
			$container->appendChild($input);

			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);

			return $this;
		}

		public function radio($label, $name, $options, $required = false)
		{
			if (isset($this->data[$name])) $value = $this->data[$name];

			$fieldset = $this->getFieldset($label, $name, $required);

			foreach ($options as $val => $option_label)
			{
				$id = $name.'_'.$val;
				$label = $this->getLabel($option_label, $id, false);

				$input = $this->input('radio', $name);
				$this->setAttribute($input, 'value', $val);
				$this->setAttribute($input, 'id', $id);

				if ($value == $val) $this->setAttribute($input, 'checked', 'checked');

				$container = $this->getContainer();
				$container->appendChild($input);
				$container->appendChild($label);
				$fieldset->appendChild($container);
			}

			$this->elements[$name] = $fieldset;
			if ($this->append_children)	$this->form->appendChild($fieldset);

			return $this;
		}

		public function select($label, $name, $options, $required = false)
		{
			if (isset($this->data[$name])) $value = $this->data[$name];
			$label = $this->getLabel($label, $name, $required);

			$select = $this->createElement('select');
			$this->setAttribute($select, 'name', $name);
			$this->setAttribute($select, 'id', $name);

			foreach ($options as $val => $option_label)
			{
				$option = $this->createElement('option', $option_label);

				$this->setAttribute($option, 'value', $val);

				if ($value == $val) $this->setAttribute($option, 'selected', 'selected');

				$select->appendChild($option);
			}

			$container = $this->getContainer();
			$container->appendChild($label);
			$container->appendChild($select);

			$this->elements[$name] = $container;
			if ($this->append_children)	$this->form->appendChild($container);

			return $this;
		}

		public function checkbox($label, $name, $options, $required = false)
		{
			if (isset($this->data[$name])) $values = $this->data[$name];
			$fieldset = $this->getFieldset($label, $name, $required);

			foreach ($options as $val => $option_label)
			{
				$id = $name.'_'.$val;
				$label = $this->getLabel($option_label, $id, false);

				$input = $this->input('checkbox', $name);
				$this->setAttribute($input, 'value', $val);
				$this->setAttribute($input, 'id', $id);

				if (is_array($values) and in_array($val, $values)) $this->setAttribute($input, 'checked', 'checked');

				$container = $this->getContainer();
				$container->appendChild($input);
				$container->appendChild($label);
				$fieldset->appendChild($container);
			}

			$this->elements[$name] = $fieldset;
			if ($this->append_children)	$this->form->appendChild($fieldset);

			return $this;
		}

		public function textarea($label, $name, $required = false, $editor = false)
		{
			if (isset($this->data[$name])) $value = $this->data[$name];
			$textarea = $this->createElement('textarea', $value);
			$this->setAttribute($textarea, 'name', $name);
			$this->setAttribute($textarea, 'id', $name);

			if ($editor) $this->setAttribute($textarea, 'class', 'editor');
			
			$container = $this->getContainer();
			$container->appendChild($textarea);

			$fieldset = $this->getFieldset($label, $name, $required);
			$fieldset->appendChild($container);

			$this->elements[$name] = $fieldset;
			if ($this->append_children)	$this->form->appendChild($fieldset);

			return $this;
		}

		public function __toString()
		{
			return $this->saveHTML();
		}


		public function render()
		{
			echo $this->__toString();
		}

		public function renderAll()
		{
			$this->render();
		}
	}
?>