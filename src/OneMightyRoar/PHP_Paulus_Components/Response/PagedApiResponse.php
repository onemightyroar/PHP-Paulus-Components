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
 * PagedApiResponse
 *
 * @package OneMightyRoar\PHP_Paulus_Components\Response
 */
class PagedApiResponse extends ApiResponse {

    /**
	 * The current page being displayed
     * 
     * @var int
     * @access protected
     */
	protected $page	= 1;

	/**
	 * The number of results returned per page
	 * 
	 * @var int
	 * @access protected
	 */
	protected $per_page = 10;

	/**
	 * Whether or not the response has a "next" page or not
	 * 
	 * @var boolean
	 * @access protected
	 */
	protected $has_next_page = false;

    /**
	 * The direction of which to order our data response
	 * Whether or not to show our result in "descending" order
     * 
     * @var boolean
     * @access protected
     */
    protected $order_descending = false;

    /**
	 * The "criteria" of which to order by
     * 
     * @var mixed
     * @access protected
     */
    protected $order_by;


	/**
	 * Get the paging data in a nice object format
	 * 
	 * @access public
	 * @return object The paging data in a nice collected object
	 */
	public function get_formatted_paging_data() {
		// Return an object representing the paging data
		return (object) array(
			'page' => $this->get_page(),
			'per_page' => $this->get_per_page(),
			'order_descending' => $this->get_order_descending(),
		);
	}

	/**
	 * @access public
	 * @return int
	 */
	public function get_page() {
		return $this->page;
	}

	/**
	 * @access public
	 * @return int
	 */
	public function get_per_page() {
		return $this->per_page;
	}

	/**
	 * @access public
	 * @return boolean
	 */
	public function get_has_next_page() {
		return $this->has_next_page;
	}

	/**
	 * @access public
	 * @return boolean
	 */
	public function get_order_descending() {
		return $this->order_descending;
	}

	/**
	 * @access public
	 * @return mixed
	 */
	public function get_order_by() {
		return $this->order_by;
	}

	/**
	 * @param int $page
	 * @access public
	 * @return PagedApiResponse
	 */
	public function set_page( $page ) {
		$this->page = (int) $page;
		return $this;
	}

	/**
	 * @param int $per_page
	 * @access public
	 * @return PagedApiResponse
	 */
	public function set_per_page( $per_page ) {
		$this->per_page = (int) $per_page;
		return $this;
	}

	/**
	 * @param boolean $has_next_page 
	 * @access public
	 * @return PagedApiResponse
	 */
	public function set_has_next_page( $has_next_page ) {
		$this->has_next_page = (bool) $has_next_page;
		return $this;
	}

	/**
	 * @param boolean $order_descending 
	 * @access public
	 * @return PagedApiResponse
	 */
	public function set_order_descending( $order_descending ) {
		$this->order_descending = (bool) $order_descending;
		return $this;
	}

	/**
	 * @param mixed $order_by 
	 * @access public
	 * @return PagedApiResponse
	 */
	public function set_order_by( $order_by ) {
		$this->order_by = $order_by;
		return $this;
	}

} // End class PagedApiResponse
