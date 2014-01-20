<?php
return array(
    'episode-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'route' => '/api/v0/episode/:id',
            'permission' => 'author',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Episode',
                'action' => 'update'
            ),
        )
    ),
    'episode-create' => array(
    'type' => 'Radio\Util\CustomSegmentRouter',
    'options' => array(
        'method' => 'POST',
        'route' => '/api/v0/episode',
        'permission' => 'author',
        'defaults' => array(
            'controller' => 'RadioAdmin\Controller\Episode',
            'action' => 'create'
        ),
    )
)
);
