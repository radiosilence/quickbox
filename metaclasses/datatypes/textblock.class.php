<?php
/**
 * text.datatype.php
 *
 * @version $Id$
 * @copyright 2007
 */
class textblock extends validate
{
	public static $fieldtype = 'TEXT';

	function dataTypeSpecific ()
	{
		return $this->invalid;
	}

	function formField ($name, $title, $value = null)
	{
		return '<label for="' . $name . '">
					' . $title . '
				</label><br/>
				<textarea  id="' . $name . '" name="' . $name . '" rows="5" cols="20">' . $value . '</textarea>';
	}
}
?>