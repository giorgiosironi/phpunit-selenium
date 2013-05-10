serverUrl='http://127.0.0.1:4444'
serverVersion='2.32.0'
serverFile=selenium-server-standalone-$serverVersion.jar


if [ ! -f composer.phar ]; then
    echo "Getting composer"
    curl -O http://getcomposer.org/composer.phar
else
    php composer.phar self-update
fi

echo "Dependencies"
if [ ! -d vendor ] || [ ! -f vendor/autoload.php ]; then 
    php composer.phar install --dev
else
    php composer.phar update --dev
fi
echo "Starting Python HTTP server"
cd selenium-1-tests
python -m SimpleHTTPServer 8080 > /dev/null 2>&1 &
cd ..
echo "Starting xvfb"
echo "Starting Selenium"
if [ ! -f $serverFile ]; then
    wget http://selenium.googlecode.com/files/selenium-server-standalone-$serverVersion.jar -O $serverFile
fi
xvfb-run java -jar $serverFile > /dev/null 2>&1 &

wget --retry-connrefused --tries=60 --waitretry=1 --output-file=/dev/null $serverUrl/wd/hub/status -O /dev/null
if [ ! $? -eq 0 ]; then
    echo "Selenium Server not started"
else
    echo "Finished setup"
fi
