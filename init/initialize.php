<?php # Start the wheels
/**
 * initialize
 * 
 * This script initializes quickbox and loads in some vital autoloading and exception handling scripts.
 * @package cradle
 */
if ($init['debug'])
{
	#  Start Timer
	$stimer = explode(' ', microtime());
	$stimer = $stimer[1] + $stimer[0];
}
# Make the encoding UTF8
if (! $init['noCustomErrorHandler'])
{
	# Include low level error handling and debug cradle.
	require_once $init['quickbox/path'] . '/init/errors.php';
	# Set the error handler
	set_error_handler('quickboxErrorHandler');
}
require_once $init['quickbox/path'] . '/init/autoload.php';
# Including the quickbox class
require_once $init['quickbox/path'] . '/classes/core/quickbox.class.php';
# Here we go...
# Initialize
$qb = new quickbox($init);
# Run
$qb->execute();
if ($init['debug'])
{
	#  End Timer
	$etimer = explode(' ', microtime());
	$etimer = $etimer[1] + $etimer[0];
	echo '<div  style="color:#444; background-color:#FFF"><pre>';
	printf("Script timer: <b>%f</b> seconds.", ($etimer - $stimer));
	echo $totalquery.' querys.</pre></div>';
}
# The cake is a lie.
?>