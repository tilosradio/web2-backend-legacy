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

1. run ```php composer.phar install``` to populate the vendor directory with the libraries (Zend Framework 2, etc.)
2. Set your webserver, use the www subdirectory as the content root


Development tools
-----------------

* [EditorConfig][http://editorconfig.org/]: basic code style definition
* Zend Framework 2.2: backend framework
* TODO

Contributors:
=============
László Károlyi laszlo@karolyi.hu