<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\DataCollection;

use Klein\DataCollection\DataCollection;
use LogicException;

/**
 * ImmutableDataCollection
 *
 * An extension of the Klein DataCollection
 * class that is immutable
 *
 * @uses    Klein\DataCollection\DataCollection
 * @package OneMightyRoar\PHP_Paulus_Components\DataCollection
 */
class ImmutableDataCollection extends DataCollection
{

    /**
     * Constants
     */

    /**
     * The message to enter to the exceptions
     *
     * @const string
     */
    const IMMUTABLE_MESSAGE = 'Illegal operation. Attempt to modify immutable collection.';


    /**
     * Methods
     */

    /**
     * Override and remove the set functionality
     *
     * @override (does not call parent)
     * @param string $key
     * @param mixed $value
     * @throws LogicException
     * @access public
     * @return void
     */
    public function set($key, $value)
    {
        throw new LogicException(self::IMMUTABLE_MESSAGE);
    }

    /**
     * Override and remove the replace functionality
     *
     * @override (does not call parent)
     * @param array $attributes
     * @throws LogicException
     * @access public
     * @return void
     */
    public function replace(array $attributes = [])
    {
        throw new LogicException(self::IMMUTABLE_MESSAGE);
    }

    /**
     * Override and remove the merge functionality
     *
     * @override (does not call parent)
     * @param array $attributes
     * @param boolean $hard
     * @throws LogicException
     * @access public
     * @return ServiceLocator
     */
    public function merge(array $attributes = [], $hard = false)
    {
        throw new LogicException(self::IMMUTABLE_MESSAGE);
    }

    /**
     * Override and remove the remove functionality
     *
     * @override (does not call parent)
     * @param array $key
     * @throws LogicException
     * @access public
     * @return void
     */
    public function remove($key)
    {
        throw new LogicException(self::IMMUTABLE_MESSAGE);
    }
}
