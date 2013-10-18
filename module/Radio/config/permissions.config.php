<?php

// TODO: load roles from database (in Module::getPermissionsConfig())
return array(
    'acl' => array(
        'roles' => array(
            'guest'  => null,
            'user'   => 'guest',
            'author' => 'user',
            'admin'  => 'author'
        ),
        'resources' => array(
            'allow' => array(
                'Radio\Controller\Auth' => array(
                    ':all'      => 'guest'
                ),
                'Radio\Controller\M3u' => array(
                    ':all'      => 'guest'
                ),
                'Radio\Controller\Text' => array(
                    ':all'      => 'guest'
                ),

                'Radio\Controller\Author' => array(
                    'get'      => 'guest',
                    'getList'  => 'guest',
                    'create'   => 'admin',
                    'update'   => 'author',
                    'delete'   => 'admin',
                ),
                'Radio\Controller\Show' => array(
                    'get'      => 'guest',
                    'getList'  => 'guest',
                    'create'   => 'admin',
                    'update'   => 'author',
                    'delete'   => 'admin',
                ),
                'Radio\Controller\Episode' => array(
                    'get'      => 'guest',
                    'getList'  => 'guest',
                    'create'   => 'admin',
                    'update'   => 'author',
                    'delete'   => 'admin',
                ),
            ),
        )
    )
);