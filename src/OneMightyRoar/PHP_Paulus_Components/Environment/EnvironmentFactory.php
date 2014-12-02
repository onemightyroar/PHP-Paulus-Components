<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2014 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Environment;

use OneMightyRoar\PHP_Paulus_Components\Environment\Environments\CustomEnvironment;
use OneMightyRoar\PHP_Paulus_Components\Environment\Environments\DevelopmentEnvironment;
use OneMightyRoar\PHP_Paulus_Components\Environment\Environments\ProductionEnvironment;
use OneMightyRoar\PHP_Paulus_Components\Environment\Environments\StagingEnvironment;

/**
 * Class EnvironmentFactory
 *
 * A factory class to build an environment class based on the current setup
 */
class EnvironmentFactory
{
    /**
     * Constants
     */

    /**
     * @type string The default environment to build
     */
    const DEFAULT_ENVIRONMENT = DevelopmentEnvironment::ENVIRONMENT_NAME;


    /**
     * Methods
     */

    /**
     * Factory create method
     *
     * Strictly build an environment based on the passed argument, the current environment, or a default one
     *
     * @param string $environment {optional} The specific environment to build
     * @return CustomEnvironment|DevelopmentEnvironment|ProductionEnvironment|StagingEnvironment
     */
    public static function create($environment = null)
    {
        // First check if we passed in an environment string, if not try to get the current environment variable
        $environment_string = $environment ?: getenv('ENVIRONMENT');
        // If there isn't an environment variable available, fall back to our default environment
        $environment_string = $environment_string ?: static::DEFAULT_ENVIRONMENT;

        if (DevelopmentEnvironment::ENVIRONMENT_NAME === $environment_string) {
            $environment = new DevelopmentEnvironment();
        } elseif (StagingEnvironment::ENVIRONMENT_NAME === $environment_string) {
            $environment = new StagingEnvironment();
        } elseif (ProductionEnvironment::ENVIRONMENT_NAME === $environment_string) {
            $environment = new ProductionEnvironment();
        } else {
            $environment = new CustomEnvironment();
        }

        return $environment;
    }
}
