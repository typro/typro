<?php
/**
 * Test: TyproMinifier->prepareTyproFiles() tests
 *
 * @author     Jan Pecha
 */

require __DIR__ . '/bootstrap.php';

class MyMinifier extends \Typro\Minifier
{
	public function prepareTyproFiles()
	{
		return parent::prepareTyproFiles();
	}
	
	
	public function normalizeTyproFilepath($file)
	{
		if(self::startsWith($file, '@idea.'))	// <typroDir>/idea/idea.typro.<module>.css
		{
			$file = 'ideas/idea.typro.' . substr($file, 6);
		}
		else	// <typroDir>/typro.<module>.css
		{
			$file = 'typro.' . substr($file, 1);
		}
		
		return '/path/' . $file . '.css';
	}
}

$minifier = new MyMinifier;

$minifier->addFiles(array(
	'@visual', '@default', '@idea.grid', '@reset',
));

$resultFiles = array(
	'/path/typro.reset.css',
	'/path/typro.default.css',
	'/path/typro.visual.css',
	'/path/ideas/idea.typro.grid.css',
);

$prepareFiles = $minifier->prepareTyproFiles();

Assert::same($prepareFiles[0], $resultFiles[0]);
Assert::same($prepareFiles[1], $resultFiles[1]);
Assert::same($prepareFiles[2], $resultFiles[2]);
Assert::same($prepareFiles[3], $resultFiles[3]);


