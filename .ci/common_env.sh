#!/bin/bash

SELENIUM_HUB_URL='http://127.0.0.1:4444'
SELENIUM_JAR=/usr/share/selenium/selenium-server-standalone.jar
SELENIUM_DOWNLOAD_URL=https://selenium-release.storage.googleapis.com/3.4/selenium-server-standalone-3.4.0.jar
GECKODRIVER_DOWNLOAD_URL=https://github.com/mozilla/geckodriver/releases/download/v0.26.0/geckodriver-v0.26.0-linux64.tar.gz
GECKODRIVER_TAR=/tmp/geckodriver.tar.gz
PHP_VERSION=$(php -v)
