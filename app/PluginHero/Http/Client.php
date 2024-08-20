<?php

declare(strict_types=1);

namespace BeycanPress\CryptoPayLite\PluginHero\Http;

final class Client
{
    /**
     * Base API url
     * @var string|null
     */
    private ?string $baseUrl = null;

    /**
     * HTTP process infos
     * @var array<string, mixed>
     */
    private array $info = [];

    /**
     * HTTP process errors
     * @var string
     */
    private string $error = '';

    /**
     * @var array<string>
     */
    private array $methods = [
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
     * @var array<string>
     */
    private array $headers = [];

    /**
     * Default options
     * @var array<string,mixed>
     */
    private array $options = [
        'timeout' => 10,
    ];

    /**
     * @param string $url
     * @return Client
     */
    public function setBaseUrl(string $url): Client
    {
        $this->baseUrl = $url;
        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Client
     */
    public function addOption(string $key, mixed $value): Client
    {
        $this->options[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return Client
     */
    public function deleteOption(string $key): Client
    {
        if (isset($this->options[$key])) {
            unset($this->options[$key]);
        }
        return $this;
    }

    /**
     * @param array<string> $keys
     * @return Client
     */
    public function deleteOptions(array $keys): Client
    {
        foreach ($keys as $key) {
            $this->deleteOption($key);
        }
        return $this;
    }

    /**
     * @param array<string,mixed> $options
     * @return Client
     */
    public function addOptions(array $options): Client
    {
        $this->options = array_merge($this->options, $options);
        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return Client
     */
    public function addHeader(string $key, string $value): Client
    {
        $this->headers[$key] = $value;
        return $this;
    }

    /**
     * @param string $key
     * @return Client
     */
    public function deleteHeader(string $key): Client
    {
        if (isset($this->headers[$key])) {
            unset($this->headers[$key]);
        }
        return $this;
    }

    /**
     * @param array<string,string> $headers
     * @return Client
     */
    public function addHeaders(array $headers): Client
    {
        foreach ($headers as $key => $value) {
            $this->addHeader($key, $value);
        }

        return $this;
    }

    /**
     * @param array<string> $keys
     * @return Client
     */
    public function deleteHeaders(array $keys): Client
    {
        foreach ($keys as $key) {
            $this->deleteHeader($key);
        }
        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function getInfo(): array
    {
        return $this->info;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function ifIsJson(string $string): mixed
    {
        $json = json_decode($string);
        if (JSON_ERROR_NONE === json_last_error()) {
            return $json;
        } else {
            return $string;
        }
    }

    /**
     * @param string $name
     * @param array<mixed> $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        if (!in_array(strtoupper($name), $this->methods)) {
            throw new \Exception("Method not found");
        }

        return $this->beforeSend($name, ...$arguments);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array<mixed> $data
     * @param boolean $raw
     * @return mixed
     */
    private function beforeSend(string $method, string $url, array $data = [], bool $raw = false): mixed
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) && !is_null($this->baseUrl)) {
            $url = $this->baseUrl . $url;
        }

        if (!empty($data)) {
            if ($raw) {
                $data = wp_json_encode($data);
            }
            $this->options['body'] = $data;
        }

        $this->options['method'] = strtoupper($method);
        $this->options['headers'] = $this->headers;

        return $this->send($url);
    }

    /**
     * @param string $url
     * @return mixed
     */
    private function send(string $url): mixed
    {
        $response = wp_remote_request($url, $this->options);

        if (is_wp_error($response)) {
            $this->error = $response->get_error_message();
            return false;
        }

        $this->info = [
            'response_code' => wp_remote_retrieve_response_code($response),
            'response_message' => wp_remote_retrieve_response_message($response),
        ];

        $body = wp_remote_retrieve_body($response);
        return $this->ifIsJson($body);
    }
}
