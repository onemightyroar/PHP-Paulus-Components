<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components;

use OneMightyRoar\PHP_Paulus_Components\DataCollection\ImmutableDataCollection;
use OneMightyRoar\PHP_Paulus_Components\Environment\EnvironmentFactory;
use OneMightyRoar\PHP_Paulus_Components\Environment\EnvironmentInterface;
use OneMightyRoar\PHP_Paulus_Components\FileLoader\FileArrayLoader;
use Paulus\FileLoader\RouteLoader;
use Paulus\FileLoader\RouteLoaderFactory;
use Paulus\Paulus;
use Paulus\Response\ApiResponse;
use Paulus\Router;
use Paulus\ServiceLocator;
use Psr\Log\LoggerInterface;

/**
 * BasicApp
 *
 * Base application class that loads and
 * initializes our Paulus application
 *
 * @uses Paulus\Paulus
 * @package OneMightyRoar\PHP_Paulus_Components
 */
class BasicApp extends Paulus
{

    /**
     * Constants
     */

    /**
     * The key used in our service locator
     * for our shared configuration
     *
     * @const string
     */
    const CONFIG_KEY = 'config';


    /**
     * Properties
     */

    /**
     * The base path of the application
     *
     * @var string
     * @access protected
     */
    protected $app_base_path;

    /**
     * The base namespace of the application
     *
     * @var string
     * @access protected
     */
    protected $app_namespace;

    /**
     * The application's current environment
     *
     * @type AbstractEnvironment
     */
    protected $environment;


    /**
     * Methods
     */

    /**
     * Constructor
     *
     * {@inheritdoc}
     *
     * @see \Paulus\Paulus::__construct()
     * @param string $base_path         The bash path to load and define constants from
     * @param string $app_namespace     The name of the application's namespace, to be used in the autoloader
     * @param array $config             A configuration array that matches Paulus' config pattern
     * @param Router $router            The Router instance to use for HTTP routing
     * @param ServiceLocator $locator   The service locator/container for the app
     * @param LoggerInterface $logger   A PSR LoggerInterface compatible logger instance
     * @param EnvironmentInterface $environment The context of the app's current environment
     * @access public
     */
    public function __construct(
        $base_path = __DIR__,
        $app_namespace = null,
        array $config = null,
        Router $router = null,
        ServiceLocator $locator = null,
        LoggerInterface $logger = null,
        EnvironmentInterface $environment = null
    ) {
        // Call our parent constructor
        parent::__construct($router, $locator, $logger);

        // Property assignments
        $this->app_base_path = $base_path;
        $this->app_namespace = $app_namespace;

        // Set the router's controller namespace
        if (null !== $app_namespace) {
            $this->router->setControllerNamespace($app_namespace .'\Controller');
        }

        // Setup our config
        $config_path = $this->app_base_path . '/../configs/';
        $config = $config ?: (new FileArrayLoader($config_path))->load();

        // Enter our config into the locator AFTER its been initialized
        $this->locator[static::CONFIG_KEY] = new ImmutableDataCollection($config);

        // Set the environment
        $this->environment = $environment ?: EnvironmentFactory::createFromString();
    }

    /**
     * Get the application's configuration
     *
     * @access public
     * @return ImmutableDataCollection
     */
    public function config()
    {
        return $this->locator[static::CONFIG_KEY];
    }

    /**
     * Get the application's current environment
     *
     * @return AbstractEnvironment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Prepare the application to be run
     *
     * @param boolean $auto_load_routes
     * @param RouteLoader $route_loader
     * @access public
     * @return BasicApp
     */
    public function prepare($auto_load_routes = null, RouteLoader $route_loader = null)
    {
        // Change our defaults
        $auto_load_routes = (null === $auto_load_routes) ? true : false;
        $route_loader = $route_loader
            ?: RouteLoaderFactory::buildByDirectoryInferring($this->router, $this->app_base_path);

        // Call our parent
        return parent::prepare($auto_load_routes, $route_loader);
    }
}
