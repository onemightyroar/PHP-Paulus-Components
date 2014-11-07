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

use Paulus\Response\ApiResponse;
use Paulus\Router;
use Paulus\ServiceLocator;
use Psr\Log\LoggerInterface;

/**
 * ApiApp
 *
 * An Api-based application class for Paulus
 *
 * @uses BasicApp
 * @package OneMightyRoar\PHP_Paulus_Components
 */
class ApiApp extends BasicApp
{

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
     * @access public
     */
    public function __construct(
        $base_path = __DIR__,
        $app_namespace = null,
        array $config = null,
        Router $router = null,
        ServiceLocator $locator = null,
        LoggerInterface $logger = null
    ) {
        // Call our parent constructor
        parent::__construct($base_path, $app_namespace, $config, $router, $locator, $logger);

        // Set our default response
        $this->setDefaultResponse(
            (new ApiResponse())->unlock()
        );
    }
}
