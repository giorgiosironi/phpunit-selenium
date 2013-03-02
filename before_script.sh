echo "Submodules of phpunit-selenium"
git submodule init
git submodule update
echo "Getting composer"
curl -O http://getcomposer.org/composer.phar
echo "PHPUnit 3.7 is used"
cd vendor/phpunit
git checkout 3.7
git pull --ff-only origin 3.7
../../composer.phar install --dev
cd -
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
