<?php

namespace Libraries\Word;

use App\Extensions\CalculateQuotaCost;
use App\Models\AVR;
use App\Models\Course;
use App\Services\Files\StorageService;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;

class AVRGen extends BaseGenerator
{
    /**
     * @var Course
     */
    private $course;

    /**
     * @var TemplateProcessor
     */
    protected $templateProcessor;

    /**
     * @var bool
     */
    private $save;

    /**
     * @var string
     */
    private $id;

    /**
     * @var AVR
     */
    private $avr;

    /**
     * @var Carbon
     */
    private $start_at;

    /**
     * @var Carbon
     */
    private $end_at;

    /**
     * @var int
     */
    private $sum;

    /**
     * AVR constructor.
     *
     * @param Course $course
     * @param bool $save
     */
    public function __construct(Course $course = null, bool $save = true)
    {
        $this->avr      = new AVR();
        $this->course   = $course;
        $this->save     = $save;
    }

    /**
     * Генерация АВР
     * @param Carbon $start_at
     * @param Carbon $end_at
     * @return AVR
     * @throws Exception
     */
    public function generate(Carbon $start_at, Carbon $end_at)
    {
        $this->setData($start_at, $end_at);

        $source     = 'avr/templates/avr.docx';
        $savePath   = 'documents/' . date('Y') . '/acts/' . $this->id . '/' . $this->id. '.docx';

        $this->readTemplate($source);

        $this
            ->setGeneral()
            ->setAVRInfo()
            ->setInfo();

        $this->TPSaveAs($savePath);

        if ($this->save) {
            $result = $this->save($savePath);
            return $this->avr;
        } else {
            $filePath = StorageService::path($savePath);

            $phpWord = IOFactory::load($filePath, "Word2007");
            $writer = IOFactory::createWriter($phpWord, "HTML");

            $result =  $writer->getContent();

            unlink($filePath);
        }

        return $result;
    }

    /**
     * Добавление номера АВР
     *
     * @param AVR $avr
     * @param string $avr_number
     * @return void
     */
    public function addAVRNumber(AVR $avr, string $avr_number): void
    {
        $this->readTemplate($avr->link);

        $this->templateProcessor->setValue('avr_number', $avr_number);

        $this->TPSaveAs($avr->link);

        $avr->update(['number' => $avr_number]);
    }

    /**
     * Предпросмотр без номера
     *
     * @param AVR $avr
     * @return string
     * @throws Exception
     */
    public function previewWithoutNumber(AVR $avr): string
    {
        $this->readTemplate($avr->link);

        $this->templateProcessor->setValue('avr_number', 'XXX');

        $savePath   = 'documents/' . date('Y') . '/acts/' . $this->id . '/' . $this->id. '-preview.docx';

        $this->TPSaveAs($savePath);

        $filePath = StorageService::path($savePath);
        $phpWord = IOFactory::load($filePath, "Word2007");
        $writer = IOFactory::createWriter($phpWord, "HTML");

        $result =  $writer->getContent();

        unlink($filePath);

        return $result;
    }

    /**
     * Set data
     *
     * @param Carbon $start_at
     * @param Carbon $end_at
     * @return void
     */
    private function setData(Carbon $start_at, Carbon $end_at): void
    {
        $this->start_at = $start_at;
        $this->end_at   = $end_at;
        $this->id       = $this->getNumber();
        $this->author   = $this->course->user;
    }

    /**
     * Резервируем id создавая запись
     *
     * @return string
     */
    private function getNumber(): string
    {
        $this->getSum();

        if ($this->save) {
            $this->avr = $this->avr->create([
                'course_id'     => $this->course->id,
                'contract_id'   => $this->course->contract_quota->id ?? 0,
                'status'        => 1,
                'start_at'      => $this->start_at,
                'end_at'        => $this->end_at,
                'sum'           => $this->sum
            ]);
        }

        return $this->avr->id ?? 'XXX';
    }

    /**
     * Сохраняем АВР
     *
     * @param string $savePath
     * @return AVR
     */
    private function save(string $savePath): AVR
    {
        $this->avr->link   = $savePath;
        $this->avr->update();

        return $this->avr;
    }

    /**
     * Заполняем
     *
     * @return self
     */
    private function setGeneral(): self
    {
        $this->templateProcessor->setValue('date_now', Carbon::now()->format('d.m.Y'));
        $this->templateProcessor->setValue('iin', $this->author->iin ?? '-');
        $this->templateProcessor->setValue('type_of_ownership_ru', $this->author->type_ownership->name_ru ?? '-');
        $this->templateProcessor->setValue('company_name', $this->author->company_name ?? '-');
        $this->templateProcessor->setValue('address_ru', $this->author->legal_address_ru ?? '-');
        $this->templateProcessor->setValue('contract_number', $this->course->quota_contract->number ?? '-');

        return $this;
    }

    /**
     * Заполняем инфо АВР
     *
     * @return self
     */
    private function setAVRInfo(): self
    {
        $this->templateProcessor->setValue('course_name', $this->course->name ?? '-');
        $this->templateProcessor->setValue('start_at', $this->start_at->format('d.m.Y'));
        $this->templateProcessor->setValue('end_at', $this->end_at->format('d.m.Y'));
        $this->templateProcessor->setValue('student_count', $this->course->certificate->count());
        $this->templateProcessor->setValue('cost_by_student', CalculateQuotaCost::calculate_quota_cost($this->course));
        $this->templateProcessor->setValue('cost', $this->sum);

        return $this;
    }

    /**
     * Заполняем подпись
     *
     * @return self
     */
    private function setInfo(): self
    {
        $this->templateProcessor->setValue('position_ru', $this->author->position_ru ?? '-');
        $this->templateProcessor->setValue('fio_director', $this->author->fio_director ?? '-');

        return $this;
    }

    /**
     * Общая сумма
     *
     * @return void
     */
    private function getSum(): void
    {
        $this->sum = CalculateQuotaCost::calculate_quota_cost($this->course) * $this->course->certificate->count();
    }
}
