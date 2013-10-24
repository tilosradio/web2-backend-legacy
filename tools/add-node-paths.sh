#!/usr/bin/env bash


PROJECT_DIR=$(cd $(dirname ${BASH_SOURCE[0]});cd ..;pwd)

NODE_PATH="$PROJECT_DIR/yeoman/node_modules/.bin"

echo $PATH|grep -q "$NODE_PATH"
if [[ $? -ne 0 ]]; then
  export PATH="$NODE_PATH:$PATH"
fi


grep -q "$NODE_PATH" $HOME/.bash_profile
if [[ $? -ne 0 ]]; then
  echo "export PATH=\"$NODE_PATH:\$PATH\"" >>$HOME/.bash_profile
  echo "=== ATTENTION ==="
  echo "If you're using bash, please relogin after finishing update.sh, as your"
  echo "PATH variable is changed to reflect your newly installed nodejs dev environment."
  echo "Press enter to continue now."
  echo "================="
  read
fi
hash -r
