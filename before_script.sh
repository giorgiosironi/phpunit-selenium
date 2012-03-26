git submodule init
git submodule update
cd vendor/phpunit
git checkout 3.6
cd -
cd selenium-1-tests
python -m SimpleHTTPServer 8080 > /dev/null 2>&1 &
cd ..
sh -e /etc/init.d/xvfb start
export DISPLAY=:99.0
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.20.0.jar
java -jar selenium-server-standalone-2.20.0.jar > /dev/null 2>&1 &
sleep 30
