web2
====

The new, recreated webpage of Tilos Radio

Directories and files in the repo
---------------------------------

* __www__: The root of the public web folder
* __config__: Config of the ZF2 backend
* __module__: The code of the backend
* __vendor__: directories for the php libraries managed by the composer dependency manager (see below to create it)

How to run
----------

1. run ```php composer.phar install``` (first time ) or ```php composer.phar update``` (next times) to populate the vendor directory
 with the libraries (Zend Framework 2, etc.)

 Running the script sometimes results in an error similar to the following:
 ```
 Fatal error: Call to undefined function Composer\Json\json_decode()
 ```
 Don't panic, just install the JSON support package for your distro. On Debian it would be:
 ```
 sudo apt-get install php5-json
 ```

2. Set your webserver, use the www subdirectory as the content root

3. Create the database schema with ```vendor/bin/doctrine-module orm:schema-tool:update --force --dump-sql```

4. copy and edit config/autoload/local.php.dist to local.php

5. Set the rewrite definitions

For lighttpd (version 2):
```
# make sure the mod_rewrite is enabled
server.modules = (
  "mod_access",
  "mod_alias",
  "mod compress",
  "mod_redirect",
  "mod_rewrite",
)

if !physical.exists {
  if request.path =^ "/api/" {
    rewrite "/api/index.php";
    docroot var.vhosts_dir + var.my_docroot;
  } else {
    rewrite "/index.html";
    docroot var.vhosts_dir + var.my_docroot;
  }
}


```

Development tools
-----------------

* [EditorConfig](http://editorconfig.org/): basic code style definition
* Zend Framework 2.2: backend framework
* TODO

Contributors:
=============
- László Károlyi laszlo@karolyi.hu
- Márton Elek elek@anzix.net
- Dávid Barkóczi david.barkoczi@gmail.com
