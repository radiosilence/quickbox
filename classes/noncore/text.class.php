<?php # Gets text
class text
{
	public static $locale;
	private static $text;
	private static $langFileLoaded;

	public static function get ($key, $array = false)
	{
		if (! isset(self::$locale))
		{
			self::$locale = locale::get();
		}
		if (! self::$langFileLoaded)
		{
			$siteLangPath = config::get('site/path') . '/languages/' . self::$locale . '.lang.php';
			$quickboxLangPath = config::get('quickbox/path') . '/languages/' . self::$locale . '.lang.php';
			$path = (file_exists($siteLangPath) ? $siteLangPath : $quickboxLangPath);
			if (file_exists($path))
			{
				require $path;
			}
			self::$langFileLoaded = true;
		}
		try
		{
			if (janitor::notNull(self::$text[$key]))
			{
				# Deciding whether we're using vsprintf to output the string or simply
				# returning it based on whether we're giving text::get() an array of
				# values.
				if ($array)
				{
					if (! is_array($array))
					{
						$array = array (
							$array
						);
					}
					$string = vsprintf(self::$text[$key], $array);
				} else
				{
					$string = self::$text[$key];
				}
				return $string;
			} else
			{
				return text::get('system/textNotFound', $key);
			}
		} catch (Exception $e)
		{
			trigger_error(text::get('system/fatalError', $e->getMessage()), E_USER_ERROR);
		}
	}

	public static function set ($key, $string)
	{
		self::$text[$key] = $string;
	}
}
?>