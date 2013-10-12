# new fields for the `author` table
ALTER TABLE  `author` ADD  `email` VARCHAR( 255 ) NOT NULL AFTER  `name` ,
    ADD  `password` VARCHAR( 40 ) NOT NULL COMMENT  'SHA1' AFTER  `email` ,
    ADD  `salt` VARCHAR( 40 ) NOT NULL COMMENT  'SHA1' AFTER  `password` ,
    ADD INDEX (  `email` ) ;