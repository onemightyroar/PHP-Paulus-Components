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
 * Class CustomEnvironment
 */
class CustomEnvironment extends AbstractEnvironment
{
    /**
     * Properties
     */

    /**
     * @type string The environment's name
     */
    protected $environment_name;


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
        return $this->$environment_name;
    }

    /**
     * Set the name of the environment
     *
     * @param $name
     * @return CustomEnvironment
     */
    public function setName($name)
    {
        $this->$environment_name = $name;

        return $this;
    }
}
