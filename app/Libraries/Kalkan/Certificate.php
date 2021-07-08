<?php

namespace App\Libraries\Kalkan;

use Illuminate\Support\Facades\Log;

/**
 * Class Certificate
 * @package App\Libraries\Kalkan
 */
class Certificate
{
    const DATETIME_FORMAT = 'Y-m-d H:i:s';
    const DATE_FORMAT = 'Y-m-d';

    static $indexSignature = 'DS:SIGNATUREVALUE';
    static $indexCertificate = 'DS:X509CERTIFICATE';
    static $indexDigest = 'DS:DIGESTVALUE';

    static $indexCleanDigest = 'digestvalue';
    static $indexCleanSignature = 'signaturevalue';
    static $indexCleanCertificate = 'x509certificate';

    static $separatorBegin = '-----BEGIN CERTIFICATE-----';
    static $separatorEnd = '-----END CERTIFICATE-----';

    /**
     * @var string
     */
    public $iin;

    /**
     * @var string
     */
    public $bin;

    /** @var array  */
    public $subject = [];

    /**
     * @var string
     */
    public $serial;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var int
     */
    public $startTime;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @var int
     */
    public $endTime;

    /**
     * @var string
     */
    public $birthDate;

    /**
     * @var string
     */
    public $legalName;

    /**
     * @var string
     */
    public $personName;

    /**
     * @var
     */
    protected $x509;

    /**
     * Simple check for sing OIDs
     * @var string[]
     */
    public static $signPolicies = [
        '1.2.398.3.3.2.1',
        '1.2.398.3.3.2.3'
    ];

    /**
     * Simple check for auth OIDs
     * @var string[]
     */
    public static $authPolicies = [
        '1.2.398.3.3.2.2',
        '1.2.398.3.3.2.4'
    ];

    /**
     * Strict check for signing OIDs
     * @var string[]
     */
    public static $strictSignPolicies = [
        '1.2.398.3.3.4.1.2.1', // First leader
        '1.2.398.3.3.4.1.2.2', // Sign permissions
        '1.2.398.3.3.4.1.2.3', // Financial sign permission
    ];

    /**
     * @var string
     */
    private $error = '';

    /**
     * Certificate constructor
     *
     * @param string $certificate
     */
    public function __construct(string $certificate)
    {
        if(!$certificate)
            return null;

        if (strpos($certificate, 'BEGIN') === false)
            $certificate = static::$separatorBegin . PHP_EOL . trim($certificate) . PHP_EOL . static::$separatorEnd;

        $this->x509 = openssl_x509_parse($certificate);

        if (!$this->x509) {
            Log::error('Certificate parse error', ['error' => openssl_error_string(), 'certificate' => $certificate]);
            return null;
        }

        $this->subject = $this->x509['subject'];

        $this->country = strtolower($this->x509['subject']['C']);
        $this->iin = str_replace(['I', 'N'], '', $this->x509['subject']['serialNumber']);

        if (isset($this->x509['subject']['OU'])) {
            $this->bin = str_replace(['B', 'I', 'N'], '', $this->x509['subject']['OU']);
        }

        if($this->country !== 'kz')
            $this->bin = $this->x509['subject']['SN'];

        if(isset($this->x509['serialNumber']))
            $this->serial = $this->x509['serialNumber'];

        $this->startTime = $this->x509['validFrom_time_t'];
        $this->startDate = date(self::DATETIME_FORMAT, $this->startTime);

        $this->endTime = $this->x509['validTo_time_t'];
        $this->endDate = date(self::DATETIME_FORMAT, $this->endTime);

        if($this->iin) {

            $century = 19;

            if($this->iin[6] > 4) {
                $century = 20;
            }

            $this->birthDate = date(self::DATE_FORMAT, strtotime($century . substr($this->iin, 0, 6)));
        }

        if (isset($this->x509['subject']['O']))
            $this->legalName = trim($this->x509['subject']['O']);

        $this->personName = $this->x509['subject']['CN'];

        if (isset($this->x509['G']))
            $this->personName .= $this->x509['G'];

        Log::debug('Certificate data fetched', ['subject' => $this->subject, 'x509' => $this->x509]);

        return $this;
    }

    /**
     * Check signing permission
     * @param string|null $requestedIinBin
     * @return bool
     */
    public function canSign(string $requestedIinBin = null): bool
    {
        if ($requestedIinBin) {
            if((int) $requestedIinBin[4] > 3 && $this->bin !== $requestedIinBin) {
                $this->error = 'Некорректный БИН для подписания документа';
                return false;
            } elseif ($this->iin !== $requestedIinBin) {
                $this->error = 'Некорректный ИИН для подписания документа';
                return false;
            }
        }

        if (!$this->bin)
            return true;

        $allowedPolicies = static::$strictSignPolicies;

        foreach ($allowedPolicies as $policy) {
            if (substr_count($this->x509['extensions']['certificatePolicies'], $policy))
                return true;
        }

        $this->error = 'Отсутствует право подписи';

        return false;
    }

    /**
     * Get error
     * @return string|null
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * getXmlCertificate function
     *
     * Get key from signed XML
     *
     * @param string $signature
     * @param bool $certificateOnly
     * @return array|Certificate|null
     */
    public static function getCertificate(string $signature, bool $certificateOnly = true)
    {
        $parser = xml_parser_create();

        xml_parse_into_struct($parser, $signature, $data, $index);
        xml_parser_free($parser);

        $info = [];

        foreach ($data as $attribute) {

            if (!isset($attribute['tag'])) continue;

            $cleanTag = strtolower(trim(str_replace('DS:', '', $attribute['tag'])));

            switch ($cleanTag) {

                case self::$indexCleanCertificate:
                case self::$indexCertificate:
                    if (isset($attribute['value']) && isset($attribute['type']) && $attribute['type'] == 'complete')
                        $info['certificate'] = trim($attribute['value']);
                    break;

                case self::$indexCleanDigest:
                case self::$indexDigest:
                    if (isset($attribute['value']) && isset($attribute['type']) && $attribute['type'] == 'complete')
                        $info['digest'] = trim($attribute['value']);
                    break;

                case self::$indexCleanSignature:
                case self::$indexSignature:
                    if (isset($attribute['value']) && isset($attribute['type']) && $attribute['type'] == 'complete')
                        $info['signature'] = trim($attribute['value']);
                    break;
            }
        }

        if($certificateOnly) {

            if (isset($info['certificate']))
                return new self($info['certificate']);

            return null;
        }

        return $info;
    }
}
