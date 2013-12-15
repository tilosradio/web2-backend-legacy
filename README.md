web2
====

The new, recreated webpage of Tilos Radio

Directories and files in the repo
---------------------------------

* __www__: The root of the public web folder
* __config__: Config of the ZF2 backend
* __module__: The code of the backend
* __vendor__: directories for the php libraries managed by the composer dependency manager (see below to create it)
* __yeaoman__: directory of the frontend application
* __yeoman/app__: raw fronted source files (HTML, JS, CSS/SCSS)
* __yeoman/app/bower_components__: client side dependencies managed with bower, user ```bower update``` regulary to regenerate this folder


How to run
----------

1. run the `tools/update.sh`, this installs frontend and backend dependencies, and modifies your PATH to reflect your locally installed tools for development.

2. Set the rewrite definitions (already done in the Virtualmachine)

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
   rewrite "/backend.php";
   docroot var.vhosts_dir + var.my_docroot;
}
```

3. Compass/Ruby is needed for development.

- If you're on an Ubuntu Box:`sudo apt-get install ruby-compass`
- on a mac: `sudo gem install compass` (Ruby is preinstalled on Macs)


Development tools
-----------------

* [EditorConfig](http://editorconfig.org/): basic code style definition
* Zend Framework 2.2: backend framework
* Composer: PHP dependency management
* doctrine: PHP Object relation mapping tool
* AngularJS: client side MVC web framework
* Bower: client side dependency management
* Grunt: javascript build tool
* yeoman: client side scaffolding tool
* mocha, karma, chai: client side test frameworks

Contributors:
=============

- Márton Elek level2@anzix.net
- Dávid Barkóczi david.barkoczi@gmail.com
- Károly Kiripolszky karcsi@ekezet.com
- Sándor Farkas sandor.farkas@gmail.com
- Eva Hajdu vid.eskin@gmail.com
- Márton Rónyai marci@pioneers.hu
- László Károlyi laszlo@karolyi.hu
