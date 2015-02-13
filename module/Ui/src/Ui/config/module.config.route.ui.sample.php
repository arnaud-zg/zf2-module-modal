<?php

return array(
    'sample' => array(
        'type' => 'Zend\Mvc\Router\Http\Literal',
        'options' => array(
            'route' => '/sample',
            'defaults' => array(
                'controller' => 'sample',
                'action' => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'modal' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/modal',
                    'defaults' => array(
                        'controller' => 'sample',
                        'action' => 'modal',
                    ),
                ),
            ),
            'info' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/info',
                    'defaults' => array(
                        'controller' => 'sample',
                        'action' => 'info',
                    ),
                ),
            ),
            'confirm' => array(
                'type' => 'Segment',
                'options' => array(
                    'route' => '/confirm',
                    'defaults' => array(
                        'controller' => 'sample',
                        'action' => 'confirm',
                    ),
                ),
            ),
        ),
    ),
);
