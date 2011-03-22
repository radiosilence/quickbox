<?php
/**
 * This file contains the janitor class.
 *
 * @package quickbox
 * @subpackage system
 * @category classes
 * @author James Cleveland
 *
 */
/**
 * The Janitor
 * 
 * The Janitor cleans and checks things.
 *
 */
class janitor
{

	public static function notNull ($string)
	{
		if (strlen($string) > 0)
		{
			return true;
		} else
		{
			return false;
		}
	}

	public static function noAccents ($string)
	{
		$string = htmlentities($string, ENT_COMPAT, "UTF-8");
		$string = preg_replace('/&([a-zA-Z])(uml|acute|grave|circ|tilde);/', '$1', $string);
		return html_entity_decode($string);
	}

	public static function cleanData ($data, $type = 'standard')
	{
		$magicquotes = (get_magic_quotes_gpc() == 1 ? true : false);
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = janitor::cleanData($v, $type);
			}
		} else
		{
			# Actual processing
			switch ($type)
			{
				case "standard":
					$data = ($magicquotes ? $data : addslashes($data));
					break;
				case "sql":
					$data = ($magicquotes ? database::escape(stripslashes($data)) : database::escape($data));
					break;
				case "html":
					$data = htmlentities(
					($magicquotes ? database::escape(stripslashes($data)) : database::escape($data)));
					break;
				case "integer":
					$data = intval($data);
					break;
			}
		}
		return $data;
	}

	public static function unCleanData ($data, $force = false)
	{
		if ($force)
		{
			$magicquotes = true;
		} else
		{
			$magicquotes = (get_magic_quotes_gpc() == 1 ? true : false);
		}
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = janitor::unCleanData($v, $type);
			}
		} else
		{
			$data = ($magicquotes ? stripslashes($data) : $data);
		}
		return $data;
	}

	public static function getUrlString ($string)
	# Takes a get string and amends the first symbol to be proper
	{
		if (strpos(config::get('site/pagePrefix'), '?') !== false)
		{
			$sym = '&';
		} else
		{
			$sym = '?';
		}
		if (substr($string, 0, 1) == '&')
		{
			return $sym . substr($string, 1);
		} else
		{
			return $sym . $string;
		}
	}

	public static function passwd ($string, $salt = false)
	{
		if ($salt)
		{
			$return['salt'] = $salt;
		} else
		{
			$return['salt'] = dechex(crc32(microtime()));
		}
		$return['passwd'] = sha1($string . $return['salt'] . config::get('security/salt'));
		return $return;
	}

	public static function pageVars ($string)
	# This parses the [key](value); format of page variables stored in the database into a useable array
	{
		if (preg_match_all('/\[(.+?)\]\((.+?)\);?/m', $string, $regs, PREG_PATTERN_ORDER))
		{
			foreach ($regs[1] as $k => $v)
			{
				$array[$v] = $regs[2][$k];
			}
		}
		return $array;
	}

	public static function userVars ($string)
	{
		return self::pageVars($string);
	}

	public static function utfEncode ($data)
	# getting rid of some weird characters in the database.
	{
		if (is_array($data))
		{
			foreach ($data as $k => $v)
			{
				$data[$k] = janitor::utfEncode($v);
			}
		} else
		{
			# Actual processing
			$data = utf8_encode($data);
		}
		return $data;
	}

	public static function urlEncode ($string)
	{
		$a = array (
			'*amp_*' , 
			'*q2*' , 
			'*amp*' , 
			'*q*' , 
			'+'
		);
		$b = array (
			'*amp*' , 
			'*q*' , 
			'&' , 
			'?' , 
			' '
		);
		return str_replace($b, $a, trim($string));
	}

	public static function urlDecode ($string)
	{
		$a = array (
			'*amp*' , 
			'*q*' , 
			'+' , 
			'*amp_*' , 
			'*q2*'
		);
		$b = array (
			'&' , 
			'?' , 
			' ' , 
			'*amp*' , 
			'*q*'
		);
		return trim(str_replace($a, $b, $string));
	}

	public static function neutralizeUrl ($string)
	{
		return str_replace('?', '&', $string);
	}

	public function formatMysqlDateTime ($datestring, $format)
	{
		preg_match('/([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})/i', $datestring, $result);
		$date = mktime($result[4], $result[5], $result[6], $result[2], $result[3], $result[1]);
		return date($format, $date);
	}
}
?>
