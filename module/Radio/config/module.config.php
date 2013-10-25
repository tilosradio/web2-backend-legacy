<?php
namespace Radio;

return array(
    'controllers' => array(
        'invokables' => array(
            'Radio\Controller\Show' => 'Radio\Controller\Show',
            'Radio\Controller\Index' => 'Radio\Controller\Index',
            'Radio\Controller\Author' => 'Radio\Controller\Author',
            'Radio\Controller\Episode' => 'Radio\Controller\Episode',
            'Radio\Controller\Auth' => 'Radio\Controller\Auth',
            'Radio\Controller\Text' => 'Radio\Controller\Text',
            'Radio\Controller\M3u' => 'Radio\Controller\M3u'


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
            'sign_in' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api/auth/sign_in',
                    'defaults' => array(
                        'controller' => 'Radio\Controller\Auth',
                        'action' => 'login'
                    )
                )
            ),
            'sign_out' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/api/auth/sign_out',
                    'defaults' => array(
                        'controller' => 'Radio\Controller\Auth',
                        'action' => 'logout'
                    )
                )
            ),
            'show-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/show[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Show',)
                )
            ),
            'author-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/author[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Author',)
                )
            ),
            'episode-rest' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/episode[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Episode',)
                )
            ),
            'episode-text' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/api/text[/:id]',
                    'constraints' => array('id' => '[0-9]*',),
                    'defaults' => array('controller' => 'Radio\Controller\Text',)
                )
            ),
            'm3u-creator' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/m3u/[:from]/[:duration].m3u',
                    'defaults' => array(
                        'controller' => 'Radio\Controller\M3u',
                        'action' => 'download'),

                )
            )
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
    )
);
