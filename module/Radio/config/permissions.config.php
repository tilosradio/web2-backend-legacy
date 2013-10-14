<?php

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
                'Radio\Controller\Author' => array(
                    'get'       => 'guest',
                    'getList'   => 'guest'
                ),
                'Radio\Controller\Show' => array(
                    'get'       => 'guest',
                    'getList'   => 'guest'
                ),
                'Radio\Controller\Episode' => array(
                    'get'       => 'guest',
                    'getList'   => 'guest'
                ),
            )
        )
    )
);