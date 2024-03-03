#!/bin/bash

# Extract values from summary.log file
TOTAL=$(grep -E 'Total' bin/output/mutation/summary.log | grep -Eo '[0-9]+')
KILLED=$(grep -E 'Killed' bin/output/mutation/summary.log | grep -Eo '[0-9]+')

# Calculate the MSI
MSI=$(echo "scale=4; $KILLED / $TOTAL * 100" | bc)
MSI=$(printf "%.2f\n" $MSI)

# Generate badge link
BADGE="[![Mutation Score](https://img.shields.io/badge/MSI-${MSI}%25-brightgreen)](https://img.shields.io/badge/MSI-${MSI}%25-brightgreen)"
echo ${BADGE}

# Update README file
if [ ! -s README.md ]; then
  echo "$BADGE" >> README.md
else
  sed -i "s|\[!\[Mutation Score\].*](.*)|$BADGE|g" README.md || echo "$BADGE" >> README.md
fi