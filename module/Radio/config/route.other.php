<?php
return array(
    'home' => array(
        'type' => 'literal',
        'options' => array(
            'route' => '/',
            'defaults' => array(
                'controller' => 'Radio\Controller\Index',
                'action' => 'index'
            )
        )
    ),
    'sign_in' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/api/v0/auth/sign_in',
            'defaults' => array(
                'controller' => 'Radio\Controller\Auth',
                'action' => 'login'
            )
        )
    ),
    'sign_out' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/api/v0/auth/sign_out',
            'defaults' => array(
                'controller' => 'Radio\Controller\Auth',
                'action' => 'logout'
            )
        )
    ),
    'password_reset' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/auth/password_reset',
            'defaults' => array(
                'controller' => 'Radio\Controller\Auth',
                'action' => 'passwordReset',
            )
        )
    ),
    'show-episodes' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/show/:id/episodes',
            'defaults' => array(
                'controller' => 'Radio\Controller\Show',
                'action' => 'listOfEpisodes'
            )
        )
    ),
    'author-rest' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/author[/:id]',
            'defaults' => array('controller' => 'Radio\Controller\Author',)
        )
    ),
    'currentuser-rest' => array(
        'type' => 'Literal',
        'options' => array(
            'route' => '/api/v0/user/me',
            'defaults' => array('controller' => 'Radio\Controller\User',),
            'action' => 'currentUser'
        )
    ),
    'user-rest' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/user/[:id]',
            'defaults' => array('controller' => 'Radio\Controller\User',)
        )
    ),
    'episode-rest' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/episode[/:id]',
            'constraints' => array('id' => '[0-9]*',),
            'defaults' => array('controller' => 'Radio\Controller\Episode',)
        )
    ),
    'show-text' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/text[/:id]',
            'defaults' => array('controller' => 'Radio\Controller\Text',)
        )
    ),
    'text-list' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/text/[:type]/list',
            'defaults' => array(
                'controller' => 'Radio\Controller\Text',
                'action' => 'listOfType'
            )
        )
    ),
    'm3u-creator' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/m3u/[:from]/[:duration].m3u',
            'defaults' => array(
                'controller' => 'Radio\Controller\M3u',
                'action' => 'download'),
        )
    ),
    'show-rss' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/feed/show/:id',
            'defaults' => array(
                'controller' => 'Radio\Controller\Atom',
                'action' => 'showFeed'
            )
        )
    ),
    'scheduling-rest' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/api/v0/show/:show/scheduling[/:id]',
            'defaults' => array('controller' => 'Radio\Controller\Scheduling',)
        )
    ));