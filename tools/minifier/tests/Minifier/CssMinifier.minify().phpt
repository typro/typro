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

Assert::same('body{content:"ahoj";backgroound:#11e}', $minifier->minify('	body {	content:  "ahoj"; backgroound: #11e;}	'));
Assert::same('body{content:"ahoj jak se vede?";backgroound:#11e}', $minifier->minify('	body {	content:  "ahoj jak se vede?"; backgroound: #11e;}	'));
