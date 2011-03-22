<?php
/**
 * This file contains the locale class.
 *
 * @package quickbox
 * @subpackage system
 * @category classes
 * @author James Cleveland
 * @copyright Copyright (c) 2007
 * @version $Id$
 * @access public
 */
class locale
{
	public static function get ()
	{
		return (janitor::notNull($_SESSION['locale']) ? $_SESSION['locale'] : 'en');
	}

	public static function set ($value)
	{
		$_SESSION['locale'] = $value;
	}
}
?>
