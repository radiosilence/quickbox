<?php
/**
 * This file contains the quickbox class.
 *
 * @package quickbox
 * @author James Cleveland <jamescleveland@gmail.com>
 * @copyright Copyright (c) 2008
 * @access public
 */
/**
 * quickbox
 * 
 * Welcome to quickbox. Originally started to provide a CMS with fairly strict ways of doing things for high
 * maintainability and also to bring The Escape Committee (my company) into being able to move forward with PHP5.
 * 
 * Originally the goals were speed and small codebase. The codebase *IS* relatively small (ha ha ha) and I'm
 * continually working to make it go faster ;-)
 * 
 * I'm trying to get it to the point where it can handle roughly 200 requests per second but that is an *insane*
 * goal.
 * 
 * Consider it a weird hybrid between a framework and a CMS.
 * 
 */
class quickbox
{
	/**
	 * Data about current page.
	 *
	 * @var array
	 */
	private $qbPage;
	/**
	 * Data to be passed to the template
	 *
	 * @var array
	 */
	private $qbTemplateData;
	/**
	 * Errors to be passed to template
	 *
	 * @var array
	 */
	private $qbErrors;

	/**
	 * quickbox::__construct()
	 *
	 * @param array $init Initialization configuration
	 *
	 * Constructor which basically creates quickbox
	 * and readies it for doing things.
	 */
	public function __construct ($init)
	{
		# We need to include initialize the config class because it allows us to get and
		# set configuration variables without using a global
		require $init['quickbox/path'] . '/classes/core/config.class.php';
		config::init($init);
		define(DEBUG, config::get('debug'));
		# Start a database connection
		$this->db = new database();
		try
		{
			$this->db->init();
		} catch (Exception $e)
		{
			trigger_error(text::get('system/fatalError',$e->getMessage()), E_USER_ERROR);
		}
		require $init['quickbox/path'] . '/classes/core/metaclass.class.php';
		metaclass::init($this->db);
		# Put the post and get variables into a private for later use.
		$_POST = $_POST;
		$this->qbGet = $_GET;
		# Start the session, giving it the database connection.
		$this->qbSession = new session($this->db);
		if ($this->qbGet['page'] == 'logout')
		{
			$this->qbSession->logout();
		}
		$this->qbSession->checkCookie();
		if (strlen($_POST['user']) > 0 && $_POST['login'] == 1)
		{
			$this->qbErrors['login'] = $this->qbSession->login($_POST['user'], $_POST['password']);
		}
		$this->qbPage = ($_GET['page'] ? janitor::cleanData($_GET['page']) : 'home');
	}

	/**
	 * Puts it all together and runs it
	 * 
	 */
	public function execute ()
	{
		# Create a dispatcher and give it the database and page info
		$dp = new dispatcher($this->db, $this->qbPage);
		# Puts the dispatcher output into the template data
		$this->qbTemplateData = $dp->output();
		$this->qbTemplateData['errors'] = $this->qbErrors;
		# Create the template and give it the data
		$tpl = new template($this->qbTemplateData);
		# Output starts!
		$tpl->display();
	}
}
?>
