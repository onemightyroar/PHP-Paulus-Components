<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Exception;

/**
 * NotImplementedException
 *
 * @uses OneMightyRoar\PHP_Paulus_Components\Exception\UnsupportedMethodException
 * @package OneMightyRoar\PHP_Paulus_Components\Exception
 */
class NotImplementedException extends UnsupportedMethodException
{

    /**
     * The exception message
     *
     * @var string
     * @access protected
     */
    protected $message = 'Method or function is not (yet) implemented';
}