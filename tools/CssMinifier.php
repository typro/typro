<?php
	/** CssMinifier
	 *
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @license		http://typro.iunas.cz/license/
	 * @version		2012.01.29-1
	 */
	
	namespace Typro;
	
	class CssMinifier
	{
		/** @var array */
		protected $files = array();
		
		/** @var string|NULL|FALSE */
		public $filename = null;
		
		
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
		public function generate($addDate = false)
		{
			$content = '';
			$header = '';
			$date = date('YmdHis');
			
			if($addDate)
			{
				$header .= '/* ' . $date . ' */';
			}
			
			// TODO: PATH
			if(!is_string($this->filename))
			{
				$this->filename = 'min' . $date . '.css';
			}
			
			foreach($this->files as $file)
			{
				$_c = file_get_contents($file);
				
				if($_c === false)
				{
					throw new \Exception('[error] ' . $file);
				}
				
				$content .= $_c;
			}
			
			file_put_contents($this->filename, $header . $this->compress($content));
		}
		
		
		/**
		 * @link	https://github.com/nette/build-tools/blob/master/tasks/minifyJs.php#L50
		 * @param	string
		 * @return	string
		 */
		protected function compress($content)
		{
			$s = preg_replace('#/\*.*?\*/#s', '', $content); // remove comments
			$s = preg_replace('#\s+#', ' ', $s); // compress space
			$s = preg_replace('# ([^0-9a-z.\#*-])#i', '$1', $s);
			$s = preg_replace('#([^0-9a-z%)]) #i', '$1', $s);
			$s = str_replace(';}', '}', $s); // remove leading semicolon
			$s = trim($s);
			
			return $s;
		}
	}
