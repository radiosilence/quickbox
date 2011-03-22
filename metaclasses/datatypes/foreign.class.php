<?php
/**
 * foreign.class.php
 * foreign key datatype
 * @version $Id$
 * @copyright 2007
 */
class foreign extends validate
{

	function dataTypeSpecific ($data, $title, $id, $property, $table, $requirements, $db)
	{
		$this->db = $db;
		if (! $this->isRealItem($data, $table, $requirements['real']))
		{
			$invalid[$property]['realItem'] = TRUE;
		}
		return $invalid;
	}

	function isRealItem ($data, $table, $real)
	{
		$realVars = metaclass::getClassVars($real);
		$query = "SELECT id FROM `" . $realVars['table'] . "` WHERE `id` = '$data'";
		if ($this->db->tableExists($realVars['table']))
		{
			$result = $this->db->query($query);
			$numRows = $this->db->numRows($result);
		} else
		{
			return false;
		}
		if ($numRows > 0)
		{
			return true;
		} else
		{
			return false;
		}
	}

	function formField ($name, $title, $value = null, $linkfield, $properties)
	{
		$cvs = metaclass::getClassVars($properties['requirements']['real']);
		if (metaclass::$db->tableExists($cvs['table']))
		{
			$query = new query();
			$query->select(array (
				'id' , 
				'title'
			))->from($cvs['table']);
			$result = metaclass::$db->query($query);
			$return .= '<label for="' . $name . '">
							' . $title . '
						</label><br/>
						<select  id="' . $name . '" name="' . $name . '">';
			while ($row = metaclass::$db->assoc($result))
			{
				$return .= '<option value="' . $row['id'] . '"' . ($row['id'] == $value ? ' selected' : null) . '>' .
						 $row['title'] . '</option>';
			}
			$return .= '</select>
						<br/>';
		} else
		{
			$return .= '<label for="' . $name . '">
							' . $title . '
						</label><br/>';
			$return .= '<p class="error">' . text::get('form/foreignTableNotExist', $cvs['title']) . '</p>';
		}
		return $return;
	}
}
?>