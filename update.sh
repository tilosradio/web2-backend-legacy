#!/bin/bash
echo "Updating php dependencies"
php composer.phar update
echo "Updating database"
if [ ! -f config/autoload/local.php ]; then
   cd config/autoload/local.php.dist config/autoload/local.php
fi
vendor/bin/doctrine-module orm:schema-tool:update --force --dump-sql
echo "Updating frontend dependencies"
cd yeoman
npm install
bower install
cd ..
