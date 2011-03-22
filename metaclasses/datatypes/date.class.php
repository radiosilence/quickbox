<?php
/**
 * date.class.php
 *
 * @version $Id$
 * @copyright 2008
 */
class date extends validate
{
	public static $fieldtype = 'DATE';

	function dataTypeSpecific ($data, $title, $id = null, $property, $table)
	{
		if (! $this->isValidDate($data))
		{
			$this->invalid[$property]['validDate'] = TRUE;
		}
		return $this->invalid;
	}

	function isValidDate ($data)
	{
		if (ereg('(18|19|20|21)[0-9]{2}[- /.](0[1-9]|1[012])[- /.](0[1-9]|[12][0-9]|3[01])', $data))
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
	<input type="text"  id="' . $name . '"  name="' . $name . '" value="' . $value .
		 '" class="'.($linkfield ? 'title' : 'text').'" />';
	}
}
?>