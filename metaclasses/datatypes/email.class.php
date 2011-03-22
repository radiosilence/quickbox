<?php

/**
 * email.class.php
 * datatype
 *
 * @version $Id$
 * @copyright 2007
 */
class email extends validate
{

	function dataTypeSpecific ($data, $title, $id = null, $property, $table)
	{
		if (! $this->isAlphaNumeric($data))
		{
			$this->invalid[$property]['realEmail'] = TRUE;
		}
		return $this->invalid;
	}

	function isAlphaNumeric ($data)
	{
		if (preg_match(
				'/\A(?:[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?)\Z/m', 
				$data))
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
	<input type="text"  id="' . $name . '" name="' . $name . '" value="' . $value . '" class="' . ($linkfield ? 'title' : 'text') .
				 '" name="' . $name . '"/> (e-mail)';
	}
}
?>