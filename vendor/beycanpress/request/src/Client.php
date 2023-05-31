<?php

namespace BeycanPress;

final class Client
{

    /**
     * Base API url
     * @var string
     */
    private $baseUrl = null;

    /**
     * cURL process infos
     * @var array
     */
    private $info;

    /**
     * cURL process errors
     * @var string
     */
    private $error;

    /**
     * Default options
     * @var array
     */
    private $options = [
        CURLOPT_RETURNTRANSFER => true
    ];

    public function get(string $url, array $data = [])
    {
        if ( ! empty( $data ) ) {
            $url = $url . '?' . http_build_query($data);
        }

        return $this->start($url);
    }

    public function post(string $url, array $data = [])
    {
        $this->addOptions([
            CURLOPT_POST => true
        ]);

        if ( ! empty( $data ) ) {
            $this->addOptions([
                CURLOPT_POSTFIELDS => $data
            ]);
        }

        return $this->start($url);
    }

    public function setOptions(array $options)
    {
        if ( isset( $options['BASE_URL'] ) ) {
            $this->baseUrl = $options['BASE_URL'];
        }
    }

    public function getInfo()
    {
        return $this->info;
    }

    public function getError()
    {
        return $this->error;
    }

    private function start(string $url)
    {
        if ( ! is_null( $this->baseUrl ) ) {
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

        return $result;
    }

    private function addOptions(array $options)
    {
        $this->options += $options;
        return $this;
    }
}