<?php

namespace Services\Contracts;

use App\Models\AVR;
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
     * Предпросмотр договора
     *
     * @param int $avr_id
     * @return string
     * @throws Exception
     */
    public function avrToHtml(int $avr_id): string
    {
        $avr        = AVR::latest()->findOrFail($avr_id);
        $filePath   = public_path($avr->link);

        if (!file_exists($filePath)) abort(404);

        $phpWord = IOFactory::load($filePath, "Word2007");
        $writer = IOFactory::createWriter($phpWord, "HTML");

        return $writer->getContent();
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

        $info = pathinfo(strtolower($filePath));

        if ($info['extension'] === 'pdf' && $forceRewrite) {
            $filePath = preg_replace('/pdf/', 'docx', $filePath);
        }

        $Content = IOFactory::load($filePath);
        $PDFWriter = IOFactory::createWriter($Content,'HTML');

        $pdfPath = preg_replace('/docx/', 'pdf', $filePath);

        $html = $PDFWriter->getContent();

        // @todo replacements

        // Use A4-L (landscape) format for acts

        $format = 'A4-L';

        $pdf = new mPDF([
            'mode' => 'utf-8',
            'format' => $format,
            'orientation' => str_contains($format, 'L') ? 'L' : 'P'
        ]);

        $pdf->SetAuthor(env('APP_NAME'));

        $pdf->WriteHTML($html);
        $pdf->Output($pdfPath, Destination::FILE);

        return $pdfPath;
    }
}
