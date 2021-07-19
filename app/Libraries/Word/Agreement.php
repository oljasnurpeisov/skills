<?php

namespace Libraries\Word;

use App\Extensions\CalculateQuotaCost;
use App\Models\Contract;
use App\Models\Course;
use App\Models\CourseAttachments;
use App\Models\User;
use App\Models\UserInformation;
use App\Services\Files\StorageService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Libraries\Helpers\GetMonth;
use Libraries\Helpers\Num2string;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\TemplateProcessor;
use Services\Course\CalculateQuotaCost\CalculateQuotaCostService;

setlocale(LC_TIME, 'ru_RU.UTF-8');

/**
 * Class Agreement
 *
 * @author kgurovoy@gmail.com
 * @package Libraries\Word
 */
class Agreement extends BaseGenerator
{
    /**
     * @var TemplateProcessor
     */
    protected $templateProcessor;

    /**
     * @var string
     */
    private $type;

    /**
     * @var int
     */
    private $number;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var User
     */
    private $author;

    /**
     * @var CourseAttachments
     */
    private $course_attachments;

    /**
     * @var UserInformation
     */
    private $author_info;

    /**
     * @var Contract
     */
    private $contract;

    /**
     * @var bool
     */
    private $save;

    /**
     * @var int
     */
    private $typeNumber;

    /**
     * Agreement constructor.
     *
     * @param Course $course
     * @param string $type
     * @param bool $save
     */
    public function __construct(Course $course, string $type = 'agreement_free', bool $save = true)
    {
        $this->contract             = new Contract();
        $this->course               = $course;
        $this->type                 = $type;
        $this->number               = null;
        $this->typeNumber           = 0;
        $this->save                 = $save;
    }

    /**
     * Генерация договора
     *
     * @return Contract|string
     * @throws Exception
     */
    public function generate()
    {
        if ($this->setType()->checkExist()) {
            return false;
        }

        $this->setData();

        $source     = 'contracts/templates/agreements/'. $this->type .'.docx';
        $savePath   = 'documents/'. date('Y') . '/contracts/' . $this->number . '/'. $this->type .'_'. $this->number.'.docx';

        $this->readTemplate($source);

        $this
            ->setGeneral()
            ->setRequisites()
            ->setCourseInfo()
            ->setCourseDetail();

        $this->TPSaveAs($savePath);

        if ($this->save) {
            $result = $this->save($savePath);
        } else {
            $filePath = StorageService::path($savePath);

            $phpWord = IOFactory::load($filePath, "Word2007");
            $writer = IOFactory::createWriter($phpWord, "HTML");

            $result = $writer->getContent();

            unlink($filePath);
        }

        return $result;
    }

    /**
     * Проверяем существование договора
     *
     * @return bool
     */
    private function checkExist(): bool
    {
        $contract = Contract::where(function ($q) {
            return $q->where(function ($e) {return $e->pending();})->orWhere(function ($e) {return $e->rejectedByAdmin();})->orWhere(function ($e) {return $e->signed();});
        })->whereCourseId($this->course->id)->whereType($this->typeNumber)->exists();

//        if (Contract::whereCourseId($this->course->id)->notRejectedByAuthor()->whereType($this->typeNumber)->exists()) {
        if ($contract) {
            Session::flash('status', 'Уже есть договор на данный курс!');

            return true;
        }

        return false;
    }

    /**
     * Set data
     *
     * @return void
     */
    private function setData(): void
    {
        $this->number               = $this->getNumber();
        $this->author               = $this->course->user;
        $this->author_info          = $this->author->author_info;
        $this->course_attachments   = $this->course->attachments;
    }

    /**
     * Get contract type
     *
     * @return self
     */
    private function setType(): self
    {
        switch ($this->type) {
            case 'agreement_free':
                $this->typeNumber   = 1;
                break;
            case 'agreement_paid':
                $this->typeNumber   = 2;
                break;
            case 'agreement_quota':
                $this->typeNumber   = 3;
                break;
        }

        return $this;
    }

    /**
     * Резервируем id создавая запись
     *
     * @return string
     */
    private function getNumber(): string
    {
        if ($this->save) {
            $this->contract = $this->contract->create([
                'course_id' => $this->course->id,
                'status'    => 1,
            ]);

            switch ($this->type) {
                case 'agreement_free':
                    $contract_id        = $this->contract->id .'-Б';
                    break;
                case 'agreement_paid':
                    $contract_id        = $this->contract->id .'-П';
                    break;
                case 'agreement_quota':
                    $contract_id        = $this->contract->id .'-ГП';
                    break;
            }
        }

        return $contract_id ?? 'XXX';
    }

    /**
     * Сохраняем договор
     *
     * @param string $savePath
     * @return Contract
     */
    private function save(string $savePath): Contract
    {
        $this->contract->link   = $savePath;
        $this->contract->number = $this->number;
        $this->contract->type   = $this->typeNumber;
        $this->contract->update();

        return $this->contract;
    }

