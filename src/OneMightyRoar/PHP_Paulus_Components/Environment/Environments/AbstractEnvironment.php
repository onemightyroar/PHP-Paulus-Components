<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2014 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Environment\Environments;

/**
 * Class AbstractEnvironment
 */
abstract class AbstractEnvironment
{
    /**
     * Constants
     */

    /**
     * @type string The environment's name
     */
    const ENVIRONMENT_NAME = '';


    /**
     * Methods
     */

    /**
     * Magic string conversion
     *
     * @return string
     */
    public function __toString()
    {
        return static::ENVIRONMENT_NAME;
    }
}
