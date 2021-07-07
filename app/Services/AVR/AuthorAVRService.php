<?php

namespace Services\Contracts;


use App\Models\AVR;
use App\Models\Course;
use App\Models\Document;
use App\Services\Signing\DocumentService;
use DOMDocument;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Libraries\Word\AVRGen;
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

    /**
     * AuthorAVRService constructor.
     *
     * @param AVRFilterService $AVRFilterService
     * @param AVRServiceRouting $AVRServiceRouting
     */
    public function __construct(AVRFilterService $AVRFilterService, AVRServiceRouting $AVRServiceRouting, AVRService $AVRService, DocumentService $documentService)
    {
        $this->AVRFilterService     = $AVRFilterService;
        $this->AVRServiceRouting    = $AVRServiceRouting;
        $this->AVRService           = $AVRService;
        $this->documentService      = $documentService;
    }

    /**
     * Attache signature info and send to next route
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

        if ($avr->document && $message) {
            $this->documentService->attachSignature($avr->document, $message, $validationResponse);
        }

        $this->AVRServiceRouting->toNextRoute($avr);
    }

    /**
     * Обновление номера АВР и счета фактуры
     *
     * @param int $avr_id
     * @param array $request
     */
    public function updateAVR(int $avr_id, array $request)
    {
        $avr = AVR::findOrFail($avr_id);
        $avr->update(['invoice_link' => $request['invoice']]);

        $avrGen = new AVRGen();
        $avrGen->addAVRNumber($avr, $request['avr_number']);
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
     * @throws \PhpOffice\PhpWord\Exception\Exception
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
        $cdata = strip_tags($cdata, ['table', 'tr', 'td', 'p']);

        $protocol = $root->appendChild($xml->createElement('document'));
        $protocol->appendChild($xml->createCDATASection($cdata));

        $xml->formatOutput = true;

        $document->content = $xml->saveXML();

        $document->user_id = Auth::user()->id;
        $document->type_id = 1;

        if ($document->save()) {

            $act->document_id = $document->id;
            $act->save();

            // Clean up signatures on document regeneration

            foreach ($document->signatures as $signature) {
                $signature->delete();
            }
        }

        return $document->content;
    }
}
