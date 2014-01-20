<?php
return array(
    'episode-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/episode/:id',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Episode',
                'action' => 'get'
            ),
        )
    ),
    'episode-list' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/episode',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'Radio\Controller\Episode',
                'action' => 'getList'
            ),
        )
    ),
    
);
