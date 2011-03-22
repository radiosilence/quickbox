<?php
/**
 * password.class.php
 * password datatype
 *
 * @version $Id$
 * @copyright 2007
 */
class password extends validate
{

	function dataTypeSpecific ($data, $title, $id = null, $property, $table, $reqs, $db, $properties)
	{
		if (! $this->isBothSame($data, $properties['password_check']))
		{
			$this->invalid[$property]['notBothSame'] = TRUE;
		}
		return $this->invalid;
	}

	function isBothSame ($data, $check)
	{
		if ($data == $check)
		{
			return true;
		} else
		{
			return false;
		}
	}

	function formField ($name, $title, $value = null)
	{
		return '<label for="' . $name . '">
		' . $title . '
	</label><br/><input type="password"  id="' . $name . '" name="' . $name . '" class="text" /></p><p><label for="' .
				 $name . '">
		Confirm ' . $title . '
	</label><br/>
	<input type="password" name="password_check" class="text" />';
	}
}
?>