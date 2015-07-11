<?php
/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-11 04:12:12
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-11 04:54:55
 */
namespace DbLayer\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class DbLayerPlugin extends AbstractPlugin
{
    public function __invoke($tableName)
    {
        return $this->getController()->getServiceLocator()->get('DbLayerService')->get($tableName);
    }
}
