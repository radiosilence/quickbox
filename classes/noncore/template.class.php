<?php
/**
 * Contains template class.
 * 
 * @author James Cleveland
 * @package quickbox
 * @subpackage template
 * @category classes
 *
 */
class template
{
	public $tData;
	private $tPathHostpage;
	private $tPathPage;
	private $tPathComponent;

	function template ($templateData)
	{
		$this->tData = $templateData;
	}

	function tplPathHostpage ($file)
	{
		$path = config::get('site/path') . '/templates/' . (strlen($file) > 0 ? $file : 'index') . '.tpl.php';
		if (file_exists($path))
		{
			return $path;
		} else
		{
			$path = config::get('quickbox/path') . '/templates/' . (strlen($file) > 0 ? $file : 'index') . '.tpl.php';
			if (file_exists($path))
			{
				return $path;
			} else
			{
				return config::get('quickbox/path') . '/templates/messages/notFound.tpl.php';
			}
		}
	}

	function tplPathPage ($file)
	{
		$path = config::get('site/path') . '/templates/pages/' . $file . '.tpl.php';
		if (file_exists($path))
		{
			return $path;
		} else
		{
			$path = config::get('quickbox/path') . '/templates/pages/' . (strlen($file) > 0 ? $file : 'index') . '.tpl.php';
			if (file_exists($path))
			{
				return $path;
			} else
			{
				return config::get('quickbox/path') . '/templates/messages/notFound.tpl.php';
			}
		}
	}

	function tplPathComponent ($file)
	{
		$path = config::get('site/path') . '/templates/components/' . $file . '.tpl.php';
		if (file_exists($path))
		{
			return $path;
		} else
		{
			$path = config::get('quickbox/path') . '/templates/components/' . (strlen($file) > 0 ? $file : 'index') . '.tpl.php';
			if (file_exists($path))
			{
				return $path;
			} else
			{
				return config::get('quickbox/path') . '/templates/messages/notFound.tpl.php';
			}
		}
	}

	function display ()
	{
		header('Content-Type: text/html; charset='.config::get('system/charset'));
		$tContent = $this->tData['content'];
		$this->tData['pagePrefix'] = $this->tData['htmlRoot'] . $this->tData['pagePrefix'];
		$tData = $this->tData;
		include $this->tplPathHostpage($this->tData['hostpage']);
	}
}
?>