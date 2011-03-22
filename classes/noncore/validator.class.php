<?php
/**
 * Validator for old admin panel.
 * 
 * @deprecated
 *
 */
class validator
{
	private static $db;

	function __construct ($db)
	{
		$this->db = $db;
	}

	public function init ($db)
	{
		self::$db = $db;
	}

	public function arrayItems ($array, $items)
	{
		// The second variable, $items, is an array of specific keys to check
		foreach ($items as $k => $v)
		{
			if (strlen($array[$k]) > 0)
			{
				$output['valid'][$k] = $v;
			} else
			{
				$output['invalid'][$k] = $v;
			}
		}
		return $output;
	}

	public function isTaken ($array, $items, $table)
	{
		if (strlen($items) > 0)
		{
			// The second variable, $items, is an array of specific keys to check
			foreach ($items as $k => $v)
			{
				$result = $this->db->query("SELECT `$k` FROM `$table` WHERE `$k` = '" . $array[$k] . "'");
				if ($this->db->numRows($result) > 0)
				{
					$output['invalid'][$v] = $array[$k];
				} else
				{
					$output['valid'][$v] = $array[$k];
				}
			}
		}
		return $output;
	}

	/* Decent new functions */
	function isUnique ($fieldname, $fieldtable, $string)
	{
		$result = $this->db->query("SELECT `$k` FROM `$table` WHERE `$k` = '" . $array[$k] . "'");
		if ($this->db->numRows($result) == 0)
		{
			return true;
		} else
		{
			return false;
		}
	}
}
?>