<?php

return array(
    'controllers' => array(
        'invokables' =>
        array(
            'Radio\Controller\Show' => 'Radio\Controller\Show',
            'Radio\Controller\Index' => 'Radio\Controller\Index',
            'Radio\Controller\Author' => 'Radio\Controller\Author'
        ),
    ),
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Radio\Controller\Index',
                        'action' => 'index'
                    )
                )
            ),
            'show-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/show[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Show',)
                )
            ),
            'author-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/author[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Author',)
                )
            )
        ,)
    ,),
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
);
