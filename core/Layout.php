<?php
class Layout extends Block
{
	protected $js = array();
	protected $css = array();
	protected $ready_script;

	public function addJs($src)
	{
		$this->js[$src] = $src;
		return $this;
	}

	public function addReadyJs($script)
	{
		$this->ready_script .= $script;
		return $this;
	}

	public function getReadyJs()
	{
		return '$(document).ready(function () {'.$this->ready_script.'});';
	}

	public function addCss($href, $media = 'screen')
	{
		$this->css[$href] = array(
			'href' => $href,
			'media' => $media
		);
		return $this;
	}

	public function getJs()
	{
		return $this->js;
	}

	public function getCss()
	{
		return $this->css;
	}

	public function render()
	{
		include($this->template);
	}
}