    /**
     * Заполняем дату и номер
     *
     * @return self
     */
    private function setGeneral(): self
    {
        $this->templateProcessor->setValue('day', Carbon::now()->day);
        $this->templateProcessor->setValue('month_ru', Carbon::now()->getTranslatedMonthName('Do MMMM'));
        $this->templateProcessor->setValue('month_kk', (new GetMonth())->kk(date('m')));
        $this->templateProcessor->setValue('year', Carbon::now()->year);
        $this->templateProcessor->setValue('number', $this->number);

        return $this;
    }

    /**
     * Заполняем реквизиты
     *
     * @return self
     */
    private function setRequisites(): self
    {
        $this->templateProcessor->setValue('company_name', $this->author->company_name ?? '-');
        $this->templateProcessor->setValue('type_of_ownership_ru', $this->author->type_ownership->name_ru ?? '-');
        $this->templateProcessor->setValue('type_of_ownership_kk', $this->author->type_ownership->name_kk ?? '-');
        $this->templateProcessor->setValue('fio', $this->author_info->surname .' '. $this->author_info->name .' '. $this->author_info->surname);
        $this->templateProcessor->setValue('position_ru', $this->author->position_ru ?? '-');
        $this->templateProcessor->setValue('position_kk', $this->author->position_kk ?? '-');
        $this->templateProcessor->setValue('fio_director', $this->author->fio_director ?? '-');
        $this->templateProcessor->setValue('iin', $this->author->iin ?? '-');
        $this->templateProcessor->setValue('iik', preg_replace('/ /', '', $this->author->iik_kz) ?? '-');
        $this->templateProcessor->setValue('kbe', $this->author->kbe ?? '-');
        $this->templateProcessor->setValue('bik', $this->author->bik ?? '-');
        $this->templateProcessor->setValue('bank_name_ru', $this->author->bank->name_ru ?? '-');
        $this->templateProcessor->setValue('bank_name_kk', $this->author->bank->name_kk ?? '-');
        $this->templateProcessor->setValue('legal_address_ru', $this->author->legal_address_ru ?? '-');
        $this->templateProcessor->setValue('legal_address_kk', $this->author->legal_address_kk ?? '-');
        $this->templateProcessor->setValue('base_ru', $this->author->base->name_ru ?? '-');
        $this->templateProcessor->setValue('base_kk', $this->author->base->name_kk ?? '-');

        return $this;
    }

    /**
     * Заполняем информацию о курсе
     *
     * @return self
     */
    private function setCourseInfo(): self
    {
        $this->templateProcessor->setValue('course_name', $this->course->name ?? '-');
        $this->templateProcessor->setValue('course_professional_areas_ru', $this->course->professional_areas->pluck('name_ru')->unique()->implode(', ') ?? '-');
        $this->templateProcessor->setValue('course_professions_ru', $this->course->professions->pluck('name_ru')->unique()->implode(', ') ?? '-');
        $this->templateProcessor->setValue('course_skills_ru', $this->course->skills->pluck('name_ru')->unique()->implode(', ') ?? '-');
        $this->templateProcessor->setValue('course_professional_areas_kk', $this->course->professional_areas->pluck('name_kk')->unique()->implode(', ') ?? '-');
        $this->templateProcessor->setValue('course_professions_kk', $this->course->professions->pluck('name_kk')->unique()->implode(', ') ?? '-');
        $this->templateProcessor->setValue('course_skills_kk', $this->course->skills->pluck('name_kk')->unique()->implode(', ') ?? '-');

        return $this;
    }

    /**
     * Заполняем информацию о курсе
     *
     * @return void
     */
    private function setCourseDetail(): void
    {
//        $this->templateProcessor->setValue('teaser', $this->clearText($this->course->teaser) ?? '-');
//        $this->templateProcessor->setValue('description', $this->clearText($this->course->description) ?? '-');
//        $this->templateProcessor->setValue('profit_desc', $this->clearText($this->course->profit_desc) ?? '-');
//        $this->templateProcessor->setValue('videos_link', $this->course->videos_link ?? '-');
//        $this->templateProcessor->setValue('duration', round((new CalculateQuotaCostService())->courseDurationService($this->course) ?? '-'));

        $this->templateProcessor->setValue('lang_ru', $this->course->lang === 1 ? 'Нет' : 'Да');
        $this->templateProcessor->setValue('lang_kk', $this->course->lang === 1 ? 'Жоқ' : 'Иә');

        $this->templateProcessor->setValue('attachments', $this->allAttachments($this->course_attachments));
        $this->templateProcessor->setValue('attachments_poor', $this->allAttachmentsPoor($this->course_attachments));

        $this->templateProcessor->setValue('practice_status_ru', $this->getPracticeStatus('ru')); // Количество форматов учебного контента
        $this->templateProcessor->setValue('practice_status_kk', $this->getPracticeStatus('kk')); // Количество форматов учебного контента

        $this->templateProcessor->setValue('attachments_forms_count_ru', $this->getAttachmentsForm('ru')); // Наличие контрольно-измерительных материалов:
        $this->templateProcessor->setValue('attachments_forms_count_kk', $this->getAttachmentsForm('kk')); // Наличие контрольно-измерительных материалов:

        $this->templateProcessor->setValue('poor_status_ru', $this->getPoorStatus($this->course_attachments, 'ru'));
        $this->templateProcessor->setValue('poor_status_kk', $this->getPoorStatus($this->course_attachments, 'kk'));

        $cost = CalculateQuotaCost::calculate_quota_cost($this->course);
        $costLet = new Num2string($cost);

        $this->templateProcessor->setValue('sum', $cost);
        $this->templateProcessor->setValue('quota_cost_ru', $costLet->ru());
        $this->templateProcessor->setValue('quota_cost_kk', $costLet->kk());
    }

