<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Response;

/**
 * ApiResponse
 *
 * @package OneMightyRoar\PHP_Paulus_Components\Response
 */
class ApiResponse {

	/**
	 * The actual response data
	 * 
	 * @var object | array
	 * @access protected
	 */
	protected $data;

	/**
	 * The status code of the HTTP RESTful Api Response
	 * 
	 * @var int
	 * @access protected
	 */
	protected $code;

	/**
	 * The status "slug" of the response 
	 * 
	 * @var string
	 * @access protected
	 */
	protected $status;

	/**
	 * The human readable message of the response
	 * 
	 * @var string
	 * @access protected
	 */
	protected $message;

	/**
	 * The object containing meta information
	 * ( usually regarding some sort of error )
	 * 
	 * @var object
	 * @access protected
	 */
	protected $more_info;


	/**
	 * Constructor
	 *
	 * @param mixed $data 
	 * @param int $code 
	 * @param string $status 
	 * @param string $message 
	 * @param object $more_info 
	 * @access public
	 * @return void
	 */
	public function __construct( $data = null, $code = null, $status = null, $message = null, $more_info = null ) {
		/**
		 * Set our object's properties based on our passed in data
		 * Use our setter methods, so we can overwrite how this works,
		 * without having to redefine our entire constructor for each sub-class
		 */
		!is_null( $data ) && $this->set_data( $data );
		!is_null( $code ) && $this->set_code( $code );
		!is_null( $status ) && $this->set_status( $status );
		!is_null( $message ) && $this->set_message( $message );
		!is_null( $more_info ) && $this->set_more_info( $more_info );
	}

	/**
	 * @access public
	 * @return object | array
	 */
	public function get_data() {
		return $this->data;
	}

	/**
	 * @access public
	 * @return int
	 */
	public function get_code() {
		return $this->code;
	}

	/**
	 * @access public
	 * @return string
	 */
	public function get_status() {
		return $this->status;
	}

	/**
	 * @access public
	 * @return string
	 */
	public function get_message() {
		return $this->message;
	}

	/**
	 * @access public
	 * @return object
	 */
	public function get_more_info() {
		return $this->more_info;
	}

	/**
	 * @param object | array $data
	 * @access public
	 * @return ApiResponse
	 */
	public function set_data( $data ) {
		if (is_array( $data ) && static::is_hash( $data )) {
			$data = (object) $data;
		}

		$this->data = $data;
		return $this;
	}

	/**
	 * @param int $code
	 * @access public
	 * @return ApiResponse
	 */
	public function set_code( $code ) {
		$this->code = (int) $code;
		return $this;
	}

	/**
	 * @param string $status
	 * @access public
	 * @return ApiResponse
	 */
	public function set_status( $status ) {
		$this->status = (string) $status;
		return $this;
	}

	/**
	 * @param string $message
	 * @access public
	 * @return ApiResponse
	 */
	public function set_message( $message ) {
		$this->message = (string) $message;
		return $this;
	}

	/**
	 * @param object $more_info
	 * @access public
	 * @return ApiResponse
	 */
	public function set_more_info( $more_info ) {
		$this->more_info = (object) $more_info;
		return $this;
	}

	/**
	 * Easy method to determine if the array contains any non-integer keys
	 *
	 * @param array $array
	 * @static
	 * @access public
	 * @return boolean
	 */
	public static function is_hash( array $array ) {
		return (bool)count(array_filter(array_keys($array), 'is_string'));
	}

} // End class ApiResponse
