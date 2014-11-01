<?php
return array(

    'currentuser-rest' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/user/me',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\User',
                'action' => 'currentUserAction'
            ),

        )
    ),
    'user-rest' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'permission' => 'guest',
            'route' => '/api/v0/user/[:id]',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\User',
                'action' => 'get'),

        )
    ),
    'user-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'permission' => '\Radio\Util\AccessControlUtil::currentUser',
            'route' => '/api/v0/user/[:id]',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\User',
                'action' => 'update'),

        )
    )
);
