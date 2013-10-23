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

1. run the update.sh, this installs frontend and backend dependencies

2. Set the rewrite definitions (already done in the VM)

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
- Károly Kiripolszky karcsi@ekezet.com
