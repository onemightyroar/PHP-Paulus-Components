<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Controller;

use ActiveRecord\DatabaseException;
use ActiveRecord\RecordNotFound;
use Exception;
use Klein\AbstractResponse;
use Klein\Request;
use Klein\ServiceProvider;
use OneMightyRoar\PHP_ActiveRecord_Components\Exceptions\ActiveRecordValidationException;
use OneMightyRoar\PHP_ActiveRecord_Components\ModelInterface;
use OneMightyRoar\PHP_Paulus_Components\Exception\Http\AuthenticationRequired;
use OneMightyRoar\PHP_Paulus_Components\Exception\Http\BadCredentials;
use OneMightyRoar\PHP_Paulus_Components\Exception\Http\DatabaseConnectionException;
use OneMightyRoar\PHP_Paulus_Components\Exception\Http\HTTPBasicUnauthorized;
use OneMightyRoar\PHP_Paulus_Components\Exception\InvalidDataModelException;
use Paulus\Controller\AbstractController;
use Paulus\Exception\Http\InvalidParameters;
use Paulus\Exception\Http\ObjectNotFound;
use Paulus\Paulus;
use Paulus\Response\ApiResponse;
use Paulus\Response\PagedApiResponse;
use Paulus\Router;
use Predis\Connection\ConnectionException as RedisConnectionException;

/**
 * Api
 *
 * Main API controller that other route
 * based controllers should extend
 *
 * @package OneMightyRoar\PHP_Paulus_Components\Controller
 */
class Api extends AbstractController
{

    /**
     * Properties
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
     * Constructor
     *
     * @see AbstractController::__construct()
     * @param Request $request
     * @param AbstractResponse $response
     * @param ServiceProvider $service
     * @param Paulus $app
     * @param Router $router
     * @access public
     */
    public function __construct(
        Request $request,
        AbstractResponse $response,
        ServiceProvider $service,
        Paulus $app,
        Router $router
    ) {
        parent::__construct($request, $response, $service, $app, $router);

        // Initializers
        $this->initPhpAuth();
    }

    /**
     * Initialize our PHP Authentication properties
     *
     * Give special, clean access to PHP's authentication credentials
     * so we don't have to always access the $_SERVER array
     *
     * @final
     * @access private
     * @return object
     */
    final private function initPhpAuth()
    {
        $server = $this->request->server();

        return $this->php_auth = (object) [
            'username'  => $server->get('PHP_AUTH_USER'),
            'password'  => $server->get('PHP_AUTH_PW'),
            'type'      => $server->get('AUTH_TYPE'),
            'digest'    => $server->get('PHP_AUTH_DIGEST'),
        ];
    }

    /**
     * Get our PHP Authentication object that holds our HTTP authentication data
     *
     * @final
     * @access public
     * @return object
     */
    final public function getPhpAuth()
    {
        return $this->php_auth;
    }

    /**
     * Easily force HTTP Basic Authentication for a service/route/etc
     * 
     * Simply checks to see if a username or password was passed in
     * the request via our initialized HTTP Basic params
     * 
     * @access public
     * @throws \OneMightyRoar\PHP_Paulus_Components\Exception\Http\HTTPBasicUnauthorized
     * @return void
     */
    public function requireHttpBasicAuth()
    {
        // If authentication data was sent
        if (is_null($this->getPhpAuth()->username) || is_null($this->getPhpAuth()->password)) {
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
    public function prepareModelData($data)
    {
        if (is_array($data)) {
            foreach ($data as &$entry) {
                $entry = $this->prepareModelData($entry);
            }
        } elseif ($data instanceof ModelInterface) {
            $data = $data->getProfile();
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function handleResult($result_data)
    {
        // Ooo, we're a paged response?!
        if ($result_data instanceof PagedApiResponse) {
            // Build a resources object for paging links/refs
            if ($this->show_paging_resources && false) { // TODO: Fix this flow based on request variables...
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

            return $result_data;
        }

        return parent::handleResult($result_data);
    }

    /**
     * {@inheritdoc}
     */
    public function handleException($exception)
    {
        // Let's do different things, based on the type of the error
        if ($exception instanceof ActiveRecordValidationException) {
            // Grab our validation errors from our exception
            $error_data = $exception->get_errors(true);

            $exception = InvalidParameters::create(null, null, $exception);
            $exception->setMoreInfo($error_data);

        } elseif ($exception instanceof InvalidDataModelException) {
            // Grab our validation errors from our exception
            $error_data = $exception->getErrors();

            $exception = InvalidParameters::create(null, null, $exception);
            $exception->setMoreInfo($error_data);

        } elseif ($exception instanceof RedisConnectionException) {
            $exception = DatabaseConnectionException::create(null, null, $exception);

        } elseif ($exception instanceof DatabaseException) {
            $exception = DatabaseConnectionException::create(null, null, $exception);

        } elseif ($exception instanceof RecordNotFound) {
            $exception = ObjectNotFound::create(null, null, $exception);

        } elseif ($exception instanceof HTTPBasicUnauthorized) {
            // Grab our "realm"
            $realm = $this->app()->config()['app-meta']['app_url'];

            // Tell the device/consumer that they must pass auth data
            $this->response->header('WWW-Authenticate', 'Basic realm="' . $realm . '"');

        }

        // Any other exceptions
        parent::handleException($exception);
    }
}
