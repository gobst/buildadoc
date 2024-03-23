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

#!/bin/bash

# PHP CodeSniffer
./scripts/analysis/codesniffer.sh
CODESNIFFER_EXIT=$?

if [ $CODESNIFFER_EXIT -eq 0 ]; then
    echo -e "PHP CodeSniffer result: Success!\n"
else
    echo -e "PHP CodeSniffer result: Failed! See bin/output/phpcs-report.log for details.\n"
fi

# PHP Compatibility
./scripts/analysis/compatibility.sh
COMPATIBILITY_EXIT=$?

if [ $COMPATIBILITY_EXIT -eq 0 ]; then
    echo -e "PHP Compatibility result: Success!\n"
else
    echo -e "PHP Compatibility result: Failed! See bin/output/php-compatibility.log for details.\n"
fi

# PHP Coding Standards Fixer
./scripts/analysis/csfixer.sh
CSFIXER_EXIT=$?

if [ $CSFIXER_EXIT -eq 0 ]; then
    echo -e "PHP Coding Standards Fixer  result: Success!\n"
else
    echo -e "PHP Coding Standards Fixer result: Failed! See bin/output/phpcsfixer-report.log for details.\n"
fi

# Run messdetector.sh
./scripts/analysis/messdetector.sh
MESSDETECTOR_EXIT=$?

# PHP Mess Detector
if [ $MESSDETECTOR_EXIT -eq 0 ]; then
    echo -e "PHP Mess Detector result: Success!\n"
else
    echo -e "PHP Mess Detector result: Failed! See bin/output/phpmd-report.log for details.\n"
fi

# PHP Psalm
./scripts/analysis/psalm.sh
PSALM_EXIT=$?

if [ $PSALM_EXIT -eq 0 ]; then
    echo -e "PHP Psalm result: Success!\n"
else
    echo -e "PHP Psalm result: Failed! See bin/output/psalm-report.log for details.\n"
fi


