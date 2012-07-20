<?php
	/** Css Minifier - simple CSS minifier
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-07-20-1
	 */
	
	class CssMinifier implements \Typro\IMinifier
	{
		/**
		 * @link	https://github.com/nette/build-tools/blob/master/tasks/minifyJs.php#L50
		 * @param	string
		 * @return	string
		 */
		public function minify($s)
		{
			$s = preg_replace('#/\*.*?\*/#s', '', $s); // remove comments
			$s = preg_replace('#\s+#', ' ', $s); // compress space
			$s = preg_replace('# ([^(0-9a-z.\#*-])#i', '$1', $s);
			$s = preg_replace('#([^0-9a-z%)]) #i', '$1', $s);
			$s = str_replace(';}', '}', $s); // remove leading semicolon
			$s = trim($s);
			
			return $s;
		}
	}
	
