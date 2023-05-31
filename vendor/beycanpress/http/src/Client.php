<?php

namespace BeycanPress\Http;

final class Client
{

    /**
     * Base API url
     * @var string
     */
    private $baseUrl = null;

    /**
     * cURL process infos
     * @var mixed
     */
    private $info;

    /**
     * cURL process errors
     * @var string
     */
    private $error;

    /**
     * @var array
     */
    private $methods = [
        "GET",
        "HEAD",
        "POST",
        "PUT",
        "DELETE",
        "CONNECT",
        "OPTIONS",
        "TRACE",
        "PATCH",
    ];

    /**
     * @var array
     */
    private $headers = [];

    /**
     * Default options
     * @var array
     */
    private $options = [
        CURLOPT_RETURNTRANSFER => true,
    ];

    /**
     * @param string $url
     * @return Client
     */
    public function setBaseUrl(string $url) : Client
    {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     * @return Client
     */
    public function addOption($key, $value) : Client
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param mixed $key
     * @return Client
     */
    public function deleteOption($key) : Client
    {
        if (isset($this->options[$key])) {
            unset($this->options[$key]);
        }
        return $this;
    }

    /**
     * @param array $keys
     * @return Client
     */
    public function deleteOptions(array $keys) : Client
    {
        foreach ($keys as $key) {
            $this->deleteOption($key);
        }
        return $this;
    }

    /**
     * @param array $options
     * @return Client
     */
    public function addOptions(array $options) : Client
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return Client
     */
    public function addHeader(string $key, string $value) : Client
    {
        $this->headers[$key] = $key . ': ' . $value;
        return $this;
    }

    /**
     * @param string $key
     * @return Client
     */
    public function deleteHeader(string $key) : Client
    {
        if (isset($this->headers[$key])) {
            unset($this->headers[$key]);
        }
        return $this;
    }

    /**
     * @param array $headers
     * @return Client
     */
    public function addHeaders(array $headers) : Client
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    public function deleteHeaders(array $keys) : Client
    {
        foreach ($keys as $key) {
            $this->deleteHeader($key);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getError() : string
    {
        return $this->error;
    }

    /**
     *
     * @param string $string
     * @return mixed
     */
    private function ifIsJson(string $string) 
    {
        $json = json_decode($string);
        if (json_last_error() === JSON_ERROR_NONE) {
            return $json;
        } else {
            return $string;
        }
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        if (!in_array(strtoupper($name), $this->methods)) {
            throw new \Exception("Method not found");
        }
        
        $this->addOption(CURLOPT_CUSTOMREQUEST, strtoupper($name));
        $this->addOption(CURLOPT_HTTPHEADER, array_values($this->headers));
        return $this->beforeSend(...$arguments);
    }

    /**
     * @return array
     */
    public function getMethods() : array
    {
        return $this->methods;
    }

    /**
     * @param string $url
     * @param array $data
     * @param boolean $raw
     * @return mixed
     */
    private function beforeSend(string $url, array $data = [], bool $raw = false)
    {
        if (!empty($data)) {
            if ($raw) {
                $data = json_encode($data);
                $data = <<<DATA
                    $data
                DATA;
            } else {
                $data = http_build_query($data);
            }
            $this->addOption(CURLOPT_POSTFIELDS, $data);
        }

        return $this->send($url);
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function send(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) && !is_null($this->baseUrl)) {
            $url = $this->baseUrl . $url;
        }

        // Inıt
        $curl = curl_init($url);
        
        // Set options
        curl_setopt_array($curl, $this->options);

        // Exec
        $result = curl_exec($curl);

        // Get some information
        $this->info = curl_getinfo($curl);
        $this->error = curl_error($curl);

        // Close
        curl_close($curl);

        if (is_string($result)) {
            $result = $this->ifIsJson($result);
        }

        $this->deleteOption(CURLOPT_POSTFIELDS);
        
        return $result;
    }
}