<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Exception\Http;

use Paulus\Exception\Http\Standard\InternalServerError;

/**
 * InternalApplicationException
 *
 * Exception representing a fatal internal application error
 *
 * @uses Paulus\Exception\Http\Standard\InternalServerError
 * @package OneMightyRoar\PHP_Paulus_Components\Exception\Http
 */
class InternalApplicationException extends InternalServerError
{

    /**
     * Constants
     */

    /**
     * The default exception message
     *
     * @const string
     */
    const DEFAULT_MESSAGE = 'Something went wrong. Please try again later.';


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
}
