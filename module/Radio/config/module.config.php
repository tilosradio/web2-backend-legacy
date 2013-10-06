<?php
return array(
    'controllers' => array(
        'invokables' => 
            array('Radio\Controller\Show' => 'Radio\Controller\Show',),
    ),
    'router' => array(
        'routes' => array(
            'show-rest' => array(
                'type' => 'Segment', 
		'options' => array(
		    'route' => '/show[/:id]', 
		    'constraints' => array('id' => '[0-9]*',), 
		    'defaults' => array('controller' => 'Radio\Controller\Show',)
		,)
	    ,)
        ,)
    ,), 
    'view_manager' => array(
        'display_exceptions' => true,
        'exception_template' => 'error/index', 
	'template_map' => array('error/index' => __DIR__ . '/../view/error/index.phtml',), 
	'template_path_stack' => array(__DIR__ . '/../view',), 
	'strategies' => array('ViewJsonStrategy'),
    ),
);
