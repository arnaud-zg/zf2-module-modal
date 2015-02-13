<?php

return array(
    'modal' => array(
        'type' => 'segment',
        'options' => array(
            'route' => '/modal[/:action]',
            'constraints' => array(
                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            ),
            'defaults' => array(
                'controller' => 'modal',
            ),
        ),
    ),
);
