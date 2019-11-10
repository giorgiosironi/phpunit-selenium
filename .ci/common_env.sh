#!/bin/bash

TESTS_URL='http://127.0.0.1:8080'
PHP_PATH=$(which php)
PHP_VERSION=$(${PHP_PATH} -v)
ESCAPED_PHP_PATH=$(echo "$PHP_PATH" | sed 's/\//\\\//g')
