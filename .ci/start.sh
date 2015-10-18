#!/bin/bash

killall -9 java
killall -9 Xvfb
sudo supervisorctl reload

wget --retry-connrefused --tries=120 --waitretry=3 --output-file=/dev/null "$SELENIUM_HUB_URL/wd/hub/status" -O /dev/null
if [ ! $? -eq 0 ]; then
    echo "Selenium Server not started"
else
    echo "Finished setup"
fi
