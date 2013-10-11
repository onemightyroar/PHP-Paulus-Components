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

use Paulus\Exception\Http\Standard\BadGateway;

/**
 * DatabaseConnectionException
 *
 * Authentication failure
 *
 * @uses Paulus\Exception\Http\Standard\BadGateway
 * @package OneMightyRoar\PHP_Paulus_Components\Exception\Http
 */
class DatabaseConnectionException extends BadGateway
{

    /**
     * The exception message
     *
     * @var string
     * @access protected
     */
    protected $message = 'There was an error connecting to or retrieving from the Database';
}
