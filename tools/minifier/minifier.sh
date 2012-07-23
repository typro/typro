#!/bin/sh

# saves the path to this script's directory
dir=` dirname $0 `

# absolutizes the path if necessary
if echo $dir | grep -v ^/ > /dev/null; then
	dir=` pwd `/$dir
fi

# runs minifier.php with script's arguments
php -f "$dir/minifier.php" -- $*
