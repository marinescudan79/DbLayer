<?php
/**
 * @Author: Dan Marinescu
 * @Email: dan.m@my1hr.com
 * @Date:   2015-07-11 04:12:12
 * @Last Modified by:   Dan Marinescu
 * @Last Modified time: 2015-07-11 04:14:17
 */
namespace DbLayer\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetService extends AbstractPlugin
{
    public function __invoke($serviceName)
    {
        $this->getServiceLocator()->get($serviceName);
    }
}
