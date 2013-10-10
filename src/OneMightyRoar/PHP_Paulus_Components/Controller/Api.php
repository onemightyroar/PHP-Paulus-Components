<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Controllers;

use \Paulus\BaseController;
use \Paulus\Exceptions\ObjectNotFound;

use \OneMightyRoar\PHP_Paulus_Components\Response\ApiResponse;
use \OneMightyRoar\PHP_Paulus_Components\Response\PagedApiResponse;
use \OneMightyRoar\PHP_Paulus_Components\Exceptions\InvalidApiParameters;
use \OneMightyRoar\PHP_Paulus_Components\Exceptions\HTTPBasicUnauthorized;
use \OneMightyRoar\PHP_Paulus_Components\Exceptions\BadCredentials;
use \OneMightyRoar\PHP_Paulus_Components\Exceptions\AuthenticationRequired;

use \OneMightyRoar\PHP_ActiveRecord_Components\ModelInterface;
use \OneMightyRoar\PHP_ActiveRecord_Components\Exceptions\ActiveRecordValidationException;

use \Predis\Connection\ConnectionException as RedisConnectionException;

/**
 * Api
 *
 * Main API controller that other route
 * based controllers should extend
 *
 * @package OneMightyRoar\PHP_Paulus_Components\Controllers
 */
class Api extends BaseController {

	/**
	 * Class properties
	 */

	/**
	 * @var object
	 * @access private
	 */
	private $php_auth;

	/**
	 * @var mixed
	 * @access public
	 */
	public $show_paging_resources = true;


	/**
	 * Methods
	 */

	/**
	 * API Controller constructor
	 *
	 * @param object $request           The router's request object
	 * @param object $response          The router's response object
	 * @param object $service           The router's service object
	 * @param object $app               Reference to the current application context
	 * @access public
	 * @return Api
	 */
	public function __construct( $request, $response, $service, $app ) {
		parent::__construct( $request, $response, $service, $app );

		// Initializers
		$this->init_php_auth();
	}

	/**
	 * Read our authorization header and return its parts
	 *
	 * Returns an object that contains 3 properties:
	 * type => The type of the authorization
	 * string => The string of the authorization that follows the type
	 * params => The split params of the authorization header
	 *
	 * @access protected
	 * @return object
	 */
	protected function read_auth_header() {
		$auth_header = isset( $_SERVER['HTTP_AUTHORIZATION'] ) ? $_SERVER['HTTP_AUTHORIZATION'] : null;

		if ( !is_null( $auth_header ) ) {
			// Grab our type
			$auth_header_type = explode( ' ', $auth_header )[0];

			// Grab our string, and do some string processing
			$auth_header_string = str_replace(
				"\r\n",
				'',
				trim( substr( $auth_header, strlen( $auth_header_type ) ) )
			);

			// Process our string into params
			$auth_header_params = array();
			// Regex source: http://oauth.googlecode.com/svn/code/php/OAuth.php OAuthUtil::split_header()
			if ( preg_match_all( '/([a-z_-]*)=(:?"([^"]*)"|([^,]*))/', $auth_header_string, $params ) ) {
				foreach( $params[1] as $key => $val ) {
					$auth_header_params[ $val ] = trim( $params[2][ $key ], '"' );
				}
			}

			// Combine our parts
			$auth_header_parts = (object) array(
				'type' => $auth_header_type,
				'string' => $auth_header_string,
				'params' => $auth_header_params,
			);

			return $auth_header_parts;
		}

		return false;
	}


	/**
	 * Initializers
	 */

	/**
	 * Initialize our PHP Authentication properties
	 *
	 * Give special, clean access to PHP's authentication credentials
	 * so we don't have to always access the $_SERVER array
	 * 
	 * @final
	 * @access private
	 * @return void
	 */
	final private function init_php_auth() {
		return $this->php_auth = (object) array(
			'username'  => isset( $_SERVER['PHP_AUTH_USER'] )   ? $_SERVER['PHP_AUTH_USER'] : null,
			'password'  => isset( $_SERVER['PHP_AUTH_PW'] )     ? $_SERVER['PHP_AUTH_PW'] : null,
			'type'      => isset( $_SERVER['AUTH_TYPE'] )       ? $_SERVER['AUTH_TYPE'] : null,
			'digest'    => isset( $_SERVER['PHP_AUTH_DIGEST'] ) ? $_SERVER['PHP_AUTH_DIGEST'] : null,
		);
	}


	/**
	 * Getters
	 */

	/**
	 * Get our PHP Authentication object that holds our HTTP authentication data
	 *
	 * @final
	 * @access public
	 * @return object
	 */
	final public function get_php_auth() {
		return $this->php_auth;
	}


