<?php

namespace App\Libraries\NcaNode;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

/**
 * Class Client
 * @package App\Libraries\NcaNode
 */
class Client
{
    /**
     * @var string
     */
    public static $host = 'http://127.0.0.1';

    /**
     * @var int
     */
    public static $port = 14579;

    /**
     * @var \GuzzleHttp\Client
     */
    public $client = null;

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var string|null
     */
    public $request;

    /**
     * @var string|null
     */
    public $response;

    /**
     * @var GuzzleException|null
     */
    public $exception;

    /**
     * Client constructor.
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        static::$host = config('services.ncanode.host');
        static::$port = config('services.ncanode.port');

        $defaults = [
            'base_uri' => static::$host,
            'timeout'  => config('services.ncanode.connect_timeout'),
            'http_errors' => false
        ];

        $this->client = new \GuzzleHttp\Client($defaults + $options);

        Log::debug('NCANode client init', ['default' => $defaults, 'options' => $options]);
    }

    /**
     * Call service
     *
     * @param string $data
     * @param bool $decode
     * @param int $count
     *
     * @return array|string|null
     * @throws GuzzleException
     */
    public function call(string $data, bool $decode = false, int $count = 1)
    {
        $url = sprintf('%s:%d', static::$host, static::$port);

        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/77.0.3865.35 Safari/537.36',
            'Host' => str_replace(['http://', 'https://'], '', static::$host),
            'Accept' => 'application/json',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,bg;q=0.6',
            'Content-Type' => 'application/json'
        ];

        try {

            $this->request = $this->client->post($url, ['headers' => $headers, 'body' => $data]);

            if ($this->request->getStatusCode() === 200) {
                $this->response = $decode ? json_decode($this->request->getBody(), true) : $this->request->getBody();
                Log::debug('NCANode call', ['url' => $url, 'data' => $data, 'response' => $this->response]);
                return $this->response;
            } elseif($count <= config('services.ncanode.connect_retries')) {
                Log::warning('NCANode next try', ['url' => $url, 'data' => $data, 'count' => $count]);
                return $this->call($data, $decode, $count + 1);
            }

        } catch (GuzzleException $exception) {

            $this->exception = $exception;

            Log::error('NCANode exception:' . $exception->getMessage(), ['url' => $url, 'data' => $data, 'count' => $count]);

            if($count <= 5) {
                return $this->call($data, $decode, $count + 1);
            } else {
                throw $exception;
            }
        }

        Log::error('NCANode no more tries', ['url' => $url, 'data' => $data, 'count' => $count]);

        return null;
    }
}
