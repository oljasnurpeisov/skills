<?php

namespace Libraries\Requests;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Get
 * @package Libraries\Requests
 */
class SendRequest
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $method;

    /**
     * @var string
     */
    private $token;

    /**
     * SendRequest constructor.
     *
     * @param string $url
     * @param string $token
     */
    public function __construct(string $url, string $token)
    {
        $this->method   = 'GET';
        $this->url      = $url;
        $this->token    = $token;
    }

    /**
     * Get request
     *
     * @return int|string
     * @throws GuzzleException
     */
    public function get()
    {
        return $this->send();
    }

    /**
     * Post request
     *
     * @return int|string
     * @throws GuzzleException
     */
    public function post()
    {
        $this->method = 'POST';

        return $this->send();
    }

    /**
     * Send request
     *
     * @return int|string
     * @throws GuzzleException
     */
    private function send()
    {
        $client = new Client(['verify' => false]);

        try {
            $response = $client->request($this->method, config('enbek.base_url').'/ru/api/resume-for-obuch', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'token' => $this->token
                ]
            ]);
        } catch (BadResponseException $e) {
            return 404;
        }

        return $response->getBody();
    }
}
