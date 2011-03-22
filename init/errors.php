<?php

/**
 * errors.php
 * Error handling and debug functions.
 *
 * @copyright 2007
 * @package cradle
 * @subpackage error handling
 */
/**
 * Quickbox error handling class.
 *
 * @param integer $errno Error number.
 * @param string $errstr Error string.
 * @param string $errfile File the error is in.
 * @param integer $errline Line number.
 * @return boolean Don't execute PHP internal error handler
 */
function quickboxErrorHandler ($errno, $errstr, $errfile, $errline)
{
	switch ($errno)
	{
		case E_USER_ERROR:
			fatalError($errstr, $errfile, $errline);
			exit(1);
			break;
		case E_ERROR:
			fatalError($errstr, $errfile, $errline);
			exit(1);
			break;
		case E_USER_WARNING:
			echo '<p style="font-family: \'Lucida Grande\', \'Segoe UI\', \'Calibri\', \'Trebuchet MS\', sans-serif; padding:28px; padding-top:30px; background-color: #FFFFFF;  border: 1px solid #ccc; font-size: 1em; margin-top: 0px; margin:10px; margin-bottom: 4px;"><strong>Warning : :</strong> ' .
					 trim($errstr) . '</p></div></div>';
				break;
			default:
				break;
	}
	return true;
}

/**
 * Fatal error page.
	 * An attractive error overlay that makes it seem like everything is under control.
	 * Ahahahaha...
	 *
	 * @param string $string Error message.
	 * @param string $file File error is in.
	 * @param integer $line Line number.
	 */
function fatalError ($string, $file, $line)
{
	global $init;
	echo ($init['debug'] ? null : '<div style="width:100%;height:100%;background-color:#EEE">') . '
				<div style="float:left; width:700px; height: 450px; position:absolute; left: 50%; top: 50%; margin-left: -350px; margin-top: -225px; text-align: center;">
					<img src="http://ix0.nl/haywire/dialog-warning.png" style="margin-bottom: 15px; border: 0px solid #ccc;"/>
					<p style="font-family: \'Lucida Grande\', \'Segoe UI\', \'Calibri\', \'Trebuchet MS\', sans-serif; padding:28px; padding-top:30px; background-color: #FFFFFF;border: 1px solid #ccc; font-size: 1em; margin-top: 0px; margin:10px; margin-bottom: 4px;">
						<strong style="color:red;">
							[Fatal]</strong>&nbsp;' . trim($string) . ($init['debug'] ? ' in ' .
			 $file . ', line ' . $line . '.' : null) . '
					</p>
				</div>
			</div>
		' . ($init['debug'] ? null : '</div>');
}

		/**
 * Weird little script to trigger an xdebug info box.
		 * I use this to test for bottlenecks before I discovered cache grind. I'll leave it in incase I need it for something.
		 *
		 * @param string $msg A message, useful for identifying a break point.
		 */
function xdebugInfo ($msg = false)
{
	if ($init['debug'])
	{
		trigger_error(
				'[xdbInfo] ' . ($msg ? '<span style="color:white;background-color:#D60;">' . $msg . '</span> | ' : null) .
						 '
    Mem: <span style="color:white;background-color:#D60;">' . Round(
								(xdebug_memory_usage() / 1000), 2) . '
    Kb</span>, Time: <span style="color:white;background-color:#D60;">' . str_pad(
								Round(xdebug_time_index(), 4), 6, '0') . 'secs</span>', E_USER_WARNING);
	}
}
?>