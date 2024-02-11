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

# check files with PHP PSALM
echo "Checking code with: PHP PSALM"
bin/psalm --config=cfg/dev/psalm.xml --output-format=text > bin/output/psalm-report.log
psalm_status=$?
if [[ $((psalm_status)) == 0 ]]; then
    echo "PHP Psalm result: Success!"
    exit 0
else
    echo "PHP Psalm result: Failed! See bin/output/psalm-report.log for details."
    exit 1
fi
