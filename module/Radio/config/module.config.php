<?php
return array(
    'controllers' => array(
        'invokables' => 
            array('Radio\Controller\Program' => 'Radio\Controller\Program',),
    ),
    'router' => array(
        'routes' => array(
            'album-rest' => array(
                'type' => 'Segment', 
		'options' => array(
		    'route' => '/program[/:id]', 
		    'constraints' => array('id' => '[0-9]+',), 
		    'defaults' => array('controller' => 'Radio\Controller\Program',)
		,)
	    ,)
        ,)
    ,), 
    'view_manager' => array(
        'display_exceptions' => true,
        'exception_template' => 'error/index', 
	'template_map' => array('error/index' => __DIR__ . '/../view/error/index.phtml',), 
	'template_path_stack' => array(__DIR__ . '/../view',), 
	'strategies' => array('ViewJsonStrategy',),
    ),
    'factories' => array(
        'Radio\Model\ProgramTable' =>  function($sm) {
            $tableGateway = $sm->get('ProgramTableGateway');
            $table = new ProgramTable($tableGateway);
            return $table;
        },
        'ProgramTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Program());
            return new TableGateway('program', $dbAdapter, null, $resultSetPrototype);
        },
    ),
);
