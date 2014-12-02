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
 * Class AbstractEnvironment
 */
abstract class AbstractEnvironment
{
    /**
     * Methods
     */

    /**
     * Get the name of the environment
     *
     * @return string
     */
    abstract public function getName();
}
