<?php
return array(
    'author-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/author/:id',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Author',
                'action' => 'get'
            ),
        )
    ),
    'author-list' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/author',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Author',
                'action' => 'getList'
            ),
        )
    ),
    
);
