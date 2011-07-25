<?php
	
	function css_Compressor($s)
	{
	// CSS compressor
	// https://github.com/nette/build-tools/blob/master/tasks/minifyJs.php
		$s = preg_replace('#/\*.*?\*/#s', '', $s); // remove comments
		$s = preg_replace('#\s+#', ' ', $s); // compress space
		$s = preg_replace('# ([^0-9a-z.\#*-])#i', '$1', $s);
		$s = preg_replace('#([^0-9a-z%)]) #i', '$1', $s);
		$s = str_replace(';}', '}', $s); // remove leading semicolon
		$s = trim($s);
		
		return $s;
	}
	
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
				switch($file)
				{
					case '#reset':
						$file = __DIR__ . '/typro.reset.css';
						break;
					
					case '#all':
						$file = __DIR__ . '/typro.all.css';
						break;
					
					case '#czech3':
						$file = __DIR__ . '/typro.czech3.css';
						break;
					
					case '#visual':
						$file = __DIR__ . '/typro.visual.css';
						break;
					
					default:
						continue;
				}
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
			file_put_contents(__DIR__ . '/' . date('hisdmy') . '.css', css_Compressor($content_of_all));
		}
	}
	else
	{
		echo 'ni';
	}
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
	 		echo "#reset\n#all\n#czech3\n#visual\n";
	 	}
	 
	?></textarea></p>
	<p>
		<small>
			#reset - typro.reset.css<br>
			#all - typro.all.css<br>
			#czech3 - typro.czech3.css<br>
			#visual - typro.visual.css<br>
		</small>
	</p>
	<p><input type="submit" name="send" value="Komprimovat" /></p>
</form>

<hr>

<p><small>Jan Pecha &copy; 2011-<?php echo date('Y'); ?></small></p>
