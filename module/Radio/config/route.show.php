<?php
return array(
    'show-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/show/:id',
            'defaults' => array(
                'controller' => 'Radio\Controller\Show',
                'action' => 'get'
            ),
        )
    ),
    'show-list' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/show',
            'defaults' => array(
                'controller' => 'Radio\Controller\Show',
                'action' => 'getList'
            ),
        )
    )
);