	/**
	 * Easily force HTTP Basic Authentication for a service/route/etc
	 * 
	 * Simply checks to see if a username or password was passed in
	 * the request via our initialized HTTP Basic params
	 * 
	 * @access public
	 * @throws \OneMightyRoar\PHP_Paulus_Components\Exceptions\HTTPBasicUnauthorized
	 * @return void
	 */
	public function require_http_basic_auth() {
		// If authentication data was sent
		if ( is_null( $this->get_php_auth()->username ) || is_null( $this->get_php_auth()->password ) ) {
			throw new HTTPBasicUnauthorized();
		}

		return true;
	}

	/**
	 * Prepare any raw model data as a controller response
	 *
	 * @param array|ModelInterface $data
	 * @access public
	 * @return object|array The normalized data
	 */
	public function prepare_model_data($data)
	{
		if (is_array($data)) {
			foreach ($data as &$entry) {
				$entry = $this->prepare_model_data($entry);
			}
		} elseif ($data instanceof ModelInterface) {
			$data = $data->getProfile();
		}

		return $data;
	}

	/**
	 * route_respond
	 *
	 * Route responder for filtering routes directed through a controller.
	 *
	 * Adds in the paging data if we're returning an array
	 *
	 * @param mixed $result_data
	 * @access public
	 * @return void
	 * @see \Paulus\BaseController::__construct()
	 */
	public function route_respond( $result_data ) {
		// Logic depends on the contents/state of result_data
		if ( !is_null( $result_data ) ) {
			// Check if we got an instance of one of our Api response classes
			if ( $result_data instanceof ApiResponse ) {
				$this->response->code( $result_data->get_code() );
				$this->response->status = $result_data->get_status();
				$this->response->message = $result_data->get_message();
				$this->response->more_info = $result_data->get_more_info();
				$this->response->data = $result_data->get_data();

				foreach( $result_data->get_headers() as $name => $value ) {
					$this->response->header( $name, $value );
				}

				// Ooo, we're a paged response?!
				if ( $result_data instanceof PagedApiResponse ) {
					// Add the paging data to our response data
					$this->response->paging = $result_data->get_formatted_paging_data();

					// Build a resources object for paging links/refs
					if ($this->show_paging_resources) {
						$this->response->paging->resources = (object) array();

						if ($result_data->get_has_next_page()) {
							// Merge our next page number into our query vars
							$query_vars = array_merge(
								$_GET,
								array(
									'page' => ($this->response->paging->page + 1)
								)
							);

							$this->response->paging->resources->next = $this->app->parse(
								'{APP_URL}{ENDPOINT}?'
								. http_build_query($query_vars)
							);
						}

						if ($this->response->paging->page > 1) {
							// Merge our next page number into our query vars
							$query_vars = array_merge(
								$_GET,
								array(
									'page' => ($this->response->paging->page - 1)
								)
							);

							$this->response->paging->resources->previous = $this->app->parse(
								'{APP_URL}{ENDPOINT}?'
								. http_build_query($query_vars)
							);
						}
					}
				}
			}
			// True response
			elseif ( $result_data === true ) {
				// True case WITHOUT any returned data
			}
			elseif ( $result_data === false ) {
				// Throw an exception
				throw new InvalidApiParameters();
			}
		}
		else {
			// The response is null, throw an exception
			throw new ObjectNotFound();
		}

	}

	/**
	 * exception_handler
	 *
	 * Function to handle exceptions from the API
	 *
	 * @param string $error_message	The error message of the exception
	 * @param string $error_type	The exception class
	 * @param Exception $exception	The actual exception object itself
	 * @access public
	 * @return void
	 */
	public function exception_handler( $error_message, $error_type, $exception ) {

		// Let's do different things, based on the type of the error

		if ( $exception instanceof ActiveRecordValidationException ) {
			// Grab our validation errors from our exception
			$error_data = $exception->get_errors( true );

			$verbose_exception = new InvalidApiParameters( $exception->getMessage(), null, $exception );
			$verbose_exception->set_more_info( $error_data );

			// Handle the rest with our parent. :)
			parent::exception_handler(
				$verbose_exception->getMessage(),
				get_class( $verbose_exception ),
				$verbose_exception
			);
		}
		elseif ( $exception instanceof RedisConnectionException ) {
			// Let's handle the exception gracefully
			$this->app->abort( 502, 'REDIS_EXCEPTION', $exception->getMessage() );
		}
		elseif ( $exception instanceof HTTPBasicUnauthorized ) {
			// Grab our "realm"
			$realm = $this->config[ 'app-meta' ][ 'app_url' ];

			// Tell the device/consumer that they must pass auth data
			$this->response->header( 'WWW-Authenticate', 'Basic realm="' . $realm . '"' );

			// Handle the rest with our parent. :)
			parent::exception_handler( $error_message, $error_type, $exception );
		}
		// Any other exceptions
		else {
			parent::exception_handler( $error_message, $error_type, $exception );
		}
	}

} // End class Api
