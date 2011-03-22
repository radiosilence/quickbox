<?php # To make URL.
class url
{
	var $sym;

	function amp ()
	{
		# This gets the latest url divider symbol
		{
			if ($this->sym !== '&')
			{
				$this->sym = '&';
				return '?';
			} else
			{
				return '&';
			}
		}
	}
}