<?php

namespace RadioAdmin;

return array(
    'controllers' => array(
        'invokables' => array(
            'RadioAdmin\Controller\Auth' => 'RadioAdmin\Controller\AuthController'
        ),
    ),
    'router' => array(
        'routes' => array(
            'sign_in' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign_in',
                    'defaults' => array(
                        'controller' => 'RadioAdmin\Controller\Auth',
                        'action' => 'login'
                    )
                )
            ),
            'auth' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/authenticate',
                    'defaults' => array(
                        'controller' => 'RadioAdmin\Controller\Auth',
                        'action' => 'authenticate'
                    )
                )
            ),
            'sign_out' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/sign_out',
                    'defaults' => array(
                        'controller' => 'RadioAdmin\Controller\Auth',
                        'action' => 'logout'
                    )
                )
            ),
        )
    ),
    'view_manager' => array(
        'display_exceptions' => true,
        'display_not_found_reason' => true,
        'template_path_stack' => array(__DIR__ . '/../view',),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                )
            )
        ),
        'authentication' => array(
            'orm_default' => array(
                'objectManager' => 'doctrine.documentmanager.odm_default',
                'identityClass' => 'Radio\Entity\User',
                'identityProperty' => 'username',
                'credentialProperty' => 'password',
                'credentialCallable' => 'Radio\Entity\User::testPassword'
            ),
        ),
    ),
);