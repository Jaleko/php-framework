<?php
class Block implements Printable
{
	protected $template;
	protected $children = array();
	protected $vars = array();

	public function __construct($template)
	{
		$this->template = $template;
	}

	public function setChild($name, Printable $block)
	{
		$this->children[$name] = $block;
		return $this;
	}

	public function getChild($name)
	{
		return $this->children[$name];
	}

	public function setVar($name, $value)
	{
		$this->vars[$name] = $value;
		return $this;
	}

	public function getVar($name)
	{
		if (isset($this->vars[$name])) return $this->vars[$name];
		else return NULL;
	}

	public function render()
	{
		include($this->template);
	}

	public function renderAll()
	{
		foreach ($this->children as $child)
		{
			$child->renderAll();
		}
	}
}