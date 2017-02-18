#!/bin/sh

sed -i "/mirror:\\/\\//d" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt xenial main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt xenial-updates main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt xenial-backports main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt xenial-security main restricted universe multiverse" /etc/apt/sources.list

apt-get update

apt-get install software-properties-common -y
apt-get install python-software-properties -y

# installing xvfb, java and php
apt-get install xvfb php-cli php-curl php-xml php-mbstring php-xdebug ncurses-term unzip xfonts-100dpi xfonts-75dpi xfonts-scalable xfonts-cyrillic vim -y --no-install-recommends

