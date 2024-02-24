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

# Check files with PHP Psalm
bin/psalm --config=./cfg/dev/psalm.xml --output-format=text > bin/output/psalm-report.log
status=$?
if [[ $((status)) == 0 ]]; then
    exit 0
else
    cd bin/output
    ls -l
    cat psalm-report.log
    exit 1
fi
