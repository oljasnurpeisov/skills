<?php

namespace Services\Contracts;

use App\Models\Contract;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Libraries\Word\Agreement;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;

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
     * Договор в PDF (НЕ РАБОТАЕТ!!!)
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

       $pdfPath = preg_replace('/docx/', 'pdf', $filePath);

       $PDFWriter = IOFactory::createWriter($Content,'PDF');

       $PDFWriter->save($pdfPath);

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
}
