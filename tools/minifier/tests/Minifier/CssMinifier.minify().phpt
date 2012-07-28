<?php
/**
 * Test: CssMinifier->minify() tests
 *
 * @author     Jan Pecha
 */

require __DIR__ . '/bootstrap.php';

$minifier = new CssMinifier;

Assert::same('body{}', $minifier->minify('	body {   }	'));
Assert::same("@media\nscreen\nand\n(max-width:10px){color:red}", $minifier->minify('	@media screen  and  ( max-width : 10px ) {	color : red	;	}	'));
