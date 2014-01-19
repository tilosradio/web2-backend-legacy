<?php
return array(
    'show-update' => array(
        'type' => 'Radio\Util\CustomSegmentRouter',
        'options' => array(
            'method' => 'PUT',
            'route' => '/api/v0/show/:id',
            'defaults' => array(
                'controller' => 'RadioAdmin\Controller\Show',
                'action' => 'update'
            ),
        )
    )
);
