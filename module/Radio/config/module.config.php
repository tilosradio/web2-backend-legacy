<?php
return array(
    'controllers' => array(
        'invokables' => 
            array('Radio\Controller\Show' => 'Radio\Controller\Show',),
    ),
    'router' => array(
        'routes' => array(
            'album-rest' => array(
                'type' => 'Segment', 
		'options' => array(
		    'route' => '/show[/:id]', 
		    'constraints' => array('id' => '[0-9]+',), 
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
	'strategies' => array('ViewJsonStrategy',),
    ),
    'factories' => array(
        'Radio\Model\ShowTable' =>  function($sm) {
            $tableGateway = $sm->get('ShowTableGateway');
            $table = new ShowTable($tableGateway);
            return $table;
        },
        'ProgramTableGateway' => function ($sm) {
            $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
            $resultSetPrototype = new ResultSet();
            $resultSetPrototype->setArrayObjectPrototype(new Show());
            return new TableGateway('show', $dbAdapter, null, $resultSetPrototype);
        },
    ),
);
