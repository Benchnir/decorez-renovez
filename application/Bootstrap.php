<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
    }

    /**
     * generate registry
     * @return Zend_Registry
     */
    protected function _initRegistry()
    {
        $registry = Zend_Registry::getInstance();
        $options = $this->getOptions(); 
        Zend_Registry::set('assetBasePath', $options['asset']['basePath']);
        Zend_Registry::set('host', $options['asset']['host']);
        return $registry;
    }
    
    protected function _initNamespaces()
    {
        $autoloader = Zend_Loader_Autoloader::getInstance();
        $autoloader->registerNamespace('Facebook_');
    }

    /**
     * Register namespace Default_
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload()
    {
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => 'Default_',
            'basePath' => __DIR__,
        ));
        return $autoloader;
    }

    public function _initDoctrine()
    {
        // include and register Doctrine's class loader
        require_once('Doctrine/Common/ClassLoader.php');
        $classLoader = new \Doctrine\Common\ClassLoader(
                        'Doctrine',
                        APPLICATION_PATH . '/../library/'
        );
        $classLoader->register();

        // create the Doctrine configuration
        $config = new \Doctrine\ORM\Configuration();

        // setting the cache ( to ArrayCache. Take a look at
        // the Doctrine manual for different options ! )
        $cache = new \Doctrine\Common\Cache\ArrayCache;
        $config->setMetadataCacheImpl($cache);
        $config->setQueryCacheImpl($cache);

        // choosing the driver for our database schema
        // we'll use annotations
        $driver = $config->newDefaultAnnotationDriver(
                APPLICATION_PATH . '/models/Base'
        );
        $config->setMetadataDriverImpl($driver);

        // set the proxy dir and set some options
        $config->setProxyDir(APPLICATION_PATH . '/models/proxies');
        $config->setAutoGenerateProxyClasses(true);
        $config->setProxyNamespace('App\Proxies');

        // now create the entity manager and use the connection
        // settings we defined in our application.ini
        $connectionSettings = $this->getOption('doctrine');
        $conn = array(
            'driver' => $connectionSettings['conn']['driv'],
            'user' => $connectionSettings['conn']['user'],
            'password' => $connectionSettings['conn']['pass'],
            'dbname' => $connectionSettings['conn']['dbname'],
            'host' => $connectionSettings['conn']['host'],
            'charset' => 'utf8',
            'driverOptions' => array(
                1002 => 'SET NAMES utf8')
        );
        $entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);
        $entityManager->getEventManager()
                ->addEventSubscriber(
                        new \Doctrine\DBAL\Event\Listeners\MysqlSessionInit('utf8', 'utf8_general_ci')
        );

        // push the entity manager into our registry for later use
        $registry = Zend_Registry::getInstance();
        $registry->entitymanager = $entityManager;

        return $entityManager;
    }

    public function _initPlugin()
    {
        Zend_Controller_Front::getInstance()->registerPlugin(new \My_Layout_AuthPlugin());
    }

    public function _initPagination()
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('_partials/pagination.phtml');
    }

    public function _initHelper()
    {
        $view = $this->getResource('view');
        $view->setHelperPath(APPLICATION_PATH . '/views/helpers');
    }

    public function _initRoutes()
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'production');
        $router = new Zend_Controller_Router_Rewrite();
        $router->addConfig($config, 'routes');
    }

}

