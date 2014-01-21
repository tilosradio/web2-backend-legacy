<?php
return array(

    'sign_in' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/auth/sign_in',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Auth',
                'action' => 'login'
            )
        )
    ),
    'sign_out' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'GET',
            'route' => '/api/v0/auth/sign_out',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Auth',
                'action' => 'logout'
            )
        )
    ),
    'password_reset' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/auth/password_reset',
            'permission' => 'guest',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Auth',
                'action' => 'passwordReset',
            )
        )
    ),
);
