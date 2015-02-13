<?php

return array(
    'ui' => array(
        'id' => 'ui',
        'type' => 'Literal',
        'options' => array(
            'route' => '/ui',
            'defaults' => array(
                '__NAMESPACE__' => 'Ui\Controller',
                'controller' => 'Index',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true
    ),
);
