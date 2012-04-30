<?php
	/**
	 * @author		Jan Pecha
	 * @license		http://typro.iunas.cz/license/
	 * @version		2012-04-02-1
	 */
	
	if(!($_SERVER['argc'] > 1))
	{
?>

== TyproCliCssStupidCleaner (v2012.04.02-2)

require: PHP 5.3+, CssStupidCleaner.php

usage:
	php -f TyproCliCssStupidCleaner.php -- [args]

options:
	-x overwrite the file (not implemented yet)
	-f filename1 filename2 filenameN

link: http://typro.iunas.cz/
author: Jan Pecha, <janpecha@email.cz>

<?php
	}
	else
	{
		require __DIR__ . '/CssStupidCleaner.php';
		
		$directory = getcwd();
		$files = array();
		$overwrite = FALSE;
		
		if($directory === false)
		{
			error('getcwd error', 'fatal');
			exit;
		}
		
		$args = $_SERVER['argv'];
		unset($args[0]);
		
		define('ST_START', 0);
		define('ST_FILES', 1);	// -f
		define('ST_OVERWRITE', 2); // -x
		
		$state = ST_START;
		
		foreach($args as $arg)
		{
			switch($state)
			{
				case ST_START:
					if($arg == '-f')
					{
						$state = ST_FILES;
					}
					elseif($arg == '-x')
					{
						$overwrite = true;
						$state = ST_START;
					}
					else
					{
						error("Unknown option '$arg'", 'fatal');
						exit;
					}
					break;
					
				case ST_FILES: // -f
					$files[] = $arg;
					break;
			}
		}
		
		if($state !== ST_FILES || count($files) == 0)
		{
			error('No files - use "-f <file1> <file2> <fileN>"', 'fatal');
			exit;
		}
		
		// Normalize file paths
		$_files = $files;
		$files = array();
		
		foreach($_files as $file)
		{
			if(!startsWith($file, '/'))
			{
				$file = $directory . '/' . $file;
			}
			
			$files[] = $file;
		}
		
		// Cleaning
		try
		{
			$cleaner = new \Typro\CssStupidCleaner;
			$cleaner->overwrite = $overwrite;
			$cleaner->addFiles($files);
			
			$cleaner->run();
		}
		catch(\Exception $e)
		{
			error($e->getMessage(), 'fatal');
			exit;
		}
		
		echo "\nDone.\n";
	}
	

	/**
	 * @param	string
	 * @param	string
	 * @return	void
	 */
	function error($msg, $type = 'error')
	{
		echo "[$type] $msg\n";
	}
	
	
	/**
	 * Starts the $haystack string with the prefix $needle?
	 * @link	http://api.nette.org/2.0/source-Utils.Strings.php.html#78
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function startsWith($haystack, $needle)
	{
		return strncmp($haystack, $needle, strlen($needle)) === 0;
	}
	
	
