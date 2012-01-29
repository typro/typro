<?php
	/**
	 * .typro Compressor
	 * 
	 * @author		Jan Pecha,	<janpecha@email.cz>
	 * @copyright	Jan Pecha, 2011
	 * @license		http://typro.iunas.cz/license
	 * @link		http://typro.iunas.cz/
	 * @version		2.1.0
	 * @package		Compressor
	 */
	
	/**
	 * CSS Compressor
	 * 
	 * @link		https://github.com/nette/build-tools/blob/master/tasks/minifyJs.php
	 * @param		string css file
	 * @package		Compressor
	 */
	function css_Compressor($s)
	{
		$s = preg_replace('#/\*.*?\*/#s', '', $s); // remove comments
		$s = preg_replace('#\s+#', ' ', $s); // compress space
		$s = preg_replace('# ([^0-9a-z.\#*-])#i', '$1', $s);
		$s = preg_replace('#([^0-9a-z%)]) #i', '$1', $s);
		$s = str_replace(';}', '}', $s); // remove leading semicolon
//-		$s = str_replace(' ', "\n", $s); // better editing and buffering// blbost - generuje nevalidni CSS
		$s = trim($s);
		
		return $s;
	}
	
	$types = array(
		'#reset' => array(
			'file' => 'typro.reset.css',
			'desc' => 'CSS reset',
			'show' => true,
		),
		'#default' => array(
			'file' => 'typro.default.css',
			'desc' => 'default style',
			'show' => true,
		),
		'#font.verdana' => array(
			'file' => 'typro.font.verdana.css',
			'desc' => 'Verdana as default font',
			'show' => false,
		),
		'#paragraph.czech' => array(
			'file' => 'typro.paragraph.czech.css',
			'desc' => 'czech paragraph',
			'show' => true,
		),
		'#paragraph.indent' => array(
			'file' => 'typro.paragraph.indent.css',
			'desc' => 'paragraph indent',
			'show' => true,
		),
		'#paragraph.indent-no-first' => array(
			'file' => 'typro.paragraph.indent-no-first.css',
			'desc' => 'no indent after heading',
			'show' => true,
		),
		'#print' => array(
			'file' => 'typro.print.css',
			'desc' => 'print style',
			'show' => true,
		),
		'#visual' => array(
			'file' => 'typro.visual.css',
			'desc' => 'visual style',
			'show' => true,
		),
		'#layout' => array(
			'file' => 'typro.layout.css',
			'desc' => 'universal layout',
			'show' => true,
		),
	);
	
	if(isset($_POST['files']))
	{
		$files = str_replace("\r\n", "\n", $_POST['files']);
		$files = str_replace("\r", "\n", $files);
		
		$files = explode("\n", $files);
		
		$content_of_all = '';
		
		
		foreach($files as $file)
		{
			$file = trim($file);
			
			if(strlen($file) && $file[0] === "#")
			{
				if(isset($types[$file]['file']))
				{
					$file = __DIR__ . '/' . $types[$file]['file'];
				}
				else
				{
					continue;
				}
#				switch($file)
#				{
#					case '#reset':
#						$file = __DIR__ . '/typro.reset.css';
#						break;
#					
#					case '#default':
#						$file = __DIR__ . '/typro.all.css';
#						break;
#					
#					case '#czech':
#						$file = __DIR__ . '/typro.czech3.css';
#						break;
#					
#					case '#visual':
#						$file = __DIR__ . '/typro.visual.css';
#						break;
#					
#					default:
#						continue;
#				}
			}
#			else
			{
				$type = false;
				
				if($pos = strpos($file, '@'))
				{
					$type = trim(substr($file, 0, $pos));
					$file = trim(substr($file, $pos + 1));
				}
				
				$content = @file_get_contents($file);
				
				if($content !== false)
				{
					if($type !== false)
					{
						$content = "@media $type { $content }";
					}
					
					$content_of_all .= "\n\n$content";
				}
				else
				{
					echo '<p style="color:red;">'.htmlspecialchars($file).'</p>'."\n";
				}
			}
		}
		
		//echo "<pre>".htmlspecialchars($content_of_all)."</pre><hr>";
		
		if($content_of_all !== '')
		{
			file_put_contents(__DIR__ . '/' . date('his_dmy') . '.css', css_Compressor($content_of_all));
		}
	}
#	else
#	{
#		echo 'ni';
#	}
/*	<link rel="stylesheet" href="{$basePath}/css/screen.css" type="text/css" media="screen,projection,tv" />
	<link rel="stylesheet" href="{$basePath}/css/print.css" type="text/css" media="print" />
	<link rel="stylesheet" href="{$basePath}/css/lightbox.css" type="text/css" media="screen" />
*/	

?>
<h1>CSS Compressor</h1>
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
	<p><textarea name="files" cols="60" rows="20"><?php

	 	if(isset($_POST['files']))
	 	{
	 		echo htmlspecialchars($_POST['files']);
	 	}
	 	else
	 	{
#	 		echo "#reset\n#default\n#czech\n#indent\n#indent-no-first\n#visual\n";
			foreach($types as $key => $type)
			{
				if(isset($type['show']) && $type['show'] == true)
				{
					echo htmlspecialchars("$key\n");
				}
			}
	 	}
	 
	?></textarea></p>
	<p>
		<small>
			<?php
				foreach($types as $key => $type)
				{
					$file = '';
					
					if(isset($type['file']))
					{
						$file = $type['file'];
					}
					
					$desc = '';
					
					if(isset($type['desc']))
					{
						$desc = ' - ' . $type['desc'];
					}
					
					echo '<code'.(($file != '') ? ' title="'.htmlspecialchars($file).'"' : '').'>' . htmlspecialchars($key) . '</code>' . htmlspecialchars($desc) . "<br>\n";
				}
#			#reset - typro.reset.css<br>
#			#all - typro.all.css<br>
#			#czech3 - typro.czech3.css<br>
#			#visual - typro.visual.css<br>
			?>

		</small>
	</p>
	<p><input type="submit" name="send" value="Komprimovat" /></p>
</form>

<hr>

<p><small>Jan Pecha &copy; 2011-<?php echo date('Y'); ?></small></p>
