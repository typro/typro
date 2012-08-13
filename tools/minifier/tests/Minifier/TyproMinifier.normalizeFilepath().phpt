<?php
/**
 * Test: TyproMinifier->normalizeFilepath() tests
 *
 * @author     Jan Pecha
 */

require __DIR__ . '/bootstrap.php';

class MyMinifier extends \Typro\Minifier
{
	public function normalizeFilepath($file)
	{
		return parent::normalizeFilepath($file);
	}
}

$minifier = new MyMinifier;

Assert::throws(function() use ($minifier) {
	$minifier->normalizeFilepath('/no/existed/file');
}, 'Exception', 'Normalization failed - File not found - /no/existed/file');


