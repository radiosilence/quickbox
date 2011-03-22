<?php

/**
 * This file contains the dispatcher class.
 * 
 * @author James Cleveland
 * @package quickbox
 * @subpackage dispatcher
 * @category classes
 *
 */
/**
 * Dispatcher
 * 
 * Dispatchers are a layer between the classes and the templates. They are like scripts
 * that use objects. They handle actually doing things and system processes.
 */
class dispatcher
{
	private $db;
	private $dPageName;
	private $dPage;
	private $dInclude;
	private $dOutput;
	private $dConfig;

	function dispatcher ($db, $pagename)
	{
		# Same with database object
		$this->db = $db;
		# Make  the pagename safe and local.
		$this->dPageName = janitor::cleanData($pagename);
		# Get information about the current page from the database
		$query = new query();
		$query->select(
				array (
					'qbPages.*' , 
					'qbSections.name' => 'sName' , 
					'qbSections.title' => 'sTitle' , 
					'qbSections.defaultpage' => 'sDefaultPage' , 
					'qbSections.cssId' => 'sCssId'
				))->from('qbPages')->joinLeft('qbSections', 'qbPages.qbSectionId', 'id')->where('qbPages.name', 
				$this->dPageName);
		$result = $this->db->query($query);
		# If there aren't any, show a 404 page.
		if ($this->db->numRows($result) < 1)
		{
			$query = new query();
			$query->select()->from('qbPages')->where('name', '404');
			$result = $this->db->query($query);
			header("HTTP/1.1 404 Not Found");
		}
		# Put data about page into a local array
		$this->dPage = $this->db->assoc($result);
		# Start an authority check to discern if its okay for the current user to be on this page
		$this->dAuth = new authorityCheck($_SESSION['userdata']['accessLevel'], $this->dPage['accessLevel']);
		if (! $this->dAuth->isAuthed())
		{
			# If they aren't, show a 403
			$query = new $query();
			$query->select()->from('qbPages')->where('name', '403');
			$result = $this->db->query($query);
			header("HTTP/1.1 403 Forbidden");
		
			$this->dPage = $this->db->assoc($result);
		}
		$this->dPage['pageVars'] = janitor::pageVars($this->dPage['pageVars']);
		$this->dInclude = $this->dispatcherPath($this->dPage['qbDispatcher']);
	}

	function dispatcherPath ($name)
	{
		# This function quickly makes a path for the file selected based
		# on whether it exists in the site directory or the qbox.
		# first it looks in the site and if it doesnt find it, it looks qbox.
		return (file_exists(
				config::get('site/path') . '/dispatchers/' . $name . '.disp.php') ? config::get('site/path') . '/dispatchers/' .
				 $name . '.disp.php' : config::get('quickbox/path') . '/dispatchers/' . $name . '.disp.php');
	}

	function output ()
	{
		# Make GET and POST safe for the template if there are no magicquotes
		$safePost = janitor::cleanData($_POST);
		$safeGet = janitor::cleanData($_GET);
		include $this->dInclude;
		# Standard page data
		include $this->dispatcherPath("standardPage");
		$this->dOutput['debug'] = config::get('quickbox/debug');
		$this->dOutput['pageName'] = $this->dPageName;
		$this->dOutput['path'] = $this->dPage['path'];
		$this->dOutput['hostpage'] = $this->dPage['hostpage'];
		$this->dOutput['title'] = $this->dPage['title'];
		$this->dOutput['sCssId'] = $this->dPage['sCssId'];
		$this->dOutput['sName'] = $this->dPage['sName'];
		$this->dOutput['sTitle'] = $this->dPage['sTitle'];
		$this->dOutput['pageTitle'] = ($this->dOutput['pageTitle'] ? $this->dOutput['pageTitle'] : $this->dPage['pageTitle']);
		$this->dOutput['pagePrefix'] = config::get('site/pagePrefix');
		$this->dOutput['htmlRoot'] = config::get('site/htmlRoot');
		$this->dOutput['pageVars'] = $this->dPage['pageVars'];
		$this->dOutput['isDefaultPage'] = ($this->dPage['sDefaultPage'] == $this->dPageName ? true : false);
		return $this->dOutput;
	}
}
?>
