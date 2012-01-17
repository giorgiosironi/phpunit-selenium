git submodule init
git submodule update
cd selenium-1-tests
python -m SimpleHTTPServer 8080 &
cd ..
sh -e /etc/init.d/xvfb start
export DISPLAY=:99.0
wget http://selenium.googlecode.com/files/selenium-server-standalone-2.15.0.jar
java -jar selenium-server-standalone-2.15.0.jar > /dev/null 2>&1 &
sleep 2
