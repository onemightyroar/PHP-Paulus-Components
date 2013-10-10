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
 * HTTPBasicUnauthorized 
 *
 * HTTP Basic authentication required
 *
 * @uses Paulus\Exception\Http\Standard\Unauthorized
 * @package OneMightyRoar\PHP_Paulus_Components\Exception\Http
 */
class HTTPBasicUnauthorized extends Unauthorized
{

    /**
     * The exception message
     *
     * @var string
     * @access protected
     */
    protected $message = '';
}
