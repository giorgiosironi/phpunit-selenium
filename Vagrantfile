VAGRANTFILE_API_VERSION = "2"

$setupEnvironment = <<-SCRIPT

sed -i "/mirror:\\/\\//d" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt precise main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt precise-updates main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt precise-backports main restricted universe multiverse" /etc/apt/sources.list
sed -i "1ideb mirror://mirrors.ubuntu.com/mirrors.txt precise-security main restricted universe multiverse" /etc/apt/sources.list

apt-get update

# firefox and xvfb
apt-get install firefox xvfb -y --no-install-recommends

# java
apt-get install openjdk-7-jre-headless -y --no-install-recommends

# selenium
mkdir -p /usr/share/selenium
if [ ! -f "/usr/share/selenium/selenium-server-standalone.jar" ]; then echo "Downloading selenium (~35Mb) ..."; wget -nv -O /usr/share/selenium/selenium-server-standalone.jar http://selenium-release.storage.googleapis.com/2.41/selenium-server-standalone-2.41.0.jar; fi

# php
apt-get install php5-cli php5-curl php5-xdebug -y --no-install-recommends
if [ ! -f "/usr/local/bin/composer" ]; then php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer; fi

# supervisor
apt-get install supervisor -y --no-install-recommends
cp /vagrant/.vagrant/phpunit-environment.conf /etc/supervisor/conf.d/
service supervisor stop
sleep 5
service supervisor start

# project
cd /vagrant
if [ ! -d "vendor" ]; then composer install --dev; fi

SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "hashicorp/precise32"

  config.vm.provision "shell", inline: $setupEnvironment
end
