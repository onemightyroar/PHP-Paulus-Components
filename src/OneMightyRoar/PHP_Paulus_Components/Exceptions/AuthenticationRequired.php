<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Exceptions;

use \Paulus\Exceptions\Unauthorized;
use \Paulus\Exceptions\Interfaces\ApiException;

/**
 * AuthenticationRequired
 *
 * @uses \Paulus\Exceptions\Unauthorized
 * @uses \Paulus\Exceptions\Interfaces\ApiException
 * @package OneMightyRoar\PHP_Paulus_Components\Exceptions
 */
class AuthenticationRequired extends Unauthorized implements ApiException {

	// Define default
	protected $message = 'A required access token was not sent';

} // End class AuthenticationRequired
