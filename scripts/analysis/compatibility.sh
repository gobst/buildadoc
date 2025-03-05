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

# Check files with PHP CodeSniffer and PHPCompatibility
echo "Checking code with: PHP CodeSniffer and PHPCompatibility"
echo "Checking PHP Version 8.4"
bin/phpcs -p src --standard=PHPCompatibility --runtime-set testVersion 8.4 --report-full=bin/output/php-compatibility.log
phpcs_status=$?
exit $((phpcs_status))
