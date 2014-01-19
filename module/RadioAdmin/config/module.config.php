<?php

namespace Radio;

return array(
    'controllers' => array(
        'invokables' => array(
            'RadioAdmin\Controller\Show' => 'RadioAdmin\Controller\Show',

        ),
    ),
    'router' => array(
        'routes' => array_merge(
            require("route.show.php")
        )
    ),
    'view_manager' => array(
        'display_exceptions' => true,
        'exception_template' => 'error/index',
        'template_map' => array(
            'error/index' => __DIR__ . '/../view/error/index.phtml',
            '404' => __DIR__ . '/../view/error/404.phtml'
        ),
        'template_path_stack' => array(__DIR__ . '/../view',),
        'strategies' => array('ViewJsonStrategy'),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../../Radio/src/' . __NAMESPACE__ . '/Entity')
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
    )
);
