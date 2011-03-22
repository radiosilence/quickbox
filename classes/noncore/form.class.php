<?php
/**
 * This file contains the form class.
 * 
 * @package quickbox
 * @subpackage html
 * @category classes
 * @author James Cleveland
 *
 */
/**
 * Form Generator
 * 
 * Makes forms. HTML ALERT!!
 */
class form
{
	public $output;
	private $rows;
	private $head;
	private $footer;
	private $scripts;

	function form ($action, $method = 'post', $id = null, $invalid = false, $title = false, $submitted = false)
	{
		$this->header .= '<form action="' . $action . '" method="' . $method . '"' . ($id ? " id=\"$id\"" : null) . '><fieldset>';
		if ($title)
		{
			$this->header .= '<h2>' . $title . '</h2>';
		}
		if ($submitted)
		{
			$this->header .= '<br/>';
			if (count($invalid) > 0)
			{
				$this->header .= '<h6>' . text::get('validation/errorsProcessing') . '</h6><br/>';
				foreach ($invalid as $k => $v)
				{
					$this->header .= '<p class="error">' . $v . '</p>';
				}
			} else
			{
				$this->header .= '<p class="success">' . text::get('validation/success') . '</p>';
			}
		}
		$this->footer .= '
	<p>
	      <button type="submit" class="button positive">
      	<img src="' . config::get(
		'site/htmlRoot') . 'css/blueprint/plugins/buttons/icons/tick.png" alt="Save"/> ' . text::get('form/save') . '
      </button>
      		  	  <button type="reset" class="button negative">
  	    <img src="' .
		 config::get('site/htmlRoot') . 'qbres/images/no.png" alt="Reset"/> ' . text::get(
		'form/reset') . '
  	  </button></p>
</fieldset></form>';
	}

	function create ()
	{
		# Put everything together and go!
		$this->output .= $this->header;
		$this->output .= implode("\n", $this->scripts);
		$this->output .= '<p>';
		$this->output .= implode("\n</p>\n<p>\n\t", $this->rows);
		$this->output .= '</p>';
		$this->output .= $this->footer;
		return $this->output;
	}

	function addField ($name, $type, $value = null, $title = null, $linkfield = false, $properties = false)
	{
		$dataType = metaclass::create($type, 'datatypes');
		if ($dataType == null)
		{
			$dataType = metaclass::create('string', 'datatypes');
		}
		if (is_array($dataType->scripts))
		{
			foreach ($dataType->scripts as $k => $v)
			{
				$this->scripts[$k] = $v;
			}
		}
		$this->rows[] = $dataType->formField($name, $title, $value, $linkfield, $properties);
		$dataType = null;
	}
}
?>