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

use LogicException;

/**
 * UnsupportedMethodException
 *
 * @uses LogicException
 * @package OneMightyRoar\PHP_Paulus_Components\Exception
 */
class UnsupportedMethodException extends LogicException
{

    /**
     * Constants
     */

    /**
     * The default exception message
     *
     * @const string
     */
    const DEFAULT_MESSAGE = 'Unsupported method call';

    /**
     * The default exception code
     *
     * @const int
     */
    const DEFAULT_CODE = 500;


    /**
     * Properties
     */

    /**
     * The exception message
     *
     * @var string
     * @access protected
     */
    protected $message = self::DEFAULT_MESSAGE;

    /**
     * The exception and response code
     *
     * @var int
     * @access protected
     */
    protected $code = self::DEFAULT_CODE;
}
