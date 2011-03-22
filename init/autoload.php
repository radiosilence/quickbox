<?php
/**
 * Checks for site specific version of class (yes, all classes can be switched out for specific versions), and loads
 * that or the quickbox version. Triggers error (English only due to no language class) if a class is totally missing.
 *
 * @param string $class Name of the class
 * @package cradle
 * @subpackage autoloading
 */
function __autoload ($class)
{
	global $init;
	$siteClassPath = $init['site/path'] . '/classes/noncore/' . $class . '.class.php';
	$defaultClassPath = $init['quickbox/path'] . '/classes/noncore/' . $class . '.class.php';
	# Check both places
	if (file_exists($siteClassPath))
	{
		require $siteClassPath;
	} else
	{
		require $defaultClassPath;
	}
}
?>