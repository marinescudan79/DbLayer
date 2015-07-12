<?php

/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-11 02:56:38
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-12 06:27:57
 */

namespace DbLayer\Service;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class DbLayerService implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    public function get($value)
    {
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        return new \DbLayer\Service\TableGateway($value, (object) $adapter);
    }
}
