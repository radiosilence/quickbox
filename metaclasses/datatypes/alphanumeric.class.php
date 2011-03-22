<?php
/**
 * username.class.php
 * datatype
 *
 * @version $Id$
 * @copyright 2007
 */
class alphanumeric extends validate
{

	function dataTypeSpecific ($data, $title, $id = null, $property, $table)
	{
		if (! $this->isAlphaNumeric($data))
		{
			$this->invalid[$property]['alphaNumeric'] = TRUE;
		}
		return $this->invalid;
	}

	function isAlphaNumeric ($data)
	{
		if (ereg("^[a-zA-Z0-9]*$", $data))
		{
			return true;
		} else
		{
			return false;
		}
	}

	function formField ($name, $title, $value = null, $linkfield = false)
	{
		return '<label for="' . $name . '">
		' . $title . '
	</label><br/>
	<input type="text"  id="' .
				 $name . '" name="' . $name . '" value="' . $value . '"  class="' . ($linkfield ? 'title' : 'text') .
				 '"
		 /> (a-z, A-Z, 0-9)';
	}
}
?>