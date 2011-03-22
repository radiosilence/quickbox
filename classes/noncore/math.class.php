<?php # Mathematical Functions
class math
{

	// Rounds a number up to a certain decimal place
	function roundup ($value, $dp = 0)
	{
		return ceil($value * pow(10, $dp)) / pow(10, $dp);
	}

	// Rounds a number down to a certain decimal place
	function rounddown ($value, $dp = 0)
	{
		return floor($value * pow(10, $dp)) / pow(10, $dp);
	}
}
?>