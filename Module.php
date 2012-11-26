<?php

namespace MyCustomTheme;

use Zend\EventManager\EventInterface;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;

class Module
{
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getParam('application');
        $em  = $app->getEventManager();

        $em->attach(MvcEvent::EVENT_DISPATCH, array($this, 'layoutForRoute'));
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
        }
    }
}
