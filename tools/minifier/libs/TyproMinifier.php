<?php
	/** Typro Minifier
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-10-09-1
	 */
	
	namespace Typro;
	
	interface IMinifier
	{
		public function minify($input);
	}
	
	
	class Minifier
	{
		const PRIORITY_NORMAL = 10,
			PRIORITY_IDEAS = 5;
		
		/** @var  \Typro\IMinifier */
		protected $minifier;
		
		/** @var  NULL|string */
		protected $outputFilename = NULL;
		
		/** @var  NULL|string */
		protected $typroDir = NULL;
		
		/** @var  NULL|string */
		protected $baseDir = NULL;
		
		/** @var  array */
		protected $options = array(
			'addDateTime' => TRUE,
			'allStables' => FALSE,
			'allIdeas' => FALSE,
		);
		
		/** @var  array */
		protected $typroFiles = array();
		
		/** @var  array */
		protected $files = array();
		
		/** @var  array */
		protected static $priority = array(
			'typro.reset.css' => 1000,
			'typro.default.css' => 999,
		);
		
		/** @var  array */
		private $messages = array();
		
		
		
		/**
		 * @param	IMinifier
		 * @return	$this	provides fluent interface
		 */
		public function setMinifier(IMinifier $minifier)
		{
			$this->minifier = $minifier;
			return $this;
		}
		
		
		
		/**
		 * @return	TRUE
		 */
		public function minify()
		{
			if($this->minifier === NULL)
			{
				throw new \Exception('No minifier');
			}
			
			$input = '';
			$this->messages = array();
			
			$files = array_merge($this->prepareTyproFiles(), $this->prepareFiles());
			$files = $this->removeDuplicateFiles($files);
			
			foreach($files as $file)
			{
				$content = file_get_contents($file);
				
				if($content === FALSE)
				{
					throw new \Exception("Cannot read file '$file'");
				}
				
				$input .= $content;
			}
			
			$output = $this->generateOutputHeader() . $this->minifier->minify($input);
			file_put_contents($this->baseDir . '/' . $this->outputFilename, $output);
			
			return TRUE;
		}
		
		
		
		/**
		 * @return	string
		 */
		protected function generateOutputHeader()
		{
			$header = '';
			
			if($this->options['addDateTime'])
			{
				$header .= '/* ' . date('YmdHis') . ' */';
			}
			
			return $header;
		}
		
		
		
		/**
		 * @return	array
		 */
		protected function prepareTyproFiles()
		{
			$files = array();
			
			// ziskani souborovych cest
			foreach($this->typroFiles as $file)
			{
				$files[] = $this->normalizeTyproFilepath($file);
			}
			
			// sken stabilnich modulu
			if($this->options['allStables'])
			{
				$_files = $this->scanDir($this->typroDir . '/typro.*.css', array(
					'typro.paragraph.czech.css',
					'typro.font.verdana.css',
				));
				
				$files = array_merge($files, $_files);
			}
			
			// sken idea modulu
			if($this->options['allIdeas'])
			{
				$files = array_merge($files, $this->scanDir($this->typroDir . '/ideas/idea.typro.*.css', array(
					'idea.typro.grid.responsive.reorder.css',
					'idea.typro.headings.css',
					'idea.typro.headings.margin.css',
					'idea.typro.ui.buttons.minimal.css',
				)));
			}
			
			// odstraneni duplicitnich souboru z pole
			$files = $this->removeDuplicateFiles($files);
			
			// serazeni souboru podle priority
			usort($files, array($this, 'compareFilesByPriority'));
			
			return $files;
		}
		
		
		
		/**
		 * @param	string	first file
		 * @param	string	second file
		 * @return	int		-1, 0, 1
		 */
		protected function compareFilesByPriority($first, $second)
		{
			$priorityFirst = $this->getFilePriority($first);
			$prioritySecond = $this->getFilePriority($second);
			
			if($priorityFirst == $prioritySecond)
			{
				return 0;
			}
			
			if($priorityFirst > $prioritySecond)
			{
				return -1;
			}
			
			return 1;
		}
		
		
		
		/**
		 * @param	string	filepath
		 * @return	int
		 */
		protected function getFilePriority($file)
		{
			$name = basename($file);
			
			if(isset(self::$priority[$name]))
			{
				return self::$priority[$name];
			}
			elseif(self::startsWith($name, 'idea.'))
			{
				return self::PRIORITY_IDEAS;
			}
			
			return self::PRIORITY_NORMAL;
		}
		
		
		
		/**
		 * @param	string
		 * @param	string[]|NULL
		 * @return	array
		 */
		protected function scanDir($pattern, $exclude = NULL)
		{
			$files = glob($pattern);
			
			if($files === FALSE)
			{
				throw new \Exception('Scan dir error');
			}
			
			if(is_array($exclude))
			{
				$exclude = array_flip($exclude);
				
				$files = array_filter($files, function($item) use ($exclude) {
					$basename = basename($item);
					
					if(isset($exclude[$basename]))
					{
						return FALSE;
					}
					
					return TRUE;
				});
			}
			
			return $files;
		}
		
		
		
		/**
		 * @return	array
		 */
		protected function prepareFiles()
		{
			$files = array();
			
			foreach($this->files as $file)
			{
				$files[] = $this->normalizeFilepath($file);
			}
			
			// remove duplicates
			//$files = $this->removeDuplicateFiles($files);
			return $files;
		}
		
		
		
		/**
		 * @param	string
		 * @return	string
		 */
		protected function normalizeFilepath($filepath)
		{
			if($filepath[0] !== '/')
			{
				$filepath = $this->baseDir . '/' . $filepath;
			}
			
			$filePath = $filepath;
			$filepath = realpath($filepath);
			
			if($filepath === FALSE)
			{
				throw new \Exception('Normalization failed - File not found - ' . $filePath);
			}
			
			return $filepath;
		}
		
		
		/**
		 * @param	string
		 * @return	string
		 */
		protected function normalizeTyproFilepath($file)
		{
			if(self::startsWith($file, '@idea.'))	// <typroDir>/idea/idea.typro.<module>.css
			{
				$file = 'ideas/idea.typro.' . substr($file, 6);
			}
			else	// <typroDir>/typro.<module>.css
			{
				$file = 'typro.' . substr($file, 1);
			}
			
			$filePath = $file = $this->typroDir . '/' . $file . '.css';
			
			$file = realpath($file);
			
			if($file === FALSE)
			{
				throw new \Exception('Normalization failed [Typro files] - File not found - ' . $filePath);
			}
			
			return $file;
		}
		
		
		
		/**
		 * @param	string
		 * @return	$this	provides fluent interface
		 */
		public function setTyproDir($typroDir)
		{
			$this->typroDir = (string)$typroDir;
			return $this;
		}
		
		
		
		/**
		 * @param	string
		 * @return	$this	provides fluent interface
		 */
		public function setBaseDir($dir)
		{
			$this->baseDir = (string)$dir;
			return $this;
		}
		
		
		
		/**
		 * @param	string
		 * @return	$this	provides fluent interface
		 */
		public function setOutputFilename($filename)
		{
			$this->outputFilename = (string)$filename;
			return $this;
		}
		
		
		
		/**
		 * @param	bool
		 * @return	$this	provides fluent interface
		 */
		public function setDateTime($addDateTime)
		{
			$this->options['addDateTime'] = (bool)$addDateTime;
			return $this;
		}
		
		
		
		/**
		 * @param	bool
		 * @return	$this	provides fluent interface
		 */
		public function setUseAllStables($addAllStables)
		{
			$this->options['allStables'] = (bool)$addAllStables;
			return $this;
		}
		
		
		
		/**
		 * @param	bool
		 * @return	$this	provides fluent interface
		 */
		public function setUseAllIdeas($addAllIdeas)
		{
			$this->options['allIdeas'] = (bool)$addAllIdeas;
			return $this;
		}
		
		
		
		/**
		 * @param	array
		 * @return	$this	provides fluent interface
		 */
		public function addFiles($files)
		{
			foreach($files as $file)
			{
				$this->addFile($file);
			}
			
			return $this;
		}
		
		
		
		/**
		 * @param	string	filename
		 * @return	$this	provides fluent interface
		 */
		public function addFile($filename)
		{
			if($filename[0] === '@') // Typro modules
			{
				if($filename === '@all')
				{
					$this->options['allStables'] = TRUE;
					$this->options['allIdeas'] = TRUE;
				}
				elseif($filename === '@stable' || $filename === '@stables')
				{
					$this->options['allStables'] = TRUE;
				}
				elseif($filename === '@ideas')
				{
					$this->options['allIdeas'] = TRUE;
				}
				else
				{
					$this->typroFiles[] = $filename;
				}
			}
			else
			{
				$this->files[] = $filename;
			}
		}
		
		
		
		/**
		 * @param	array
		 * @return	array
		 */
		public function removeDuplicateFiles(array $files)
		{
			$cache = array();
			$i = 0;
			
			foreach($files as $file)
			{
				if(isset($cache[$file]))
				{
					$this->messages[] = "[warn] Duplicate file '$file'";
				}
				else
				{
					$cache[$file] = $i;
					$i++;
				}
			}
			
			return array_flip($cache);
		}
		
		
		
		/**
		 * @return	void
		 */
		public function log()
		{
			echo "\n";
			
			foreach($this->messages as $messages)
			{
				echo "$messages\n";
			}
		}
		
		
		
		/**
		 * Starts the $haystack string with the prefix $needle?
		 * @link	http://api.nette.org/2.0/source-Utils.Strings.php.html#78
		 * @param	string
		 * @param	string
		 * @return	bool
		 */
		public static function startsWith($haystack, $needle)
		{
			return strncmp($haystack, $needle, strlen($needle)) === 0;
		}
	}

