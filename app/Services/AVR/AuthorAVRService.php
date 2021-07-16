<?php

namespace Services\Contracts;


use App\Models\AVR;
use App\Models\Course;
use App\Models\Document;
use App\Services\Files\StorageService;
use App\Services\Signing\DocumentService;
use Carbon\Carbon;
use DOMDocument;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Libraries\Word\AVRGen;
use PhpOffice\PhpWord\Exception\Exception;
use Services\AVR\AVRFilterService;
use Spatie\ArrayToXml\ArrayToXml;

/**
 * Class AuthorAVRService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class AuthorAVRService
{
    /**
     * @var AVRFilterService
     */
    private $AVRFilterService;

    /**
     * @var AVRServiceRouting
     */
    private $AVRServiceRouting;

    /**
     * @var $AVRService
     */
    private $AVRService;

    /**
     * @var DocumentService
     */
    private $documentService;

    /** @var StorageService */
    private $storageService;

    /**
     * AuthorAVRService constructor.
     *
     * @param AVRFilterService $AVRFilterService
     * @param AVRServiceRouting $AVRServiceRouting
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRServiceRouting $AVRServiceRouting, AVRService $AVRService, DocumentService $documentService, StorageService $storageService)
    {
        $this->AVRFilterService     = $AVRFilterService;
        $this->AVRServiceRouting    = $AVRServiceRouting;
        $this->AVRService           = $AVRService;
        $this->documentService      = $documentService;
        $this->storageService       = $storageService;
    }

    /**
     * Attache signature info and send to next route
     *
     * @param int $avr_id
     * @param string|null $message
     * @param array $validationResponse
     */
    public function acceptAvr(int $avr_id, string $message = null, array $validationResponse = [])
    {
        $avr = AVR::whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->whereHas('current_route', function ($q) {
            return $q->whereRoleId(Auth::user()->role->role_id);
        })->findOrFail($avr_id);

        if ($message) {

            $avr->author_signed_at = Carbon::now();
            $avr->save();

            if ($avr->document) {
                $this->documentService->attachSignature($avr->document, $message, $validationResponse);
            }

        } else {
            $this->AVRServiceRouting->toNextRoute($avr);
        }
    }

    /**
     * Обновление номера АВР и счета фактуры
     *
     * @param int $avr_id
     * @param array $request
     */
    public function updateAVR(int $avr_id, array $request)
    {
        Log::info('Update AVR', ['id' => $avr_id, 'request' => $request]);

        $avr = AVR::findOrFail($avr_id);

        if (isset($request['invoice'])) {
            $avr->update(['invoice_link' => $request['invoice']]);
        } elseif(isset($request['avr_number'])) {
            $avrGen = new AVRGen();
            $avrGen->addAVRNumber($avr, $request['avr_number']);
        }
    }

    /**
     * Получаем договора автора
     *
     * @param array|null $request
     * @return LengthAwarePaginator
     */
    public function getOrSearchMyContracts(array $request = null): LengthAwarePaginator
    {
        $avr = AVR::with('course');

        $avr = $this->AVRFilterService->search($avr, $request);

        return $avr->whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->latest()->paginate(10);
    }

    /**
     * Generate (and store) act xml
     *
     * @param AVR $act
     * @return string
     * @throws Exception
     */
    public function generateXml(AVR $act): string
    {
        $document = $act->document;

        if  (!$document) {
            $document = new Document();
            $document->type_id = 2;
            $document->number = Document::generateNumber(2);
        }

        $attributes = $act->toArray();

        unset($attributes['document']);

        $content = ArrayToXml::convert(
            $attributes,
            [],
            true,
            'UTF-8',
            '1.0',
            [],
            true
        );

        $xml = new DomDocument('1.0', 'utf-8');
        $xml->loadXML($content);

        $root = $xml->getElementsByTagName('root')->item(0);

        $html = $this->AVRService->avrToHtml($act->id);
        preg_match("/<body[^>]*>(.*?)<\/body>/is", $html, $matches);
        $cdata = $matches[1];

        $cdata = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $cdata);
        $cdata = strip_tags($cdata, '<table><tr><td><p>');

        $protocol = $root->appendChild($xml->createElement('document'));
        $protocol->appendChild($xml->createCDATASection($cdata));

        $xml->formatOutput = true;

        $content = $xml->saveXML();

        $document->user_id = Auth::user()->id;

        if ($document->save()) {

            $act->document_id = $document->id;
            $act->save();

            // Clean up signatures on document regeneration

            foreach ($document->signatures as $signature) {
                $signature->delete();
            }

            // Save file in storage

            $this->storageService->save(preg_replace('/docx/', 'xml', $act->link), $content);
        }

        return $content;
    }
}
