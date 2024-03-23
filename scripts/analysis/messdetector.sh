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

# Check files with PHP Mess Detector
bin/phpmd src text cfg/dev/phpmd.xml --suffixes php --reportfile bin/output/phpmd-report.log
phpmd_status=$?
if [[ $((phpmd_status)) == 0 ]]; then
    exit 0
else
    exit 1
fi
