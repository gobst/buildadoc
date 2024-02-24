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
if [ $? -eq 0 ]; then
    # Psalm result: Success!
    exit 0
else
    # Psalm result: Failed! See bin/output/psalm-report.log for details
    exit 1
fi
