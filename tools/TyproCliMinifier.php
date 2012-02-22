<?php
	/**
	 * @author		Jan Pecha
	 * @license		http://typro.iunas.cz/license/
	 * @version		2012.02.10-1
	 */
	
	if(!($_SERVER['argc'] > 1))
	{
?>

== TyproCliMinifier (v2012.01.29-1)

require: PHP 5.3+, CssMinifier.php

usage:
	php -f typroCliMinifier.php -- [args]

options:
	-n <generated file name = 'min' . date('YmdHis') . 'css'>
	-p <path to Typro directory = current directory>
	-t add date and time on begin of file (-t or -t1 = ON; -t0 = OFF)
	-f <CSS file 1> <CSS file 2> <CSS file 3> ...
	   * <CSS file> = path to a CSS file
	   * <Typro file> = @module.name (@default, @reset, @paragraph.indent,...)
	   * <Typro Idea file> = @idea.module.name (@idea.grid)

link: http://typro.iunas.cz/
author: Jan Pecha, <janpecha@email.cz>

<?php
	}
	else
	{
		require __DIR__ . '/CssMinifier.php';
		
		$typroDir = getcwd();
		$filename = null;
		$typroFiles = array();
		$files = array();
		$addDateTime = true;
		
		if($typroDir === false)
		{
			error('getcwd error', 'fatal');
			exit;
		}
		
		$args = $_SERVER['argv'];
		unset($args[0]);
		
		define('ST_START', 0);
		define('ST_FILENAME', 1);
		define('ST_PATH', 2);
		define('ST_FILES', 3);
		
		$state = ST_START;
		
		foreach($args as $arg)
		{
			switch($state)
			{
				case ST_START:
					if($arg == '-n')
					{
						$state = ST_FILENAME;
					}
					elseif($arg == '-p')
					{
						$state = ST_PATH;
					}
					elseif($arg == '-f')
					{
						$state = ST_FILES;
					}
					elseif($arg == '-t' || $arg == '-t1')
					{
						$addDateTime = true;
						$state = ST_START;
					}
					elseif($arg == '-t0')
					{
						$addDateTime = false;
						$state = ST_START;
					}
					else
					{
						error("Unknown option '$arg'", 'fatal');
						exit;
					}
					break;
					
				case ST_FILENAME:	// -n
					$filename = $arg;
					$state = ST_START;
					break;
				
				case ST_PATH:	// -p
					$typroDir = $arg;
					$state = ST_START;
					break;
				
				case ST_FILES: // -f
					if($arg[0] == '@')	// typro module
					{
						$typroFiles[] = substr($arg, 1);
					}
					else
					{
						$files[] = $arg;
					}
					break;
			}
		}
		
		if($state !== ST_FILES || (count($typroFiles) + count($files)) == 0)
		{
			error('No files - use "-f <file1> <file2> <fileN>"', 'fatal');
			exit;
		}
		
		// Normalize Typro Files
		$_typroFiles = $typroFiles;
		$typroFiles = array();
		
		foreach($_typroFiles as $file)
		{
			if(startsWith($file, 'idea.'))	// <typroDir>/idea/idea.typro.<module>.css
			{
				$file = 'ideas/idea.typro.' . substr($file, 5);
			}
			else	// <typroDir>/typro.<module>.css
			{
				$file = 'typro.' . $file;
			}
			
			$typroFiles[] = $typroDir . '/' . $file . '.css';
		}
		
		/// TODO: typro -> serazeni souboru
		
		try
		{
			$minifier = new \Typro\CssMinifier;
			$minifier->filename = $filename;
			$minifier->addFiles($typroFiles);
			$minifier->addFiles($files);
			
			$minifier->generate($addDateTime);
		}
		catch(\Exception $e)
		{
			error($e->getMessage(), 'fatal');
			exit;
		}
		
		echo "\nDone.\n";
	}
	

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
	
	
