<?php
//bh6ouac1ghk8oug1pub43q8j11
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
                'SwaggerModule\Controller\Documentation' => array(
                    ':all'      => 'guest'
                ),
                'Radio\Controller\Auth' => array(
                    ':all'      => 'guest'
                ),
                'Radio\Controller\User' => array(
                    'create'      => 'admin',
                    'get'         => 'guest',
                    'passwordReset' => 'guest',

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
                    'listOfEpisodes' => 'guest',
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
                'Radio\Controller\Scheduling' => array(
                    'get'      => 'guest',
                    'getList'  => 'guest',
                    'update'  => 'admin',
                    'delete'  => 'admin',
                    'create'  => 'admin'



                ),
            ),
        )
    )
);