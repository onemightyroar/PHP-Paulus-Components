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
     * @return EnvironmentInterface
     */
    public static function createFromString($environment = null)
    {
        // First check if we passed in an environment string, if not try to get the current environment variable
        $environment_string = $environment ?: getenv('ENVIRONMENT');
        // If there isn't an environment variable available, fall back to our default environment
        $environment_string = $environment_string ?: static::DEFAULT_ENVIRONMENT;

        switch (strtolower($environment_string)) {
            case DevelopmentEnvironment::ENVIRONMENT_NAME:
                $environment = new DevelopmentEnvironment();
                break;
            case StagingEnvironment::ENVIRONMENT_NAME:
                $environment = new StagingEnvironment();
                break;
            case ProductionEnvironment::ENVIRONMENT_NAME:
                $environment = new ProductionEnvironment();
                break;
            default:
                $environment = new UnknownEnvironment();
        }

        return $environment;
    }
}
