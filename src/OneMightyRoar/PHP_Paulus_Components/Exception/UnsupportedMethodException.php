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

use \LogicException;

/**
 * UnsupportedMethodException 
 *
 * @uses \LogicException
 * @package OneMightyRoar\PHP_Paulus_Components\Exceptions
 */
class UnsupportedMethodException extends LogicException {

	// Default properties
	protected $code = 500;
	protected $message = 'Unsupported method call';

} // End class UnsupportedMethodException
