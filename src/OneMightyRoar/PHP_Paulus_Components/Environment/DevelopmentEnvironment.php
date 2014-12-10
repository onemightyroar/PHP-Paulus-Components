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
 * Class DevelopmentEnvironment
 */
class DevelopmentEnvironment implements EnvironmentInterface
{
    /**
     * Constants
     */

    /**
     * @type string The environment's name
     */
    const ENVIRONMENT_NAME = 'development';


    /**
     * Methods
     */

    /**
     * Get the name of the environment
     *
     * @return string
     */
    public function getName()
    {
        return static::ENVIRONMENT_NAME;
    }
}