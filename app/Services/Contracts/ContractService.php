<?php

namespace Services\Contracts;


use App\Models\Contract;
use App\Models\Course;
use Libraries\Word\Agreement;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Barryvdh\DomPDF\PDF;

/**
 * Class ContractService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractService
{
    /**
     * Создаем договор,
     * если данного типа еще не создан
     *
     * @param Course $course
     * @param string $type
     * @return Contract|string|bool
     * @throws Exception
     */
    public function createContract(Course $course, string $type)
    {
        $contract = new Agreement($course, $type);
        return $contract->generate();
    }

    /**
     * Превью договора
     *
     * @param Course $course
     * @param string $type
     * @return string
     * @throws Exception
     */
    public function createPreviewContract(Course $course, string $type): string
    {
        $contract = new Agreement($course, $type, false);
        return $contract->generate();
    }

    /**
     * Предпросмотр договора
     *
     * @param int $contract_id
     * @return string
     * @throws Exception
     */
   public function contractToHtml(int $contract_id): string
   {
       $contract = Contract::latest()->findOrFail($contract_id);
       $filePath = public_path($contract->link);

       if (!file_exists($filePath)) abort(404);

       $phpWord = IOFactory::load($filePath, "Word2007");
       $writer = IOFactory::createWriter($phpWord, "HTML");

       return $writer->getContent();
   }

    /**
     * Договор в PDF (НЕ РАБОТАЕТ!)
     *
     * @param int $contract_id
     * @return string
     * @throws Exception
     */
   public function contractToPdf(int $contract_id): string
   {
       $contract = Contract::latest()->findOrFail($contract_id);
       $filePath = public_path($contract->link);

       Settings::setPdfRendererPath(base_path('vendor/dompdf/dompdf'));
       Settings::setPdfRendererName('DomPDF');

       $Content = IOFactory::load($filePath);

       $PDFWriter = IOFactory::createWriter($Content,'PDF');

//       $PDFWriter->save(public_path('new-result.pdf'));

       return $PDFWriter->getContent();
   }
}
