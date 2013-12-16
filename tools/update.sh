#!/usr/bin/env bash


function mysql_create {
  # user pass dbname
  local ROOT_PASS
  while [[ true ]]; do
    echo "=== Creating your DB"
    echo "I need to know your root user and password in order to first create your DB."
    read -e -p "Your mysql pass (just press enter if empty) []: " ROOT_PASS
    if [[ "$ROOT_PASS" = "" ]]; then
      mysql -h$MYSQL_HOST -P$MYSQL_PORT -uroot -e "DROP DATABASE IF EXISTS \`$MYSQL_DBNAME\`;CREATE DATABASE \`$MYSQL_DBNAME\`; GRANT ALL PRIVILEGES ON \`$MYSQL_DBNAME\`.* TO \`$MYSQL_USER\`@localhost IDENTIFIED BY '$MYSQL_PASS'"
    else
      mysql -h$MYSQL_HOST -P$MYSQL_PORT -uroot "-p$ROOT_PASS" -e "DROP DATABASE IF EXISTS \`$MYSQL_DBNAME\`;CREATE DATABASE \`$MYSQL_DBNAME\`; GRANT ALL PRIVILEGES ON \`$MYSQL_DBNAME\`.* TO \`$MYSQL_USER\`@localhost IDENTIFIED BY '$MYSQL_PASS'"
    fi
    if [[ $? -eq 0 ]]; then
      break
    else
      echo "Your credentials were invalid. Retrying..."
    fi
  done
}

function mysql_available {
  # host port user pass dbname
  if [[ "$MYSQL_PASS" = "" ]]; then
    mysql -h$MYSQL_HOST -P$MYSQL_PORT -u$MYSQL_USER $MYSQL_DBNAME -e '' >/dev/null 2>&1
  else
    mysql -h$MYSQL_HOST -P$MYSQL_PORT -u$MYSQL_USER "-p$MYSQL_PASS" $MYSQL_DBNAME -e '' >/dev/null 2>&1
  fi
  return $?
}

PROJECT_DIR=$(cd $(dirname ${BASH_SOURCE[0]});cd ..;pwd)
cd $PROJECT_DIR

echo "=== Updating php dependencies"
php composer.phar update

if [ ! -f config/autoload/local.php ]; then
  echo "=== Creating your initial mysql configuration ..."
  read -e -p "Your mysql host [localhost]: " MYSQL_HOST
  if [ "$MYSQL_HOST" == "" ]; then
    MYSQL_HOST="localhost"
  fi
  read -e -p "Your mysql port [3306]: " MYSQL_PORT
  if [ "$MYSQL_PORT" == "" ]; then
    MYSQL_PORT="3306"
  fi
  read -e -p "Your mysql user [radio]: " MYSQL_USER
  if [ "$MYSQL_USER" == "" ]; then
    MYSQL_USER="radio"
  fi
  read -e -p "Your mysql pass (just press enter if empty) []: " MYSQL_PASS
  if [ "$MYSQL_PASS" == "" ]; then
    MYSQL_PASS=""
  fi
  read -e -p "Your mysql db name [radio]: " MYSQL_DBNAME
  if [ "$MYSQL_DBNAME" == "" ]; then
    MYSQL_DBNAME="radio"
  fi
  if ! mysql_available; then
    mysql_create "$MYSQL_USER" "$MYSQL_PASS" "$MYSQL_DBNAME"
  fi
  cp config/autoload/local.php.dist config/autoload/local.php
  sed -i "s/\[MYSQL_HOST\]/$MYSQL_HOST/g" config/autoload/local.php
  sed -i "s/\[MYSQL_PORT\]/$MYSQL_PORT/g" config/autoload/local.php
  sed -i "s/\[MYSQL_USER\]/$MYSQL_USER/g" config/autoload/local.php
  sed -i "s/\[MYSQL_PASS\]/$MYSQL_PASS/g" config/autoload/local.php
  sed -i "s/\[MYSQL_DBNAME\]/$MYSQL_DBNAME/g" config/autoload/local.php
else
  echo "=== Updating database"
fi
php vendor/bin/doctrine-module orm:schema-tool:update --force --dump-sql
echo "=== Updating frontend dependencies"
cd yeoman
mkdir -p node_modules/.bin
. ../tools/add-node-paths.sh
if [[ -e /usr/bin/nodejs && ! -e node_modules/.bin/node ]]; then
  ln -s /usr/bin/nodejs node_modules/.bin/node
fi
# install npm locally, having the latest version is always a good idea
npm install npm
hash -r
node node_modules/.bin/npm install
node node_modules/.bin/bower install
