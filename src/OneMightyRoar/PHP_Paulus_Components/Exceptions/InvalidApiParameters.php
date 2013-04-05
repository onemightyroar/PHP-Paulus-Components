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

use \Paulus\Exceptions\InvalidApiParameters as PaulusInvalidApiParameters;
use \Paulus\Exceptions\Interfaces\ApiVerboseException;
use \Paulus\Exceptions\Traits\ApiVerboseExceptionBase;

/**
 * InvalidApiParameters
 *
 * InvalidApiParameters Exception that uses an ApiVerboseException
 *
 * @uses \Paulus\Exceptions\InvalidApiParameters
 * @uses \Paulus\Exceptions\Interfaces\ApiVerboseException
 * @uses \Paulus\Exceptions\Traits\ApiVerboseExceptionBase
 * @package OneMightyRoar\PHP_Paulus_Components\Exceptions
 */
class InvalidApiParameters extends PaulusInvalidApiParameters implements ApiVerboseException {

	// Use trait
	use ApiVerboseExceptionBase;

	// Define default
	protected $more_info = null;

} // End class InvalidApiParameters
