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

# Check files with PHP CodeSniffer
bin/phpcs --runtime-set ignore_warnings_on_exit 1 --standard=cfg/dev/phpcs.xml --extensions=php -s -p --report=code --report-width=120 src > bin/output/phpcs-report.log
phpcs_status=$?
if [[ $phpcs_status == 0 ]]; then
    exit 0
else
    exit 1
fi
