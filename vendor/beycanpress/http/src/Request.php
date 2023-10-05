<?php

namespace BeycanPress\Http;

final class Request
{
    /**
     * It contains this class.
     * @var self
     */
    private static $instance = null;

    /**
     * The variable where the request type is kept
     * @var string
     */
    private $method;

    /**
     * The variable that keeps all errors that occur during the class work process
     * @var array
     */
    private $errors = [];

    /**
     * The variable in which all parameters are kept.
     * @var array
     */
    private $params = [];

    /**
     * PHP contains the data sent with the "GET" method.
     * @var array
     */
    private $getParams = [];

    /**
     * PHP contains the data sent with the "POST" method.
     * @var array
     */
    private $postParams = [];

    /**
     *  "$_FILES" contains the data in the variable.
     * @var array
     */
    private $filesParams = [];

    /**
     * Methods to run when it is started class.
     * @return void
     */
    public function __construct()
    {
        $this->checkRequests();
        $this->parseContent();
        $this->method = $_SERVER['REQUEST_METHOD'];
    }

    # public methods #

    /**
     * To access and use the class in a static format
     * @return Request
     */
    public static function init() : Request
    {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Request;
        }
        return self::$instance;
    }

    /**
     * Allows getting the type of request.
     * @return string
     */
    public function getMethod() : string
    {
        return $this->method;
    }

    /**
     * It allows to receive errors that occur in the classroom.
     * @return array
     */
    public function getErrors() : array
    {
        return $this->errors;
    }
    
    /**
     * It enables the data received by "POST" method to be received by key or collectively.
     * @param string $key
     * @return mixed
     */
    public function post(string $key) 
    {
        $this->params = $this->postParams;
        return $this->getWithKey($key);
    }

    /**
     * It enables the data received by "GET" method to be received by key or collectively.
     * @param string $key
     * @return mixed
     */
    public function get(string $key) 
    {
        $this->params = $this->getParams;
        return $this->getWithKey($key);
    }

    /**
     * It allows us to retrieve the files sent from the html form, ie the data in the "$_FILES" variable.
     * @param string $key
     * @return mixed
     */
    public function files(string $key) 
    {
        $this->params = $this->filesParams;
        return $this->getWithKey($key);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function getParam(string $key) 
    {
        return $this->getWithKey($key);
    }

    /**
     * Allows you to retrieve all data from the request body or other methods.
     * @return array
     */
    public function getAllParams() : array
    {
        return $this->params;
    }


    /**
     * Allows you to get request headers.
     * @param string $key
     * @return string|null
     */
    public function getHeaderParam(string $key) : ?string
    {
        $headers = $this->getAllHeaderParams();
        if ( is_null( $key ) ) {
            return $headers;
        } else {
            if ( isset( $headers ) ) {
                $headers[$key];
            } else {
                $this->errors[] = array(
                    'HEADERS Error' => 'Key not found in headers'
                );
                return null;
            }
        }
    }

    /**
     * Allows you to get request headers.
     * @return array
     */
    public function getAllHeaderParams() : array
    {
        return getallheaders();
    }

    /**
     * If the data in the request body is json data, it allows you to get it easily.
     * @param string $key
     * @return mixed
     */
    public function json(string $key) 
    {
        if ( $this->isJson() ) {
            $this->params = json_decode($this->getContent(), true);
            return $this->getWithKey($key);
        } else {
            $this->errors[] = array(
                'JSON Error' => json_last_error_msg()
            );
            return false;
        }
    }

    /**
     * If the data in the request body is xml data, it allows you to get it easily.
     * @param string $key
     * @return mixed
     */
    public function xml(string $key) 
    {
        if ( $result = $this->xmlParse() ) {
            $this->params = $result;
            return $this->getWithKey($key);
        } else {
            return false;
        }
    }

    /**
     * It allows you to get the data in the request body.
     * @return string
     */
    public function getContent() : string
    {
        return file_get_contents('php://input');
    }

    # private methods #

    /**
     * If a "key" parameter is sent from the methods used to get data, 
     * it is processed here and the output is decided here.
     * @param string $key
     * @return array|mixed
     */
    private function getWithKey(?string $key = null) 
    {
        if (!is_null($key)) {
            if (is_array($this->params)) {
                if (isset($this->params[$key])) {
                    return $this->params[$key];
                } else {
                    $this->errors[] = array(
                        'GENERAL Error' => 'There is no data with the key value you entered.'
                    );
                    return null;
                }
            } else {
                if (isset($this->params->$key)) {
                    return $this->params->$key;
                } else {
                    $this->errors[] = array(
                        'GEENERAL Error' => 'There is no data with the key value you entered.'
                    );
                    return null;
                }
            }
        } else {
            return $this->params;
        }
    }

    /**
     * Checks if a string data is json data.
     * 
     * @return bool
     */
    private function isJson() : bool
    {
        json_decode($this->getContent());
        return (json_last_error() === 0 ? true : false);
    }

    /**
     * Checks if a string data is query string data.
     * 
     * @return bool
     */
    private function isQueryString() : bool
    {
        preg_match('/([a-zA-Z0-9]+)=([a-zA-Z0-9]+)/i', $this->getContent(), $matches);
        return empty($matches) ? false : true;
    }

    /**
     * Checks if a string data is form data.
     * 
     * @return bool
     */
    private function isFormData() : bool
    {
        return strpos($this->getContent(), 'form-data') !== false ? true : false;
    }

    /**
     * Converts the form data string to an array.
     * @return array
     */
    private function parseFormData() : array
    {
        $pattern = '/name="(.*)"\r\n\r\n(.*)\r\n/';

        preg_match_all($pattern, $this->getContent(), $matches, PREG_SET_ORDER, 0);

        $params = [];

        foreach ($matches as $value) {
            unset($value[0]);
            $params[$value[1]] = $value[2];
        }

        return $params;
    }

    /**
     * Converts the xml data string to an array.
     * 
     * @return array
     */
    private function xmlParse() : array
    {
        if (function_exists('libxml_use_internal_errors') === false) {
            $this->errors[] = array(
                'XML Error' => 'libxml_use_internal_errors function not found.'
            );
            return [];
        }
        $saved = libxml_use_internal_errors(true);
        $xml = @simplexml_load_string($this->getContent());
        if ($xml) {
            $json = json_encode($xml);
            return json_decode($json, true);
        } else {
            $this->errors[] = array(
                'XML Error' => libxml_get_errors()
            );
            libxml_use_internal_errors($saved);
            return [];
        }
    }

    /**
     * It checks the data in the request body and runs the method that will convert it to an array if known data.
     * @return void
     */
    private function parseContent() : void
    {
        if ($this->isFormData()) {
            $this->params = array_merge($this->params, $this->parseFormData());
        } 
        
        if ($this->isQueryString()) {
            parse_str($this->getContent(), $result);
            $this->params = array_merge($this->params, $result);
        } 
        
        if($this->isJson()) {
            $this->params = array_merge($this->params, json_decode($this->getContent(), true));
        } 
        
        $this->params = array_merge($this->params, $this->xmlParse());
        
        $this->params = json_decode(json_encode($this->params));
    }

    /**
     * In order to get the data in the requests sent, the variables in PHP are checked, if any, the data are transferred to the relevant variables.
     * @return void
     */
    private function checkRequests() : void
    {
        if (!empty($_REQUEST)) {
            $this->params = array_merge($this->params, $_REQUEST);
        }
        if (!empty($_POST)) {
            $this->postParams = array_merge($this->postParams, $_POST);
        }
        if (!empty($_GET)) {
            $this->getParams = array_merge($this->getParams, $_GET);
        }
        if (!empty($_FILES)) {
            $this->params = array_merge($this->params, $_FILES);
            $this->filesParams = array_merge($this->filesParams, $_FILES);
        }
    }

}