<?php
	/** CssStupidCleaner
	 *
	 * Removes duplicate properties in stylesheet.
	 * 
	 * rule @media not supported yet
	 * 
	 * Requires format:
	 *  selector {
	 *      property: value;
	 *      property: value;
	 *  }
	 *
	 * or
	 * 
	 *  selector, selector,
	 *  selector {
	 *      property: value;
	 *      property: value;
	 *  }
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @license		http://typro.iunas.cz/license/
	 * @version		2012.01.29-1
	 */
	
	namespace Typro;
	
	class CssStupidCleaner
	{
		const STATE_SELECTOR = 0,
			STATE_RULESET = 1;
		
		/** @var array */
		protected $files = array();
		
		/** @var bool */
		public $overwrite = false;
		
		
		/**
		 * @param	string
		 * @return	void
		 */
		public function addFile($filepath)
		{
			$this->files[] = $filepath;
		}
		
		
		/**
		 * @param	array
		 * @return	void
		 */
		public function addFiles(array $filepaths)
		{
			foreach($filepaths as $file)
			{
				$this->addFile($file);
			}
		}
		
		
		/**
		 * @return	void
		 * @throws	\Exception	file error
		 */
		public function run()
		{
			foreach($this->files as $file)
			{
				$content = file_get_contents($file);
				
				if($content === FALSE)
				{
					throw new \Exception('[fatal] ' . $file);
				}
				
				$content = $this->normalize($content);
				$content = explode("\n", $content);
				
				$document = self::createDocument();
				
				$state = self::STATE_SELECTOR;
				$selectorBuffer = '';
				
				foreach($content as $line)
				{
					$line = trim($line);
					
					switch($state)
					{
						case self::STATE_SELECTOR:
							if($this->endsWith($line, '{'))
							{
								$state = self::STATE_RULESET;
							}
							
							$selectorBuffer .= $line . "\n";
							break;
						
						case self::STATE_RULESET:
							if($line == '}')
							{
								$state = self::STATE_SELECTOR;
								$selectorBuffer = '';
							}
							else
							{
								$property = $this->parseProperty($line);
								
								if($property !== FALSE)
								{
									$document[trim($selectorBuffer)][$property['name']] = $property['value'];
								}
							}
							break;
					}
				}
				
				$content = self::generateDocument($document);
				
				$fileName = $file;
				
				if(!$this->overwrite)
				{
					$fileName .= '-modified-' . date('YmdHis') . '.css';
				}
				
				file_put_contents($fileName, $content);
			}
		}
		
		
		/**
		 * @param	string
		 * @return	array  [[name => name], [value => value]]
		 */
		protected function parseProperty($line)
		{
			$name = '';
			$value = '';
			
			if(($pos = strpos($line, ':')) !== FALSE)
			{
				// preskakujeme dvojtecku
				$name = trim(substr($line, 0, $pos));
				$value = trim(substr($line, $pos + 1));
				
				return array(
					'name' => $name,
					'value' => $value,
				);
			}
			
			return FALSE;
		}
		
		
		/**
		 * @return	array
		 */
		protected static function createDocument()
		{
			return array();
		}
		
		
		/**
		 * @param	array  Document
		 * @return	string|FALSE
		 */
		protected static function generateDocument($document)
		{
			if(is_array($document))
			{
				$doc = '';
				
				foreach($document as $selector => $property)
				{
					$doc .= $selector . "\n";
					
					foreach($property as $propertyName => $value)
					{
						$doc .= "\t{$propertyName}: {$value}\n";
					}
					
					$doc .= "}\n\n\n";
				}
				
				return $doc;
			}
			
			return FALSE;
		}
		
		
		/**
		 * @link	http://api.nette.org/2.0/source-Utils.Strings.php.html#134
		 * @param	string  UTF-8 encoding or 8-bit
		 * @return	string
		 */
		protected function normalize($s)
		{
			// standardize line endings to unix-like
			$s = str_replace("\r\n", "\n", $s); // DOS
			$s = strtr($s, "\r", "\n"); // Mac
			
			// remove control characters; leave \t + \n
			$s = preg_replace('#[\x00-\x08\x0B-\x1F\x7F]+#', '', $s);
			
			// right trim
			$s = preg_replace("#[\t ]+$#m", '', $s);
			
			// leading and trailing blank lines
			$s = trim($s, "\n");
			
			return $s;
		}
		
		
		/**
		 * Ends the $haystack string with the suffix $needle?
		 * @link	http://api.nette.org/2.0/source-Utils.Strings.php.html#91
		 * @param	string
		 * @param	string
		 * @return	bool
		 */
		public static function endsWith($haystack, $needle)
		{
			return strlen($needle) === 0 || substr($haystack, -strlen($needle)) === $needle;
		}
	}
