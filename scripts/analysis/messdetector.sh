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

# check files with PHP Mess Detector
echo "Checking code with: PHP Mess Detector"
bin/phpmd src text cfg/dev/phpmd.xml --suffixes php --reportfile bin/output/phpmd-report.log
phpmd_status=$?
if [[ $((phpmd_status)) == 0 ]]; then
    echo -e "PHP Mess Detector result: Success!\n"
else
    echo -e "PHP Mess Detector result: Failed! See bin/output/phpmd-report.log for details.\n"
fi

exit $((phpmd_status))