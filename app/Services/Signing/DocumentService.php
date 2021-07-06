<?php

namespace App\Services\Signing;

use App\Libraries\Kalkan\Certificate;
use App\Models\Document;
use App\Models\DocumentSignature;

/**
 * Class DocumentService
 * @package App\Services\Signing
 */
class DocumentService
{
    /**
     * Attach document signature
     *
     * @param Document $document
     * @param string $message
     * @param array $validationResponse
     */
    public function attachSignature(Document $document, string $message, array $validationResponse = [])
    {
        $attributes = Certificate::getCertificate($message, false);

        $signature = new DocumentSignature();

        $signature->document_id = $document->id;
        $signature->user_id = Auth()->user()->id;

        $signature->hash = $attributes['digest'];
        $signature->sign = $attributes['signature'];
        $signature->cert = $attributes['certificate'];

        $signature->data = $message;
        $signature->result = json_encode($validationResponse);

        $signature->save();
    }
}
