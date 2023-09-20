<?php

declare(strict_types=1);

return function (JP\CodeChecker\CheckerConfig $config) {
	$config->setPhpVersion(new JP\CodeChecker\Version('8.2.0'));
	$config->addPath('./');
	JP\CodeChecker\Sets\CzProjectMinimum::configure($config);
};
