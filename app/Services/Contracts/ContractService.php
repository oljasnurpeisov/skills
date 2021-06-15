<?php

namespace Services\Contracts;


use App\Models\Contract;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;

/**
 * Class ContractService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractService
{
    /**
     * Предпросмотр договора
     *
     * @param int $id
     * @return string
     * @throws Exception
     */
   public function contractToHtml(int $id): string
   {
       $contract = Contract::findOrFail($id);
       $filePath = public_path($contract->link);

       if (!file_exists($filePath)) abort(404);

       $phpWord = IOFactory::load($filePath, "Word2007");
       $writer = IOFactory::createWriter($phpWord, "HTML");

       return $writer->getContent();
   }
}
