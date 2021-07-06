<?php

namespace App\Services\Signing;

use App\Libraries\NcaNode\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class SigningService
 * @package App\Services\Signing
 */
class SigningService
{
    /** @var Client */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Sign XML request
     *
     * @param string $data
     * @param string $certificate
     * @param string $password
     * @return string|null $response
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function signXml(string $data, string $certificate, string $password): ?string
    {
        $message = json_encode([
            'version' => '1.0',
            'method' => 'XML.sign',
            'params' => [
                'xml' => $data,
                'p12' => $certificate,
                'password' => $password
            ]
        ]);

        $response = null;

        try {

            $query = $this->client->call($message);

            if($query) {

                $query = json_decode($query, true);

                if(isset($query['result']) && isset($query['result']['xml']))
                    $response = $query['result']['xml'];
            }

        } catch (\Exception $exception) {
            Log::error('Signing Service error', ['exception' => $exception->getMessage()]);
        }

        return $response;
    }
}
