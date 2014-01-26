<?php
return array(
    'show-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'route' => '/api/v0/show/:id',
            'permission' => '\Radio\Util\AccessControlUtil::showOwner',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Show',
                'action' => 'update'
            ),
        )
    ),
    'show-new' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/show',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Show',
                'action' => 'create'
            ),
        )
    )
);
