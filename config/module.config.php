<?php
/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-09 22:43:00
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-11 21:56:19
 */
return array(
    'controller_plugins' => array(
        'invokables' => array(
            'getService' => 'DbLayer\Plugin\GetService',
            'getTable'   => 'DbLayer\Plugin\DbLayerPlugin',
        )
    ),
     'service_manager' => array(
         'invokables' => array(
            'DbLayerService' => 'DbLayer\Service\DbLayerService',
         )
     ),
     'view_manager' => array(
         'template_path_stack' => array(
             __DIR__ . '/../view',
         ),
     ),
 );
