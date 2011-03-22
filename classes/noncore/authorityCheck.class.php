<?php

/**
 * This file contains the authorityCheck class.
 * 
 * @package quickbox
 * @subpackage dispatcher
 * @author James Cleveland
 */
/**
 * Authority Checking
 *
 * This class is called by the dispatcher and handles anything and everything to do with seeing
 * if the user can do this or that. It is simple at the moment but could be expanded, adding
 * many rules.
 */
class authorityCheck
{
	/**
	 * The authorization level of the requested page.
	 * 
	 * @var integer
	 */
	private $acPageAuth;
	/**
	 * The authorization level of the current user.
	 *
	 * @var integer
	 */
	private $acUserAuth;

	/**
	 * Set up the two variables.
	 *
	 * @param integer $userAuth The authorization level of the current user.
	 * @param integer $pageAuth The authorization level of the requested page.
	 * @return authorityCheck
	 */
	function __construct ($userAuth, $pageAuth)
	{
		$this->acPageAuth = $pageAuth;
 		$this->acUserAuth = $userAuth;
	}

	/**
	 * Check if the authorization is okay.
	 *
	 * @return boolean Whether its okay.
	 */
	function isAuthed ()
	{
		if ((strlen($this->acUserAuth) > 0 ? $this->acUserAuth : 0) >= $this->acPageAuth)
		{
			$status = true;
		} else
		{
			$status = false;
		}
		return $status;
	}
}
?>