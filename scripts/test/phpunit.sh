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
if [[ $1 == 'testdox' ]]; then
    bin/phpunit --testdox --color
fi

# run unit tests without testdox
if [[ $1 == 'run' ]]; then
    bin/phpunit --color
fi

if [[ $1 == 'coverage' ]]; then
    bin/phpunit --coverage-text --coverage-html bin/output/unit/coverage/ --coverage-xml bin/output/unit/coverage-xml  --log-junit bin/output/unit/junit.xml > bin/output/phpunit.log
fi