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

abstract class AbstractEnvironment
{
    const ENVIRONMENT_NAME = '';

    public static function __toString()
    {
        return static::ENVIRONMENT_NAME;
    }
}
