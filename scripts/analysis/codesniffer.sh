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

# check files with PHP CodeSniffer
echo "Checking code with: PHP CodeSniffer"
bin/phpcs --runtime-set ignore_warnings_on_exit 1 --standard=cfg/dev/phpcs.xml --extensions=php -s -p --report=code --report-width=120 src > bin/output/phpcs-report.log
phpcs_status=$?
if [[ $phpcs_status == 0 ]]; then
    echo -e "PHP CodeSniffer result: Success!\n"
else
    echo -e "PHP CodeSniffer result: Failed! See bin/output/phpcs-report.log for details.\n"
fi

exit $((phpcs_status))
