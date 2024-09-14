#!/bin/bash

############################################################################
#
# This file is part of BuildADoc.
#
# (c) Guido Obst
#
# For the full copyright and license information, please view the LICENSE
# file that was distributed with this source code.
#
############################################################################

echo $1

# run unit tests with testdox
if [[ $1 == 'run-testdox' ]]; then
    bin/phpunit --configuration cfg/dev/phpunit.xml --testdox --color
fi

# run unit tests
if [[ $1 == 'run' ]]; then
    bin/phpunit --configuration cfg/dev/phpunit.xml
fi