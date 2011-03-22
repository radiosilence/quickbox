<?php
/**
 * This file contains the database class.
 *
 * @package quickbox
 * @subpackage database
 * @category classes
 * @author James Cleveland
 * @copyright Copyright (c) 2007
 * @access public
 */
/**
 * Database Interface
 * 
 * All communication with the database should be done through an instance of this class.
 */
class database
{
	/**
	 * Contains details about the database such as host, login, etc.
	 *
	 * @var unknown_type
	 */
	private $dbDetails;
	private $dbNumQuery;
	private $clientEncoding;

	/**
	 * database::database()
	 * Sets up variables for database.
	 */
	function __construct ($details = null)
	{
		if ($details)
		{
			$this->dbDetails['hostname'] = $details['hostname'];
			$this->dbDetails['username'] = $details['username'];
			$this->dbDetails['password'] = $details['password'];
			$this->dbDetails['database'] = $details['database'];
		} else
		{
			$this->dbDetails['hostname'] = config::get('database/hostname');
			$this->dbDetails['username'] = config::get('database/username');
			$this->dbDetails['password'] = config::get('database/password');
			$this->dbDetails['database'] = config::get('database/database');
		}
		$this->dbNumQuery = 0;
	}

	/**
	 * database::init()
	 *
	 * @return boolean Returns true if all went okay, or throws an Exception
	 * Connects to database
	 */
	public function init ()
	{
		if ($this->connect())
		{
			if (mysql_select_db($this->dbDetails['database'], $this->connection))
			{
				return true;
			} else
			{
				throw new Exception(
						text::get('system/dbNoConnect') . (config::get('debug') ? ' ' . mysql_error() : null));
			}
		} else
		{
			throw new Exception(text::get('system/dbNoDb') . (config::get('debug') ? ' ' . mysql_error() : null));
		}
	}

	/**
	 * database::stats()
	 *
	 * @return integer How many queries done.
	 *
	 */
	public function stats ()
	{
		return $this->dbNumQuery;
	}

	/**
	 * database::connect()
	 *
	 * @return boolean true if it connected, false if it did not.
	 */
	public function connect ()
	{
		if ($this->connection = mysql_pconnect($this->dbDetails['hostname'], $this->dbDetails['username'], 
				$this->dbDetails['password']))
		{
			$this->brokenEncoding = config::get("sql/brokenEncoding");
			return true;
		} else
		{
			return false;
		}
	}

	/**
	 * database::disconnect()
	 *
	 * Closes this object's connection
	 */
	public function disconnect ()
	{
		mysql_close($this->connection);
	}

	/**
	 * database::query()
	 *
	 * @param string $query The query to be fed to the database.
	 * @return mixed Returns the result of the query.
	 */
	public function query ($query)
	{
		global $totalquery;
		$totalquery++;
		if ($result = mysql_query($query, $this->connection))
		{
			return $result;
		} else
		{
			if (config::get('debug'))
			{
				$debugdata = debug_backtrace();
				$debugData = $debugdata[0]['file'] . ', line ' . $debugdata[0]['line'];
			}
			trigger_error(
					text::get('system/dbError') . (config::get('debug') ? ' ' . mysql_error() . '<br/>[ Called from: ' .
							 $debugData . ' ]<br/>' : null), E_USER_ERROR);
			return false;
		}
	}

	/**
	 * database::escape()
	 *
	 * @param string $string
	 * @return Returns escaped string.
	 */
	public static function escape ($string)
	{
		return mysql_real_escape_string($string);
	}

	/**
	 * database::assoc()
	 *
	 * @param mixed $result
	 * @return array Associative array of items.
	 */
	public function assoc ($result)
	{
		$result = mysql_fetch_assoc($result);
		if ($this->brokenEncoding == true)
		{
			$result = janitor::utfEncode($result);
		}
		return $result;
	}

	/**
	 * database::numRows()
	 *
	 * @param mixed $result
	 * @return integer Number of results
	 */
	public static function numRows ($result)
	{
		return mysql_num_rows($result);
	}

	/**
	 * database::tableExists()
	 *
	 * @param mixed $result
	 * @return integer Whether it exists
	 */
	public function tableExists ($table)
	{
		$result = $this->query("SHOW TABLE STATUS LIKE '$table'");
		return ($this->numRows($result) == 1);
	}

	public function getProperty ($property)
	{
		if ($property !== 'password')
		{
			return $this->dbDetails[$property];
		}
	}
}
?>
