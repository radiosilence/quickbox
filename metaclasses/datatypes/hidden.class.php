<?php
/**
 * hidden.datatype.php
 *
 * @version $Id$
 * @copyright 2007
 */
class hidden extends validate
{

	function dataTypeSpecific ()
	{
		return $this->invalid;
	}

	function formField ($name, $title, $value = null)
	{
		return '<input type="hidden" name="' . $name . '" value="' . $value . '" />';
	}
}
?>