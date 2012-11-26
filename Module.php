<?php

namespace MyCustomTheme;

use Zend\ModuleManager\Feature;
use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\ServiceProviderInterface,
    Feature\BootstrapListenerInterface
{
    protected $serviceConfig;

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

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $em  = $app->getEventManager();

        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'layoutForRoute'));
    }

    public function getConfig()
    {
        $config =  array();
        $configFiles = array(
            __DIR__ . '/config/module.config.php',
        );
        foreach($configFiles as $configFile) {
            $config = \Zend\Stdlib\ArrayUtils::merge($config, include $configFile);
        }

        return $config;
    }

    public function layoutForRoute(MvcEvent $e)
    {
        $app    = $e->getParam('application');
        $sm     = $app->getServiceManager();
        $config = $sm->get('config');

        $match = $e->getRouteMatch();
        if ($match instanceof RouteMatch && $match->getParam('controller') === 'catalog') {
            $layout     = $config['my_custom_theme']['catalog_layout'];
            $controller = $e->getTarget();
            $controller->layout($layout);
        } else {
            return;
        }
    }

    /**
     * @return serviceConfig
     */
    public function getServiceConfig()
    {
        return $this->serviceConfig;
    }

    /**
     * @param $serviceConfig
     * @return self
     */
    public function setServiceConfig($serviceConfig)
    {
        $this->serviceConfig = $serviceConfig;
        return $this;
    }
}
