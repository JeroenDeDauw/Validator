#! /bin/bash

set -x

originalDirectory=$(pwd)

cd ..

wget https://github.com/wikimedia/mediawiki/archive/master.tar.gz
tar -zxf master.tar.gz
mv mediawiki-master phase3

wget https://github.com/wikimedia/mediawiki-vendor/archive/master.tar.gz
tar -zxf master.tar.gz
mv mediawiki-master phase3/vendor

cd phase3

git checkout master

mysql -e 'create database its_a_mw;'
php maintenance/install.php --dbtype $DBTYPE --dbuser root --dbname its_a_mw --dbpath $(pwd) --pass nyan TravisWiki admin

cd extensions

cp -r $originalDirectory Validator

cd Validator
composer install --prefer-source
composer require 'phpunit/phpunit=3.7.*' --prefer-source

cd ../..

echo 'require_once( __DIR__ . "/extensions/Validator/Validator.php" );' >> LocalSettings.php

echo 'error_reporting(E_ALL| E_STRICT);' >> LocalSettings.php
echo 'ini_set("display_errors", 1);' >> LocalSettings.php
echo '$wgShowExceptionDetails = true;' >> LocalSettings.php
echo '$wgDevelopmentWarnings = true;' >> LocalSettings.php
echo "putenv( 'MW_INSTALL_PATH=$(pwd)' );" >> LocalSettings.php

php maintenance/update.php --quick
