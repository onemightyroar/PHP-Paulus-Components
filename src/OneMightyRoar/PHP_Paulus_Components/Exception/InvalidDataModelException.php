<?php
/**
 * PHP-Paulus-Components
 *
 * Components to enhance Paulus projects to enable quicker, more structured REST API's
 *
 * @copyright   2013 One Mighty Roar
 * @link        http://onemightyroar.com
 */

namespace OneMightyRoar\PHP_Paulus_Components\Exception;

use UnexpectedValueException;

/**
 * InvalidDataModelException
 *
 * An exception for reporting back a more structured and
 * consistent format when a generic data model fails to
 * validate, where "data model" may refer to a request
 * body, a JSON structure, etc.
 *
 * @uses UnexpectedValueException
 * @package OneMightyRoar\PHP_Paulus_Components\Exception
 */
class InvalidDataModelException extends UnexpectedValueException
{

    /**
     * Constants
     */

    /**
     * The default error key for generic error messages
     *
     * @const string
     */
    const DEFAULT_ERROR_KEY = 'generic';

    /**
     * The default exception message
     *
     * @const string
     */
    const DEFAULT_MESSAGE = 'The data model is invalid';


    /**
     * Properties
     */

    /**
     * The exception message
     *
     * @var string
     * @access protected
     */
    protected $message = self::DEFAULT_MESSAGE;

    /**
     * Errors array
     *
     * @var array
     * @access protected
     */
    protected $errors = array();


    /**
     * Methods
     */

    /**
     * Returns the exception's "errors" property/attribute
     *
     * @access public
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets the exception's "errors" property/attribute
     *
     * @param array $errors
     * @access public
     * @return InvalidDataModelException
     */
    public function setErrors(array $errors)
    {
        // Reset our collection
        $this->errors = array();

        // Loop through our passed array
        foreach ($errors as $attribute => $messages) {
            // Add each message array individually
            foreach ((array) $messages as $message) {
                $this->addError($message, $attribute);
            }
        }

        return $this;
    }

    /**
     * Add an error to collection
     *
     * @param string $message
     * @param string $attribute
     * @access public
     * @return InvalidDataModelException
     */
    public function addError($message, $attribute = null)
    {
        if (!is_string($attribute)) {
            $attribute = static::DEFAULT_ERROR_KEY;
        }

        $this->errors[$attribute][] = (string) $message;

        return $this;
    }
}
