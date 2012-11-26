<?php

namespace MyCustomTheme;

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
        $config = array(
            'view_manager' => array(
                'template_map' => array(
                    'layout/layout' => __DIR__ . '/src/' . __NAMESPACE__ . '/view/layout/layout.phtml',
                ),
                'template_path_stack' => array(
                    __DIR__ . '/src/' . __NAMESPACE__ . '/view',
                ),
            ),
        );
        return $config;
    }
}
