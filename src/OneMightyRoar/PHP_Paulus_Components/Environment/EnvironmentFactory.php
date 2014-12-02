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

class EnvironmentFactory
{
    const DEFAULT_ENVIRONMENT = 'development';

    public static function create($environment = null)
    {
        $environment_string = getenv('ENVIRONMENT') ?: static::DEFAULT_ENVIRONMENT;
    }
}