    /**
     * Вложения курса
     *
     * @param $course_attachments
     * @return string
     */
    private function allAttachments($course_attachments)
    {
        $attachments = [];

        $video  = json_decode($course_attachments->videos_link);
        $audio  = json_decode($course_attachments->audios);

        if ($this->attachmentExist($video)) {
            array_push($attachments, $video[0]);
        }

        if ($this->attachmentExist($audio)) {
            array_push($attachments, url('/') .'/'. $audio[0]);
        }

        return implode(', ', $attachments);
    }

    /**
     * Вложения для лиц с особыми потребностями
     *
     * @param $course_attachments
     * @return string
     */
    private function allAttachmentsPoor($course_attachments)
    {
        $attachments = [];

        $videos_poor_hearing_link   = json_decode($course_attachments->videos_poor_hearing_link);
        $audios_poor_vision         = json_decode($course_attachments->audios_poor_vision);

        if ($this->attachmentExist($videos_poor_hearing_link)) {
            array_push($attachments, $videos_poor_hearing_link[0]);
        }

        if ($this->attachmentExist($audios_poor_vision)) {
            array_push($attachments, url('/') .'/'. $audios_poor_vision[0]);
        }

        return implode(', ', $attachments);
    }

    /**
     * Attachment exist
     *
     * @param $attachment
     * @return bool
     */
    private function attachmentExist($attachment): bool
    {
        return !empty($attachment) and !empty($attachment[0]);
    }

    /**
     * Адаптированность для лиц с особыми образовательными потребностями
     *
     * @param $course_attachments
     * @param string $lang
     * @return string
     */
    private function getPoorStatus($course_attachments, string $lang): string
    {
        $videos_poor_hearing_link   = json_decode($course_attachments->videos_poor_hearing_link);
        $audios_poor_vision         = json_decode($course_attachments->audios_poor_vision);

        if ($this->attachmentExist($videos_poor_hearing_link) or $this->attachmentExist($audios_poor_vision)) {
            $adaptive = trans('default.pages.calculator.poor_opportunities_not_full_adaptive', [], $lang);
        } elseif ($this->attachmentExist($videos_poor_hearing_link) and $this->attachmentExist($audios_poor_vision)) {
            $adaptive = trans('default.pages.calculator.poor_opportunities_full_adaptive', [], $lang);
        } else {
            $adaptive = trans('default.pages.calculator.poor_opportunities_not_adaptive', [], $lang);
        }

        return $adaptive;
    }

    /**
     * Контрольно-измерительные материалы
     *
     * @param string $lang
     * @return string
     */
    private function getPracticeStatus(string $lang): string
    {
        $status = (new CalculateQuotaCostService())->practice_status($this->course);

        switch ($status) {
            case 0:
                return trans('default.pages.calculator.practice_section_3', [], $lang);
            case 1:
                return trans('default.pages.calculator.practice_section_2', [], $lang);
            case 2:
                return trans('default.pages.calculator.practice_section_1', [], $lang);
            default:
                return '-';
        }
    }

    /**
     * Формат учебного контента
     *
     * @param string $lang
     * @return string
     */
    private function getAttachmentsForm(string $lang): string
    {
        $count = (new CalculateQuotaCostService())->attachments_forms_count($this->course);

        switch ($count) {
            case 1:
                return trans('default.pages.calculator.format_section_3', [], $lang);
            case 2:
            case 3:
                return trans('default.pages.calculator.format_section_2', [], $lang);
            default:
                return trans('default.pages.calculator.format_section_1', [], $lang);
        }
    }

    /**
     * Очистка от мусора
     *
     * @param string $text
     * @return string
     */
    private function clearText(string $text): string
    {
        $text = preg_replace('/(&nbsp;)/', ' ', $text);
        $text = preg_replace('/(&laquo;)/', ' ', $text);
        $text = preg_replace('/(&raquo;)/', ' ', $text);
        $text = preg_replace('/\s/', ' ', $text);
        $text = htmlspecialchars(strip_tags($text));

        return $text;
    }
}
