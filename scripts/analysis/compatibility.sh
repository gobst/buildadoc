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

# check files with PHP CodeSniffer and PHPCompatibility
echo "Checking code with: PHP CodeSniffer and PHPCompatibility"
echo "Checking PHP Version 8.3"
bin/phpcs -p src --standard=PSR2 --runtime-set testVersion 8.3 --report-full=bin/output/php-compatibility.log
phpcs_status=$?
if [[ $((phpcs_status)) == 0 ]]; then
    echo -e "PHP Compatibility result: Success!\n"
else
    echo -e "PHP Compatibility result: Failed! See bin/output/php-compatibility.log for details.\n"
fi

exit $((phpcs_status))