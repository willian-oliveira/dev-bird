<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SONUser;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    /**
     * Dados para envio de email e servico do usuario
     * @return type
     */
    public function getServiceConfig(){        
        return array(
            'factories' => array(
                'SONUser\Mail\Transport' => function($sm){
                    $config = $sm->get('Config');                    
                    $transport = new SmtpTransport;
                    $options = new SmtpOptions($config['mail']);
                    $transport->setOptions($options);                    
                    return $transport;
                },
                'SONUser\Service\User' => function($sm){
                    return new Service\User($sm->get('Doctrine\ORM\EntityManager'),
                                       $sm->get('SONUser\Mail\Transport'),
                                       $sm->get('View'));
                }    
            )
        );
    }
}
