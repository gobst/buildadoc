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

git fetch all

# Run mutation tests
bin/infection --threads=4 --configuration=cfg/dev/infection.json5 --skip-initial-tests --min-msi=95 --git-diff-base=develop --git-diff-lines --ignore-msi-with-no-mutations --coverage=../../bin/output/unit