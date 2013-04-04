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

/**
 * NotImplementedException 
 *
 * @uses OneMightyRoar\PHP_Paulus_Components\Exceptions\UnsupportedMethodException
 * @package OneMightyRoar\PHP_Paulus_Components\Exceptions
 */
class NotImplementedException extends UnsupportedMethodException {

	// Default properties
	protected $message = 'Method or function is not (yet) implemented';

} // End class NotImplementedException
