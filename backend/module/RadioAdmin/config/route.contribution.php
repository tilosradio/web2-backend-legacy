<?php
return array(
    'contribution-get' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/contribution/:id',
            'permission' => 'author',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Contribution',
                'action' => 'get'
            ),
        )
    ),
    'contribution-new' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/contribution',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Contribution',
                'action' => 'create'
            ),
        )
    ),
    'contribution-delete' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'DELETE',
            'route' => '/api/v0/contribution/:id',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Contribution',
                'action' => 'delete'
            ),
        )
    )
);
