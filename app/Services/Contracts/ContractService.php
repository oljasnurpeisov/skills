<?php

namespace Services\Contracts;

use App\Console\Commands\DocumentGenerator;
use App\Libraries\Kalkan\Certificate;
use App\Models\Contract;
use App\Models\Course;
use App\Models\Document;
use App\Models\DocumentSignature;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Libraries\Helpers\GetMonth;
use Libraries\Word\Agreement;
use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;

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
     * ContractService constructor.
     */
    public function __construct()
    {
        //
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
       $filePath = public_path($contract->link);

       if (!file_exists($filePath)) abort(404);

       $phpWord = IOFactory::load($filePath, "Word2007");
       $writer = IOFactory::createWriter($phpWord, "HTML");

       return $writer->getContent();
   }

    /**
     * Договор в PDF
     *
     * @see DocumentGenerator
     *
     * @param int $contract_id
     * @return string
     * @throws Exception
     */
   public function contractToPdf(int $contract_id, bool $forceRewrite = false): string
   {
       /** @var Contract $contract */
       $contract = Contract::latest()->findOrFail($contract_id);

       $filePath = public_path($contract->link);

       $info = pathinfo(strtolower($filePath));

       if ($info['extension'] === 'pdf' && $forceRewrite) {
           $filePath = preg_replace('/pdf/', 'docx', $filePath);
       }

       $Content = IOFactory::load($filePath);
       $PDFWriter = IOFactory::createWriter($Content,'HTML');

       $pdfPath = preg_replace('/docx/', 'pdf', $filePath);

       $html = $PDFWriter->getContent();

       $html = str_replace('</body>', '<pagebreak />{appendix}</body>', $html);

       $html = str_replace('{appendix}', $this->generateAppendix(
           $contract->document,
           $contract->document->number,
           $contract->number
       ), $html);

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

       preg_match_all('/&lt;(.*?)&gt;/', $html, $dateMatches);

       if ($dateMatches && sizeof($dateMatches[0]) === 2) {

           foreach($dateMatches[0] as $dateMatch) {

               if(strpos($dateMatch, 'қойылған') !== false) {
                    $html = str_replace($dateMatch, $dateKz, $html);
               }

               elseif(strpos($dateMatch, 'подписания') !== false) {
                   $html = str_replace($dateMatch, $dateRu, $html);
               }
           }
       }

       $html = str_replace('#auto', '#0000', $html);

       $format = 'A4';

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

    /**
     * Удаление активных договоров
     *
     * @param int $course_id
     * @return void
     */
   public function removeActiveContracts(int $course_id): void
   {
       $contracts = Contract::pending()->whereCourseId($course_id)->get();

       foreach ($contracts as $contract)
       {
           if (file_exists(public_path($contract->link))) {
               unlink(public_path($contract->link));
           }

           $contract->delete();
       }
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
}
