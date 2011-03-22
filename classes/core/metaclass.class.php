<?php
/**
 * This file contains the metaclass class.
 * 
 * @author James Cleveland
 * @copyright 2008
 * @package quickbox
 * @subpackage metaclass
 * @category core_classes
 *
 */
/**
 * Metaclass
 * 
 * The idea is that every class has a database table, and this class handles
 * putting things into and out of the database, creating the database needed
 * generating queries, etc.
 * Not that it should be used for every part of the system, just most of it.
 * It is built using php's existing class system, for speed.
 * Note that this class will probably be complex and messy, the idea being that
 * it means that other code doesn't have to be.
 */
class metaclass
{
	/**
	 * Classes that have been loaded into memory so do not need to be loaded again.
	 *
	 * @var array
	 */
	public static $classesLoaded;
	/**
	 * Database object for use by static metaclass calls.
	 *
	 * @var object
	 */
	public static $db;
	/**
	 * Tables that we know exist so don't need to check for / create.
	 *
	 * @var array
	 */
	public static $tablesLoaded;
	/**
	 * Invalid items to be passed back to the calling source (eg. Scaffold)
	 *
	 * @var array
	 */
	public $invalid;
	/**
	 * Id of current object being handled.
	 *
	 * @var string
	 */
	public $id;
	/**
	 * Properties of the current object as defined in $definitions.
	 *
	 * @var array
	 */
	public $properties;
	/**
	 * The original properties of an object, when it was loaded. For checking changes, etc.
	 *
	 * @var array
	 */
	public $originalProperties;
	/**
	 * Property definitions of current object.
	 *
	 * @var array
	 */
	private $definitions;
	/**
	 * Database table current object uses.
	 *
	 * @var string
	 */
	private $table;
	/**
	 * Class type of object being handled.
	 *
	 * @var string
	 */
	private $type;
	/**
	 * Title of current object class type being handled.
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Sets up all the variables needed when a metaobject is instantiated.
	 */
	public function __construct ()
	{
		$classVars = metaclass::getClassVars(get_class($this));
		$this->definitions = $classVars['propertyDefinitions'];
		$this->table = $classVars['table'];
		$this->id = $classVars['id'];
		$this->type = $class;
		$this->title = $classVars['title'];
	}

	/**
	 * Called by quickbox to assign the static database object.
	 *
	 * @param object $db
	 */
	public static function init ($db)
	{
		self::$db = $db;
	}

