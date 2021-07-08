<?php

namespace Services\Contracts;

use App\Models\AVR;
use App\Models\Document;
use App\Models\StudentCertificate;
use Illuminate\Database\Eloquent\Collection;
use Libraries\Word\AVRGen;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;

/**
 * Class AVRService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class AVRService
{
    /**
     * Предпросмотр АВР
     *
     * @param int $avr_id
     * @return string
     * @throws Exception
     */
    public function avrToHtml(int $avr_id): string
    {
        $avr        = AVR::findOrFail($avr_id);
        $filePath   = public_path($avr->link);

        if (!file_exists($filePath)) abort(404);

        $phpWord = IOFactory::load($filePath, "Word2007");
        $writer = IOFactory::createWriter($phpWord, "HTML");

        return $writer->getContent();
    }

    /**
     * Предпросмотр АВР без ноера договора
     *
     * @param int $avr_id
     * @return string
     * @throws Exception
     */
    public function avrToHtmlWithoutNumber(int $avr_id): string
    {
        $avr = AVR::findOrFail($avr_id);

        return (new AVRGen)->previewWithoutNumber($avr);
    }

    /**
     * Акт в PDF
     *
     * @param int $act_id
     * @param bool $forceRewrite
     * @return string
     * @throws Exception
     * @throws \Mpdf\MpdfException
     * @see ContractService::contractToPdf()
     *
     */
    public function avrToPdf(int $act_id, bool $forceRewrite = false): string
    {
        /** @var AVR $act */
        $act = AVR::latest()->findOrFail($act_id);

        $filePath = public_path($act->link);
        $returnPath = preg_replace('/docx/', 'pdf', $act->link);

        $info = pathinfo(strtolower($filePath));

        if ($info['extension'] === 'pdf' && $forceRewrite) {
            $filePath = preg_replace('/pdf/', 'docx', $filePath);
        }

        $Content = IOFactory::load($filePath);
        $PDFWriter = IOFactory::createWriter($Content,'HTML');

        $pdfPath = preg_replace('/docx/', 'pdf', $filePath);

        $html = $PDFWriter->getContent();

        $html = str_replace('</body>', '<pagebreak orientation="P"/>{appendix}</body>', $html);

        $html = str_replace('{appendix}', $this->generateAppendix(
            $act->document,
            $act->document->number,
            $act->number
        ), $html);

        // Use A4-L (landscape) format for acts

        $html = str_replace('#auto', '#0000', $html);

        $format = 'A4-L';

        $pdf = new mPDF([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => str_contains($format, 'L') ? 'L' : 'P'
        ]);

        $pdf->SetAuthor(env('APP_NAME'));

        $pdf->WriteHTML($html);
        $pdf->Output($pdfPath, Destination::FILE);

        return $returnPath;
    }

    /**
     * Generate document appendix
     * @param Document $document
     * @param string $number
     * @param string $parent
     * @return string
     */
    private function generateAppendix(Document $document, string $number = '', string $parent = ''): string
    {
        if($document->signatures()->count() == 0)
            return '';

        return view('app.pages.general.documents.appendix', [
            'link' => route('public.document.verify', ['lang' => 'ru', 'number' => $document->number]),
            'type' => $document->type->name,
            'parent' => $parent,
            'number' => $number,
            'signatures' => $document->signatures
        ])->render();
    }

    /**
     * Сертификаты под АВР
     *
     * @param AVR $avr
     * @return Collection
     */
    public function searchCertifications(AVR $avr): Collection
    {
        return StudentCertificate::whereCourseId($avr->course_id)
            ->where(function ($q) use ($avr) {
                return $q->whereDate('created_at', '>=', $avr->start_at)
                    ->whereDate('created_at', '<=', $avr->end_at);
            })
            ->get();
    }
}
