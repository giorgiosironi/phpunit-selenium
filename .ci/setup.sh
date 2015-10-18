#!/bin/bash

sudo apt-get update

if [ ! -f "/usr/local/bin/composer" ]; then
    echo "Installing Composer"
    php -r "readfile('https://getcomposer.org/installer');" | sudo php -d apc.enable_cli=0 -- --install-dir=/usr/local/bin --filename=composer
else
    echo "Updating Composer"
    sudo /usr/local/bin/composer self-update
fi

echo "Installing dependencies"
composer install

echo "Installing supervisord"
sudo apt-get install supervisor -y --no-install-recommends
sudo cp ./.ci/phpunit-environment.conf /etc/supervisor/conf.d/
sudo sed -i "s/^directory=.*webserver$/directory=${ESCAPED_BUILD_DIR}\\/selenium-1-tests/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^autostart=.*selenium$/autostart=true/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^autostart=.*python-webserver$/autostart=true/" /etc/supervisor/conf.d/phpunit-environment.conf

echo "Installing Firefox"
sudo apt-get install firefox -y --no-install-recommends

if [ ! -f "$SELENIUM_JAR" ]; then
    echo "Downloading Selenium"
    sudo mkdir -p $(dirname "$SELENIUM_JAR")
    sudo wget -nv -O "$SELENIUM_JAR" "$SELENIUM_DOWNLOAD_URL"
fi
