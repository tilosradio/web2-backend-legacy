<?php
return array(
    'tag-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/tag',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Tag',
                'action' => 'getList'
            ),
        )
    ),
    'tag-list' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/tag/:name',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Tag',
                'action' => 'get'
            ),
        )
    ),
    
);
