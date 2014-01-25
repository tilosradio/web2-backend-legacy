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


    'm3u-creator' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/m3u/[:from]/[:duration].m3u',!
            'defaults' => array(
                'controller' => 'Radio\Controller\M3u',
                'action' => 'download'),
        )
    ),
    'show-rss' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/oldfeed/show/:id',
            'defaults' => array(
                'controller' => 'Radio\Controller\Atom',
                'action' => 'showFeed'
            )
        )
    ),
    'show-super-rss' => array(
        'type' => 'Segment',
        'options' => array(
            'route' => '/feed/show/:id',
            'defaults' => array(
                'controller' => 'Radio\Controller\Atom',
                'action' => 'showSuperFeed'
            )
        )
    ),
);