#!/bin/bash

echo "Installing dependencies"
composer install

echo "Installing supervisord"
sudo apt-get update
sudo apt-get install supervisor -y --no-install-recommends
sudo cp ./.ci/phpunit-environment.conf /etc/supervisor/conf.d/
sudo sed -i "s/^command=.*php-webserver/command=${ESCAPED_PHP_PATH}/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^directory=.*webserver$/directory=${ESCAPED_BUILD_DIR}\\/selenium-1-tests/" /etc/supervisor/conf.d/phpunit-environment.conf
sudo sed -i "s/^autostart=.*php-webserver$/autostart=true/" /etc/supervisor/conf.d/phpunit-environment.conf
