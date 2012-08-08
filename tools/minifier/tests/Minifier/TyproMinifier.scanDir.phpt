<?php
/**
 * Test: TyproMinifier->scanDir() tests
 *
 * @author     Jan Pecha
 */

require __DIR__ . '/bootstrap.php';

class MyMinifier extends \Typro\Minifier
{
	public function scanDir($pattern, $exclude = NULL)
	{
		return parent::scanDir($pattern, $exclude);
	}
}

$minifier = new MyMinifier;

$dir = __DIR__ . '/TyproMinifier.scanDir';
$files = $minifier->scanDir($dir . '/module.*.css');
Assert::same(count($files), 4);
Assert::same($files[0], $dir . '/module.1.css');
Assert::same($files[1], $dir . '/module.2.css');
Assert::same($files[2], $dir . '/module.3.css');
Assert::same($files[3], $dir . '/module.4.css');


$files = $minifier->scanDir($dir . '/module.*.css', array(
	'module.1.css',
	'module.3.css',
	'module.4.css',
));

Assert::same(count($files), 1);
Assert::same(reset($files), $dir . '/module.2.css');
