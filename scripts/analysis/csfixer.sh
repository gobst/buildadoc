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

if [[ $1 == 'dry-run' ]]; then
    #echo "Checking code style with: PHP Coding Standards Fixer (dry-run)"
    bin/php-cs-fixer fix -v --dry-run --config=cfg/dev/.php-cs-fixer.php > bin/output/phpcsfixer-report.log
    csfixer_status=$?
    if [[ $((csfixer_status)) == 0 ]]; then
        #echo -e "PHP Coding Standards Fixer result: Success!\n"
        exit 0
    else
        #echo -e "PHP Coding Standards Fixer result: Failed! See bin/output/phpcsfixer-report.log for details.\n"
        exit 1
    fi
fi

if [[ $1 == 'fix' ]]; then
    #echo "Fix code style with: PHP Coding Standards Fixer"
    bin/php-cs-fixer fix -v --config=cfg/dev/.php-cs-fixer.php > bin/output/phpcsfixer-report.log
    csfixer_status=$?
    if [[ $((csfixer_status)) == 0 ]]; then
      #echo -e "PHP Coding Standards Fixer result: Success!\n"
      exit 0
    else
      #echo -e "PHP Coding Standards Fixer result: Failed! See bin/output/phpcsfixer-report.log for details.\n"
      exit 1
    fi
fi

exit 1
