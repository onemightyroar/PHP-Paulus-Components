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

use Paulus\Exception\Http\Standard\Unauthorized;

/**
 * BadCredentials 
 *
 * Authentication failure
 *
 * @uses Paulus\Exception\Http\Standard\Unauthorized
 * @package OneMightyRoar\PHP_Paulus_Components\Exception\Http
 */
class BadCredentials extends Unauthorized
{

    /**
     * Constants
     */

    /**
     * The default exception message
     *
     * @const string
     */
    const DEFAULT_MESSAGE = 'Please check your credentials and try again';


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
