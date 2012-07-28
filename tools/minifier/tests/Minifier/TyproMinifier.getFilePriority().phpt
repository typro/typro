<?php
/**
 * Test: TyproMinifier->getFilePriority() tests
 *
 * @author     Jan Pecha
 */

require __DIR__ . '/bootstrap.php';

class MyMinifier extends \Typro\Minifier
{
	public function getFilePriority($file)
	{
		return parent::getFilePriority($file);
	}
	
	
	public function _test_getPriority($key)
	{
		return self::$priority[$key];
	}
	
}

$minifier = new MyMinifier;

Assert::same($minifier->_test_getPriority('typro.reset.css'), $minifier->getFilePriority('/path/to/typro.reset.css'));
Assert::same($minifier->_test_getPriority('typro.default.css'), $minifier->getFilePriority('/path/to/typro.default.css'));
Assert::error(function() use ($minifier) {
	$minifier->_test_getPriority('not.existed.key');	
}, E_NOTICE);
Assert::same($minifier::PRIORITY_NORMAL, $minifier->getFilePriority('/path/to/typro.color.a.css'));
Assert::same($minifier::PRIORITY_IDEAS, $minifier->getFilePriority('/path/to/idea.typro.grid.css'));

