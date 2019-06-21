#!/bin/bash

sudo killall supervisord
sudo /etc/init.d/supervisor start

wget --retry-connrefused --tries=60 --waitretry=1 --output-file=/dev/null "$TESTS_URL/confirm.html" -O /dev/null
if [ ! $? -eq 0 ]; then
    echo "PHP Webserver not started"
    exit 1
fi

echo "Finished setup"
