<?php
	/** CLI CSS Minifier
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-07-20-1
	 */
	
	define('ST_START', 0);
	define('ST_FILENAME', 1);
	define('ST_PATH', 2);
	define('ST_FILES', 3);
	
	if($_SERVER['argc'] > 1)
	{
		require __DIR__ . '/libs/TyproMinifier.php';
		require __DIR__ . '/libs/CssMinifier.php';
		
		$arguments = $_SERVER['argv'];
		unset($arguments[0]);
		
		$arguments = parseArguments($arguments);
		
		if(!count($arguments['files']))
		{
			die("[fatal] No files - use \"-f <file1> <file2> <fileN>\"\n");
		}
		
		// minifier process code
		$minifier = new CssMinifier;
		$typro = new \Typro\Minifier;
		
		// settings
		$baseDir = getcwd();
		
		if($baseDir === FALSE)
		{
			die("[fatal] getcwd failed\n");
		}
		
		$typroDir = $baseDir;
		
		if($arguments['options']['typroDir'] !== NULL)
		{
			$typroDir = realpath($arguments['options']['typroDir']);
		
			if($typroDir === FALSE)
			{
				die("[fatal] realpath failed\n");
			}
		}
		
		if($arguments['options']['filename'] === NULL)
		{
			$arguments['options']['filename'] = 'min' . date('YmdHis') . '.css';
		}
		
		$typro->setMinifier($minifier)
			->setOutputFilename($arguments['options']['filename'])
			->setDateTime($arguments['options']['addDateTime'])
			->setUseAllStables($arguments['options']['allStables'])
			->setUseAllIdeas($arguments['options']['allIdeas'])
			->setTyproDir($typroDir)
			->setBaseDir($baseDir);
		
		// add files
		$typro->addFiles($arguments['files']);
		
		try
		{
			$typro->minify();
			$typro->log();
			
			echo "\nDone.\n\n";
		}
		catch(Exception $e)
		{
			echo "[fatal] Minify error - {$e->getMessage()}";
		}
		
		exit;
	}
	
	
	function parseArguments($args)
	{
		$arguments = array(
			'options' => array(
				'filename' => NULL,
				'addDateTime' => TRUE,
				'allStables' => FALSE,
				'allIdeas' => FALSE,
				'typroDir' => NULL,
			),
			'files' => array(),
		);
		
		$state = ST_START;
		
		foreach($args as $arg)
		{
			switch($state)
			{
				case ST_START:
					if($arg == '-n')	// filename
					{
						$state = ST_FILENAME;
					}
					elseif($arg == '-p')	// path to Typro directory
					{
						$state = ST_PATH;
					}
					elseif($arg == '-f')	// list of files
					{
						$state = ST_FILES;
					}
					elseif($arg == '-t' || $arg == '-t1')
					{
						$arguments['options']['addDateTime'] = TRUE;
						$state = ST_START;
					}
					elseif($arg == '-t0')
					{
						$arguments['options']['addDateTime'] = FALSE;
						$state = ST_START;
					}
					else
					{
						throw new Exception("Unknown option '$arg'");
					}
					break;
					
				case ST_FILENAME:	// -n
					$arguments['options']['filename'] = $arg;
					$state = ST_START;
					break;
				
				case ST_PATH:	// -p
					$arguments['options']['typroDir'] = $arg;
					$state = ST_START;
					break;
				
				case ST_FILES: // -f
					$arguments['files'][] = $arg;
					break;
			}
		}
		
		return $arguments;
	}
?>

== CLI CSS Minifier (v2012-07-20-1)

require: PHP 5.3+, CssMinifier.php, TyproMinifier.php

usage:
	php -f minifier.php -- [options]

options:
	Optional:
	-n <name of the generated file>	['min' . date('YmdHis') . 'css']
	-p <path to Typro directory>	[current directory]
	-t add datetime info on begin of file (-t or -t1 = ON; -t0 = OFF) [ON]
	
	Required:
	-f <file 1> <file 2> <file 3> ...
	   * <CSS file> = path to a CSS file
	   * <Typro file> = @<module.name> (@default, @reset, @paragraph.indent,...)
	   * <Typro Idea file> = @idea.<module.name> (@idea.grid, @idea.classes, ...)
	   * @all = all stable and ideas modules
	   * @stable = all stable modules
	   * @ideas = all ideas modules

example:
	php -f minifier.php -- -n styles.css -t1 -f @reset @default @idea.grid

link: http://typro.iunas.cz/
author: Jan Pecha, <janpecha@email.cz>

