echo "Getting composer"
curl -O http://getcomposer.org/composer.phar
echo "Dependencies"
composer.phar install --dev
echo "Starting Python HTTP server"
cd selenium-1-tests
python -m SimpleHTTPServer 8080 > /dev/null 2>&1 &
cd ..
echo "Starting xvfb"
sh -e /etc/init.d/xvfb start
export DISPLAY=:99.0
echo "Starting Selenium"
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.30.0.jar
java -jar selenium-server-standalone-2.30.0 > /dev/null 2>&1 &
sleep 30
echo "Finished setup"
