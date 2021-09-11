<?php

namespace Services\Contracts;

use App\Console\Commands\DocumentGenerator;
use App\Models\Contract;
use App\Models\Course;
use App\Models\Document;
use App\Models\Log;
use App\Services\Files\StorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Libraries\Helpers\GetMonth;
use Libraries\Word\Agreement;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

ini_set('memory_limit', '4G');

/**
 * Class ContractService
 *
 * @author kgurovoy@gmail.com
 * @package Services\Contracts
 */
class ContractService
{
    /**
     * @var StorageService
     */
    private $storageService;

    /**
     * ContractService constructor.
     */
    public function __construct(StorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    /**
     * Получение договора, если очередь юзера
     *
     * @param $contract_id
     * @return Contract
     */
    public function getContractIfMyCurrentRoute($contract_id): Contract
    {
        return Contract::whereHas('current_route', function ($r) {
                return $r->whereRoleId(Auth::user()->role->role_id);
            })
            ->findOrFail($contract_id);
    }

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
       $filePath = StorageService::path($contract->link);

       if (!file_exists($filePath)) abort(404);

       $processor = (new Agreement($contract->course))->readTemplate($filePath);

       $processor->setValue('signature_date_kk', '&lt;қол қойылған күні&gt;');
       $processor->setValue('signature_date_ru', '&lt;дата подписания&gt;');

       $template = $processor->save();

       $phpWord = IOFactory::load($template, "Word2007");
       $writer = IOFactory::createWriter($phpWord, "HTML");

       return $writer->getContent();
   }

    /**
     * Договор в PDF
     *
     * @param int $contract_id
     * @param bool $forceRewrite
     * @param bool $saveOnly
     * @return string
     * @throws Exception
     * @throws \Mpdf\MpdfException
     *
     * @see DocumentGenerator
     */
    public function contractToPdf(int $contract_id, bool $forceRewrite = false, bool $saveOnly = false, bool $strict = null): string
   {
       /** @var Contract $contract */
       $contract = Contract::latest()->findOrFail($contract_id);
       $filePath = StorageService::path($contract->link);

       $converter = config('services.pdf.converter');
       $merger = config('services.pdf.merger');

       $info = pathinfo(strtolower($filePath));

       $template = null;

       if ($strict === false) {
           $converter = null;
           $merger = null;
       }

       $dateKz = '&lt;қол қойылған күні&gt;';
       $dateRu = '&lt;дата подписания&gt;';

       if (!$saveOnly && $contract->document && $contract->document->lastSignature) {

           $dateKz = sprintf('%d жылғы %s «%s»',
               Carbon::parse($contract->document->lastSignature->created_at)->year,
               (new GetMonth())->kk(date('m', strtotime($contract->document->lastSignature->created_at))),
               Carbon::parse($contract->document->lastSignature->created_at)->day
           );

           $dateRu = sprintf('«%s» %s %d года',
               Carbon::parse($contract->document->lastSignature->created_at)->day,
               Carbon::parse($contract->document->lastSignature->created_at)->getTranslatedMonthName('Do MMMM'),
               Carbon::parse($contract->document->lastSignature->created_at)->year
           );
       }

       if ($converter && $merger) {

           $processor = (new Agreement($contract->course))->readTemplate($filePath);

           $processor->setValue('signature_date_kk', $dateKz);
           $processor->setValue('signature_date_ru', $dateRu);

           $template = $processor->save();

           // Append document extension

           rename($template, $template . '.docx');
           $template = $template . '.docx';
       }

       $returnPath = preg_replace('/docx/', 'pdf', $contract->link);

       if ($info['extension'] === 'pdf' && $forceRewrite) {
           $filePath = preg_replace('/pdf/', 'docx', $filePath);
       }

       $Content = IOFactory::load($template ?: $filePath);
       $PDFWriter = IOFactory::createWriter($Content,'HTML');

       $pdfPath = preg_replace('/docx/', 'pdf', $filePath);
       $extPath = preg_replace('/docx/', 'ext.pdf', $filePath);
       $orgPath = preg_replace('/docx/', 'org.pdf', $filePath);

       $html = $PDFWriter->getContent();

       if (!$saveOnly) {

           $document = $contract->document;

           $appendix = $this->generateAppendix(
               $document,
               $document->number,
               $contract->number
           );

           $format = 'A4';

           $pdf = new mPDF([
               'mode' => 'utf-8',
               'format' => $format,
               'orientation' => str_contains($format, 'L') ? 'L' : 'P'
           ]);

           $pdf->SetAuthor(env('APP_NAME'));
           $pdf->WriteHTML($appendix);
           $pdf->Output($extPath, Destination::FILE);

           $html = str_replace('</body>', '<pagebreak />{appendix}</body>', $html);
           $html = str_replace('{appendix}', $appendix , $html);

           preg_match_all('/&lt;(.*?)&gt;/', $html, $dateMatches);

           if ($dateMatches && sizeof($dateMatches[0]) === 2) {

               foreach ($dateMatches[0] as $dateMatch) {

                   if (strpos($dateMatch, 'қойылған') !== false) {
                       $html = str_replace($dateMatch, $dateKz, $html);
                   } elseif (strpos($dateMatch, 'подписания') !== false) {
                       $html = str_replace($dateMatch, $dateRu, $html);
                   }
               }
           }
       }

       $html = str_replace('#auto', '#0000', $html);

       $process = null;
       $status = -1;

       if ($converter && $merger) {

           $params = [$converter, '-f', 'pdf', '-o', storage_path('app/' . $returnPath), $template ?: $filePath];

           try {

               $process = new Process($params);
               $status = $process->run();
               $output = $process->getErrorOutput() ?: $process->getOutput();

               \Illuminate\Support\Facades\Log::info('Generate status', [
                   'params' => $params,
                   'status' => $status,
                   'output' => $output
               ]);
           } catch (\Exception $exception) {

               \Illuminate\Support\Facades\Log::info('Generate status', [
                   'exception' => $exception->getMessage()
               ]);

               return $this->contractToPdf($contract_id, $forceRewrite, $saveOnly, false);
           }
       }

       // Executes only after the command finishes

       if ($status !== 0 || !$process->isSuccessful()) {

           $format = 'A4';

           $pdf = new mPDF([
               'mode' => 'utf-8',
               'format' => $format,
               'orientation' => str_contains($format, 'L') ? 'L' : 'P'
           ]);

           $pdf->SetAuthor(env('APP_NAME'));
           $pdf->shrink_tables_to_fit = 0;

           $pdf->WriteHTML($html);
           $result = $pdf->Output($pdfPath, Destination::STRING_RETURN);

           $this->storageService->save($returnPath, $result);
       }

       elseif (file_exists($extPath)) {

           copy($pdfPath, $orgPath);

           $params = [$merger, $orgPath, $extPath, $pdfPath];

           $process = new Process($params);
           $status = $process->run();
           $output = $process->getErrorOutput() ?: $process->getOutput();

           \Illuminate\Support\Facades\Log::info('Merge status', [
               'params' => $params,
               'status' => $status,
               'output' => $output
           ]);
       }

       return $returnPath;
   }

