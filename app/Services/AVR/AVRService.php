<?php

namespace Services\Contracts;

use App\Models\AVR;
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
}
