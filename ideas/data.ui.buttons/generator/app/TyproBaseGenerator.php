<?php
	/** Typro Button Generator
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-10-07-1
	 */
	
	namespace Typro\Generators;
	
	abstract class BaseGenerator
	{
		/** @var  array */
		protected $iterVars = array();
		
		/** @var  array */
		protected $headerVars = array();
		
		/** @var  array */
		protected $settings;
		
		/** @var  string */
		protected $header;
		
		/** @var  string */
		protected $part;
		
		
		
		public function __construct($header, $part, array $settings)
		{
			$this->header = (string)$header;
			$this->part = (string)$part;
			$this->settings = $settings;
		}
		
		
		
		protected function beforeGenerate()
		{
		}
		
		
		
		public function generate()
		{
			$this->beforeGenerate();
			
			$output = $this->expandVars($this->header, $this->headerVars);
			
			foreach($this->iterVars as $i => $vars)
			{
				$output .= "\n" . $this->expandVars($this->part, $vars);
			}
			
			return $output;
		}
		
		
		
		protected function expandVars($source, array $vars)
		{
			$vars = $this->formatVars($vars);
			
			return strtr($source, $vars);
		}
		
		
		
		protected function formatVariableName($name)
		{
			return '{$' . $name . '}';
		}
		
		
		
		protected function formatVars(array $vars)
		{
			$a = array();
			
			foreach($vars as $name => $value)
			{
				$a[$this->formatVariableName($name)] = $value;
			}
			
			return $a;
		}
	}

