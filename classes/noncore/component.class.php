<?php

/**
 * This file contains the component class.
 * 
 * @package quickbox
 * @subpackage dispatcher
 * @category classes
 */
/**
 * Components
 * 
 * Components are sort of a mini-dispatcher, for use by dispatchers.
 * They work on the same level as dispatchers but are designed for reuse.
 */
class component
{
	/**
	 * Database to use
	 *
	 * @var object
	 */
	private $db;
	/**
	 * The path to a component.
	 *
	 * @var string
	 */
	private $cPath;
	/**
	 * The information given back to the dispatcher.
	 *
	 * @var array
	 */
	private $cOutput;
	/**
	 * Information about the current page.
	 *
	 * @var array
	 */
	private $cPage;

	/**
	 * Sets up the necessary environment for a component.
	 *
	 * @param object $db
	 * @param string $type
	 * @param array $page
	 */
	function __construct ($db, $type, $page = null)
	{
		$this->cPage = $page;
		$this->db = $db;

		$sitePath = config::get('site/path') . '/components/' . $type . '.com.php';
		$qbPath = config::get('quickbox/path') . '/components/' . $type .
				 '.com.php';
		if(file_exists($sitePath)) {
			$this->cPath = $sitePath;
		} else {
			$this->cPath = $qbPath;
		}
	}

	/**
	 * Returns a components output.
	 *
	 * @return array
	 */
	function output ()
	{
		require $this->cPath;
		return $this->cOutput;
	}
}
?>