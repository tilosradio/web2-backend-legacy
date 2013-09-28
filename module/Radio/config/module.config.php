<?php
return array('controllers' => array('invokables' => array('Radio\Controller\Program' => 'Radio\Controller\Program',),),
// The following section is new` and should be added to your file
'router' => array('routes' => array('album-rest' => array('type' => 'Segment', 'options' => array('route' => '/program[/:id]', 'constraints' => array('id' => '[0-9]+',), 'defaults' => array('controller' => 'Radio\Controller\Program',),),),),), 'view_manager' => array('exception_template' => 'error/index', 'template_map' => array('error/index' => __DIR__ . '/../view/error/index.phtml',), 'template_path_stack' => array(__DIR__ . '/../view',), 'strategies' => array('ViewJsonStrategy',),),);
