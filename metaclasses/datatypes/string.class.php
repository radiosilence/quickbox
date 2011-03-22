<?php

/**
 * string.datatype.php
 *
 * @version $Id$
 * @copyright 2007
 */
class string extends validate
{

	function dataTypeSpecific ()
	{
		return $this->invalid;
	}

	function formField ($name, $title, $value = null, $linkfield = false)
	{
		return '<label for="' . $name . '">
		' . $title . '
	</label><br/>
	<input type="text" id="' . $name . '" name="' . $name . '" value="' .
				 $value . '" class="' . ($linkfield ? 'title' : 'text') . '" />';
	}
}
?>