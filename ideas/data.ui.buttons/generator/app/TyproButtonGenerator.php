<?php
	/** Typro Button Generator
	 * 
	 * @author		Jan Pecha, <janpecha@email.cz>
	 * @version		2012-10-07-1
	 */
	
	namespace Typro\Generators;
	
	class ButtonGenerator extends BaseGenerator
	{
		protected function beforeGenerate()
		{
			$this->headerVars = array(
				'version' => date('Y-m-d') . '-1',
				'year' => date('Y'),
			);
			
			$this->iterVars = array();
			
			foreach($this->settings as $state => $vars)
			{
				$a = $this->prepareVars($vars);
				
				$a['stateName'] = $state;
				
				if($state === 'default')
				{
					$a['state'] = '';
					$a['state:hover'] = '';
					$a['state:active'] = '';
				}
				elseif($state === 'disabled')
				{
					$a['state'] = '.ui-disabled, .ui-button[disabled]';
					$a['state:hover'] = '.ui-disabled:hover, .ui-button[disabled]';
					$a['state:active'] = '.ui-disabled:active, .ui-button[disabled]';
				}
				else
				{
					$a['state'] = $state;
					$a['state:hover'] = $state;
					$a['state:active'] = $state;
				}
				
				$this->iterVars[] = $a;
			}
		}
		
		
		
		protected function prepareVars(array $variables)
		{
			$a = array();
			
			foreach($variables as $section => $vars)
			{
				if($section !== 'normal')
				{
					$section = ':' . $section;
				}
				else
				{
					$section = '';
				}
				
				foreach($vars as $name => $value)
				{
					$varName = $name . $section;
					
					$value = (string)$value;
					
					if(isset($value[0]) && $value[0] === '@')
					{
						$value[0] = '#';
					}
					
					$a[$varName] = $value;
				}
			}
			
			return $a;
		}
	}

