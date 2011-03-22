<?php # Validation based on properties and methods defined by metaclass datatypes.
class validate
{
	private static $db;
	public $valid;
	private $properties;
	public $invalid;
	private $object;

	function __construct ($db = null)
	{
		if ($db)
		{
			self::$db = $db;
		}
	}

	function exec ($object)
	{
		$this->object = $object;
		$this->db = $db;
		$classVars = metaclass::getClassVars(get_class($object));
		foreach ($classVars['propertyDefinitions'] as $k => $v)
		{
			$dataType = metaclass::create($v['type'], 'datatypes');
			if ($dataType == null)
			{
				$dataType = metaclass::create('string', 'datatypes');
			}
			$this->check($dataType, $object->properties[$k], $v['title'], $object->id, $k, $classVars['table'], 
					$v['requirements']);
			$dataType = null;
		}
		if ($this->invalid)
		{
			$this->valid = false;
		} else
		{
			$this->valid = true;
		}
	}

	private function check ($dataType, $data, $title, $id = null, $property, $table, $requirements = null)
	{
		if (is_array($requirements))
		{
			foreach ($requirements as $k => $v)
			{
				switch ($k)
				{
					case "exists":
						if (strlen($data) <= 0)
						{
							$this->invalid[$property][$k] = TRUE;
						}
						break;
					case "unique":
						if (! $this->isUnique($data, $property, $table, $id))
						{
							$this->invalid[$property][$k] = TRUE;
						}
						break;
				}
			}
		}
		$datatypeReturn = $dataType->dataTypeSpecific($data, $title, $id = null, $property, $table, $requirements, 
				self::$db, $this->object->properties);
		if (is_array($datatypeReturn))
		{
			foreach ($datatypeReturn as $k => $v)
			{
				foreach ($v as $z => $x)
				{
					$this->invalid[$k][$z] = $x;
				}
			}
		}
	}

	# Globally useable validation functions
	private function isUnique ($data, $field, $table, $id = null)
	{
		if (! in_array($table, metaclass::$tablesLoaded))
		{
			if (! self::$db->tableExists($table))
			{
				$table = false;
			}
		}
		if ($table)
		{
			$query = "SELECT `id` FROM `$table` WHERE `id` != '$id' AND `$field` = '$data'";
			$result = self::$db->query($query);
			if (self::$db->numRows($result) > 0)
			{
				return false;
			} else
			{
				return true;
			}
		} else
		{
			return true;
		}
	}
}
?>