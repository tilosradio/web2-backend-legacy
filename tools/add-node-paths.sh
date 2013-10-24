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
fi
hash -r
