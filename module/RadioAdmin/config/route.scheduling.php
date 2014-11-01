<?php
return array(
    'scheduling-list' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/show/:show/schedulings',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Scheduling',
                'action' => 'getList'
            ),
        )
    ),
    'scheduling-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/scheduling/:id',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Scheduling',
                'action' => 'get'
            ),
        )
    ),
    'scheduling-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'route' => '/api/v0/scheduling/:id',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Scheduling',
                'action' => 'update'
            ),
        )
    ),
    'scheduling-new' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/scheduling',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Scheduling',
                'action' => 'create'
            ),
        )
    ),
    'scheduling-delete' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'DELETE',
            'route' => '/api/v0/scheduling/:id',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Scheduling',
                'action' => 'delete'
            ),
        )
    )
);