	/**
	 * Makes sure a class file is loaded. If it cannot, skip it. If it can, add it to the list of loaded classes.
	 * This doesn't return anything. It either loads the class and proceeds, sees the class is loaded and proceeds,
	 * or fails to find the class and throws an exception.
	 *
	 * @param string $class Name of the class.
	 * @param string $rel Extra path to the class, used for datatypes primarily.
	 */
	public static function loadClass ($class, $rel = null)
	{
		try
		{
			$classString = ($rel ? $rel . '/' : null) . $class;
			if (! in_array($classString, metaclass::$classesLoaded))
			{
				$siteMetaPath = config::get('site/path') . '/metaclasses/' . ($rel ? $rel . '/' : null) . $class . '.class.php';
				$quickboxMetaPath = config::get('quickbox/path') . '/metaclasses/' . ($rel ? $rel . '/' : null) . $class .
						 '.class.php';
				$path = file_exists($siteMetaPath) ? $siteMetaPath : $quickboxMetaPath;
				if (file_exists($path))
				{
					# Require the $class file path.
					require_once $path;
					metaclass::$classesLoaded[] = $classString;
				} else
				{
					# If the class file is missing, we're fucked.
					throw new Exception(text::get('metaclass/missingClassFile', $class));
				}
			}
		} catch (Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}

	/**
	 * Perform the standard operations to load a metaclass. Basically re-inventing autoloading for the specific purpose.
	 *
	 * @param string $class Name of the class.
	 * @param string $rel Extra path to the class, used for datatypes primarily.
	 * @return object An instance of the required class, ready for use.
	 */
	public static function create ($class, $rel = null)
	{
		$classString = ($rel ? $rel . '/' : null) . $class;
		metaclass::loadClass($class, $rel);
		return new $class();
	}

	/**
	 * Loads a specific stored metaobject from the database table.
	 *
	 * @param string $class Type of the class.
	 * @param integer|string $id The Id of the specific instance.
	 * @return object The loaded object, ready for use.
	 */
	public static function load ($class, $id)
	{
		$object = metaclass::create($class);
		if (! metaclass::checkTable($object))
		{
			metaclass::createTable($object->table, $object->definitions);
		}
		$query = new query();
		$query->select()->from($object->table)->where('id', $id)->limit(1);
		$result = self::$db->query($query);
		$row = self::$db->assoc($result);
		$object->setId($id);
		foreach ($object->definitions as $k => $v)
		{
			$object->setProperty($k, $row[$k]);
			$object->setProperty($k, $row[$k], true);
		}
		$object->id = $id;
		return $object;
	}

	/**
	 * Initiates a call to the saveObject function. This allows a class to have a custom save handler (to process
	 * information) and still call the same saving process.
	 *
	 * @uses metaclass::saveObject;
	 */
	public function save ()
	{
		$this->saveObject();
	}

	/**
	 * Saves an instantiated object to its respective table as a record.
	 *
	 * @return boolean Whether or not object could be saved.
	 */
	public function saveObject ()
	{
		try
		{
			# Validation bit
			$validate = new validate(metaclass::$db);
			$validate->exec($this);
			if (! $validate->valid)
			{
				$this->invalid = $validate->invalid;
				return false;
			} else
			{
				if (! metaclass::checkTable($this))
				{
					metaclass::createTable($this->table, $this->definitions);
				}
				if (janitor::notNull($this->id))
				{
					# Updating existing row, editing object.
					foreach ($this->definitions as $k => $v)
					{
						$queryStatements[$k] = janitor::cleanData($this->properties[$k], 'sql');
					}
					$query = new query();
					$query->update($this->table)->set($queryStatements)->where('id', janitor::cleanData($this->id));
					if (! metaclass::$db->query($query))
					{
						return false;
					}
				} else
				{
					# Inserting new row, creating object.
					foreach ($this->properties as $k => $v)
					{
						$queryFields[] = $k;
						$queryData[] = janitor::cleanData($v, 'sql');
					}
					$query = new query();
					$query->insert($this->table, $queryFields)->values($queryData);
					if (! metaclass::$db->query($query))
					{
						return false;
					}
				}
				return true;
			}
		} catch (Exception $e)
		{
			trigger_error($e->getMessage(), E_USER_ERROR);
		}
	}

	/**
	 * Deletes the current object from the database :'-(
	 *
	 * @todo Make the query use the query class.
	 */
	public function delete ()
	{
		# This function has no confirmation, that should be written into the calling script.
		$query = 'DELETE FROM `' . $this->table . '` WHERE `id` = \'' . $this->id . '\' LIMIT 1';
		metaclass::$db->query($query);
	}

	/**
	 * Basically the same as get_class_vars but uses loadClass to check / load the requested class *just in case*.
	 * Very useful for datatypes.
	 *
	 * @param string $class The class name.
	 * @param string $rel Path to the class
	 * @return array Array of the class's variables.
	 */
	public static function getClassVars ($class, $rel = false)
	{
		self::loadClass($class, $rel);
		return get_class_vars($class);
	}

	/**
	 * Creates a table in the image of the property definitions supplied to it.
	 *
	 * @param string $tableName What the table will be called.
	 * @param array $propertyDefinitions The structure of the table.
	 * 
	 * @todo Need to make this use the query class.
	 */
	private static function createTable ($tableName, $propertyDefinitions)
	{
		$query .= " CREATE TABLE `" . metaclass::$db->getProperty('database') . "`.`$tableName` (\n";
		$query .= " `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,\n";
		foreach ($propertyDefinitions as $k => $v)
		{
			$vars = metaclass::getClassVars($v['type'], 'datatypes');
			$querylines[] .= "`$k` " . (janitor::notNull($vars['fieldtype']) ? $vars['fieldtype'] : 'VARCHAR( 255 )') .
					 " NOT NULL ";
		}
		$query .= implode(",\n", $querylines);
		$query .= ")";
		metaclass::$db->query($query);
		metaclass::$tablesLoaded[] = $tableName;
	}

	/**
	 * Checks if a table exists. Records whether it exists.
	 *
	 * @param object $object The instantiated object.
	 * @return boolean Whether it exists or not.
	 */
	private static function checkTable ($object)
	{
		if (! in_array($object->table, metaclass::$tablesLoaded))
		{
			if (! metaclass::$db->tableExists($object->table))
			{
				return false;
			} else
			{
				metaclass::$tablesLoaded[] = $object->table;
				return true;
			}
		} else
		{
			return true;
		}
	}

	/**
	 * Sets a property in an object so we can keep those things private.
	 *
	 * @param string $property The property to set
	 * @param boolean $original Whether we are setting the current property or the original.
	 * @param string $value What to set it to.
	 */
	public function setProperty ($property, $value, $original)
	{
		if ($original)
		{
			$this->originalProperties[$property] = $value;
		}
		$this->properties[$property] = $value;
	}

	/**
	 * Set the current objects Id
	 *
	 * @param string|integer $value The value of Id to set.
	 */
	private function setId ($value)
	{
		$this->id = $value;
	}

	/**
	 * Surprisingly like metaclass::setProperty(), but the other way round.
	 *
	 * @param string $property The property to get.
	 * @param boolean $original Whether we are getting the current property or the original.
	 * @return string The value of the property.
	 */
	public function getProperty ($property, $original)
	{
		if ($original)
		{
			return $this->originalProperties[$property];
		}
		return $this->properties[$property];
	}

	/**
	 * Get a list of objects from the database.
	 *
	 * @todo Make this way way more flexible.
	 * @param string $table The table to query.
	 * @param array $fields Specific fields to retrieve.
	 * @return array Array of row data.
	 */
	public function getItems ($table, $fields = false, $order = false)
	{
		$query = new query();
		$query->select($fields)->from($table)->order($order);
		
		$result = metaclass::$db->query($query);
		while ($row = metaclass::$db->assoc($result))
		{
			$return[] = $row;
		}
		return $return;
	}
}
?>