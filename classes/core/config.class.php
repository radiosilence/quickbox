<?php
/**
 * This file contains the config class.
 * 
 * @package quickbox
 * @subpackage system
 * @category core_classes
 * @author James Cleveland
 * @access public
 */
/**
 * Configuration Manager
 *
 * Static class to get config things and give it to people.
 */
class config
{
	/**
	 * Holds all the configuration variables.
	 *
	 * @var array Configuration variables.
	 */
	private static $config;

	/**
	 * Includes the site's configuration file (and quickbox's) also pops the initialization vars into the config.
	 *
	 * @param array $init The initialization variables.
	 */
	public static function init ($init)
	{
		#  Include config files
		config::set('quickbox/path', $init['quickbox/path']);
		config::set('site/path', $init['site/path']);
		config::set('debug', $init['debug']);
		# This loops through the site config/extra directories and --
		# includes found config
		$configPath = $init['quickbox/configPath'] ? $init['quickbox/configPath'] : config::get(
		'quickbox/path') . '/config/config.conf.php';
		if (file_exists($configPath))
		{
			include $configPath;
		}
		$configPath = $init['site/configPath'] ? $init['site/configPath'] : config::get('site/path') . '/config/config.conf.php';
		if (file_exists($configPath))
		{
			include $configPath;
		}
	}

	/**
	 * Gets a config variable.
	 *
	 * @param string $key The variable to get.
	 * @return string The value.
	 */
	public static function get ($key)
	{
		return self::$config[$key];
	}

	/**
	 * Sets a config variable.
	 *
	 * @param string $key The variable to set.
	 * @param string $value The value.
	 */
	public static function set ($key, $value)
	{
		self::$config[$key] = $value;
	}
}
?>