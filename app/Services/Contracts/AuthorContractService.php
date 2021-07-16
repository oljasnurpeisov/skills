<?php

namespace Services\Contracts;


use App\Models\Contract;
use App\Models\Document;
use App\Services\Files\StorageService;
use DOMDocument;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Spatie\ArrayToXml\ArrayToXml;

class AuthorContractService
{
    /**
     * @var ContractFilterService
     */
    private $contractFilterService;

    /** @var ContractService */
    private $contractService;

    /** @var StorageService */
    private $storageService;

    /**
     * AuthorContractService constructor.
     *
     * @param ContractFilterService $contractFilterService
     * @param ContractService $contractService
     */
    public function __construct(ContractFilterService $contractFilterService, ContractService $contractService, StorageService $storageService)
    {
        $this->contractFilterService = $contractFilterService;
        $this->contractService = $contractService;
        $this->storageService = $storageService;
    }

    /**
     * Получаем договора автора
     *
     * @param array|null $request
     * @return LengthAwarePaginator
     */
    public function getOrSearchMyContracts(array $request = null): LengthAwarePaginator
    {
        $contracts = Contract::signed()->with('course');

        $contracts = $this->contractFilterService->search($contracts, $request);

        return $contracts->whereHas('course', function ($q) {
            return $q->whereAuthorId(Auth::user()->id);
        })->latest()->paginate(10);

    }

    /**
     * Договор курса отклонен автором
     *
     * @param Contract $contract
     * @return void
     */
    public function rejectContract(Contract $contract): void
    {
        Contract::find($contract->id)->update([
            'status' => 4
        ]);

        if ($contract->isFree() or $contract->isPaid()) {

            Session::flash('status', 'Договор ('. $contract->getTypeName() .') отклонен, курс перемещен в черновики!');

            $contract->course->update([
                'contract_status'   => 0,
                'status'            => 0
            ]);

            if ($contract->isPaid()) {
//                Contract::whereCourseId($contract->course_id)->quota()->pending()->first()->update([
//                    'status' => 4
//                ]);
                $contract->update([
                    'status' => 4
                ]);
            }
        } else {
            Session::flash('status', 'Договор ('. $contract->getTypeName() .') отклонен!');
        }
    }

    /**
     * Generate (and store) contract xml
     *
     * @param Contract $contract
     * @return string
     * @throws \PhpOffice\PhpWord\Exception\Exception
     */
    public function generateXml(Contract $contract): string
    {
        $document = $contract->document;

        if  (!$document) {
            $document = new Document();
            $document->number = Document::generateNumber();
        }

        $attributes = $contract->toArray();

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

        $html = $this->contractService->contractToHtml($contract->id);
        preg_match("/<body[^>]*>(.*?)<\/body>/is", $html, $matches);
        $cdata = $matches[1];

        $cdata = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $cdata);
        $cdata = strip_tags($cdata, '<table><tr><td><p>');

        $protocol = $root->appendChild($xml->createElement('document'));
        $protocol->appendChild($xml->createCDATASection($cdata));

        $xml->formatOutput = true;

        $content = $xml->saveXML();

        $document->user_id = Auth::user()->id;
        $document->type_id = 1;

        if ($document->save()) {

            $contract->document_id = $document->id;
            $contract->save();

            // Clean up signatures on document regeneration

            foreach ($document->signatures as $signature) {
                $signature->delete();
            }

            // Save file in storage

            $this->storageService->save(preg_replace('/docx/', 'xml', $contract->link), $content);
        }

        return $content;
    }
}
