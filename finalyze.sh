#!/usr/bin/env bash

vendor/bin/phpcbf $@ >> /dev/null

status=$?

echo "Thank you for using BEAR.Sunday."
echo "See README.md."
rm ./finalyze.sh

[ $status -eq 1 ] && exit 0 || exit $status
