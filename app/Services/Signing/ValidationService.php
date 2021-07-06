<?php

namespace App\Services\Signing;

use App\Libraries\NcaNode\Client;
use Illuminate\Support\Facades\Log;

/**
 * Class SigningService
 * @package App\Services\Signing
 */
class ValidationService
{
    /** @var Client */
    private $client;

    /** @var string */
    private $error;

    /**
     * @var array
     */
    private $response = [];

    /**
     * ValidationService constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * Validate signed XML
     *
     * @param $data
     * @param bool $rawResult
     * @param string $allowedPolicy
     * @return array|bool|mixed|string|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function verifyXml($data, bool $rawResult = false, string $allowedPolicy = 'SIGN')
    {
        $message = json_encode([
            'version' => '1.0',
            'method' => 'XML.verify',
            'params' => [
                'xml' => $data,
                'verifyCrl' => true,
                'verifyOcsp' => (boolean) $allowedPolicy == 'SIGN'
            ]
        ]);

        $response = null;
        $query = null;

        $checklist = [
            'valid' => false,
            'revoke' => false,
            'chain' => false,
            'date' => false,
            'policy' => false
        ];

        try {

            $query = $this->client->call($message);

            if($query) {

                $query = json_decode($query, true);
                $this->response = $query;

                if(isset($query['result'])) {

                    $checklist['valid'] = (boolean) $query['result']['valid'];

                    if (!isset($query['result']['cert']['ocsp'])) {
                        $checklist['revoke'] = null;
                        $this->error = 'Невозможно проверить отозванность сертификата';
                    } else {

                        $checklist['revoke'] = (
                            $query['result']['cert']['ocsp']['status'] === 'ACTIVE'
                            ||
                            $query['result']['cert']['crl']['status'] === 'ACTIVE'
                        );
                    }

                    if (!isset($query['result']['cert']['chain'])) {
                        $checklist['chain'] = false;
                        $this->error = 'Невозможно проверить валидность цепочки сертификатов';
                    } else {
                        $checklist['chain'] = (sizeof($query['result']['cert']['chain']) > 0);
                    }

                    $checklist['date'] =  (
                        strtotime($query['result']['cert']['notAfter']) > time()
                        &&
                        time() > strtotime($query['result']['cert']['notBefore'])
                    );

                    $checklist['valid'] = (boolean) ($query['result']['cert']['valid']);
                    $checklist['policy'] = ($query['result']['cert']['keyUsage'] === $allowedPolicy);
                }

                else {

                    $checklist = [
                        'valid' => false,
                        'revoke' => null,
                        'chain' => null,
                        'date' => null,
                        'policy' => null
                    ];
                }

                $response = array_search(false, $checklist, true) === false;

                Log::debug('nca_verify', ['request' => $message, 'response' => $query, 'checkList' => $checklist]);

            } else {
                $this->error = 'При проверке подлинности произошла ошибка';
            }

        } catch (\Exception $exception) {
            Log::error('nca_verify', ['error' => $exception->getMessage()]);
            $this->error = 'При проверке подлинности произошла ошибка: ' . $exception->getMessage();
        }

        if ($checklist['valid'] === false && empty($this->error)) {
            $this->error = 'Некорректный сертификат';
        }

        return $rawResult ? $query : $response;
    }
}
