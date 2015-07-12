<?php
/**
* @Author: Dan Marinescu
* @Email: dan.m@my1hr.com
* @Date:   2015-07-08 05:57:56
* @Last Modified by:   Dan Marinescu
* @Last Modified time: 2015-07-11 23:47:26
*/
namespace DbLayer;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    /**
      * Return an array for passing to Zend\Loader\AutoloaderFactory.
      *
      * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/' . __NAMESPACE__,
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }
}