    /**
     * Удаление активных договоров
     *
     * @param int $course_id
     * @return void
     */
   public function removeActiveContracts(int $course_id): void
   {
       Contract::whereCourseId($course_id)->update([
           'status' => 6
       ]);
//       $contracts = Contract::pending()->whereCourseId($course_id)->get();

//       foreach ($contracts as $contract)
//       {
//           if (file_exists(storage_path($contract->link))) {
//               unlink(storage_path($contract->link));
//           }
//
//           $contract->delete();
//       }
    }

    /**
     * Расторжение договора
     *
     * @param int $contract_id
     * @param string $message
     * @return void
     */
   public function rejectContract(int $contract_id, string $message): void
   {
       $contract = Contract::find($contract_id);

       $contract->update([
           'status'         => 3,
           'reject_comment' => $message
       ]);

       $this->rejectQuotaContractByPaid($contract->id);
   }

    /**
     * Расторжение договора по квоте
     *
     * @param int $contract_id
     * @param int $status
     * @return void
     */
   public function rejectQuotaContractByPaid(int $contract_id, int $status = 3): void
   {
       $contract = Contract::find($contract_id);

       if ($contract->isPaid() and !empty($contract->course->contract_quota)) {
           $contract->course->contract_quota->update([
               'status'    => $status,
           ]);
       }
   }

    /**
     * Отклонение договора от администрации
     *
     * @param int $contract_id
     * @param string $message
     * @return void
     */
   public function rejectContractByAdmin(int $contract_id, string $message): void
   {
       Contract::find($contract_id)->update([
           'status'         => 5,
           'reject_comment' => $message
       ]);
   }

    /**
     * Отмена отклонения договора администрацией,
     * возращаем обратно на подписание
     *
     * @param int $contract_id
     * @return void
     */
   public function rejectContractByAdminCancel(int $contract_id): void
   {
       Contract::find($contract_id)->update([
           'status'         => 1,
           'reject_comment' => null
       ]);
   }

    /**
     * Подтверждение отклонения договора
     *
     * @param int $contract_id
     * @param string $message
     */
    public function rejectContractConfirmation(int $contract_id, string $message): void
    {
        $contract = Contract::findOrFail($contract_id);

        $contract->update([
            'status'         => 6,
            'reject_comment' => $message
        ]);

        $this->rejectQuotaContractByPaid($contract->id, 6);
    }

    /**
     * Generate document appendix
     *
     * @param Document $document
     * @param string $number
     * @param string $parent
     * @return string
     */
    private function generateAppendix(Document $document, string $number = '', string $parent = ''): string
    {
        if($document->signatures()->count() === 0)
            return '';

        return view('app.pages.general.documents.appendix', [
            'link' => route('public.document.verify', ['lang' => 'ru', 'number' => $document->number]),
            'type' => $document->type->name,
            'parent' => $parent,
            'number' => $number,
            'signatures' => $document->signatures
        ])->render();
    }
}
