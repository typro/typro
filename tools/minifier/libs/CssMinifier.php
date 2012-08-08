<?php
	/** Css Minifier - simple CSS minifier
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-08-08-1
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
			
			$state = 'normal';
			$stringChar = '';
			$len = strlen($s);
			for($i = 0; $i < $len; $i++)
			{
				if($state === 'normal')
				{
					if($s[$i] === '\'' || $s[$i] === '"')
					{
						$state = 'string';
						$stringChar = $s[$i];
					}
					elseif($s[$i] === ' ')
					{
						$s[$i] = "\n";
					}
				}
				elseif($state === 'string')
				{
					if($s[$i] === $stringChar)
					{
						$state = 'normal';
						$stringChar = '';
					}
				}
			}
			
			
			
			return $s;
		}
	}
	
