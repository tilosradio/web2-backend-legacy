<?php
return array(
    'author-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'route' => '/api/v0/author/:id',
            'permission' => '\Radio\Util\AccessControlUtil::authorOwner',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Author',
                'action' => 'update'
            ),
        )
    ),
    'author-create' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'POST',
            'route' => '/api/v0/author',
            'permission' => 'admin',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Author',
                'action' => 'create'
            ),
        )
    )
);
