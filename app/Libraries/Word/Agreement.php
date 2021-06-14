<?php

namespace Libraries\Word;

use App\Extensions\CalculateQuotaCost;
use App\Models\Contract;
use App\Models\Course;
use App\Models\CourseAttachments;
use App\Models\User;
use App\Models\UserInformation;
use Carbon\Carbon;
use PhpOffice\PhpWord\Exception\CopyFileException;
use PhpOffice\PhpWord\Exception\CreateTemporaryFileException;
use PhpOffice\PhpWord\TemplateProcessor;
use Services\Course\CalculateQuotaCost\CalculateQuotaCostService;

setlocale(LC_TIME, 'ru_RU.UTF-8');

/**
 * Class Agreement
 *
 * @author kgurovoy@gmail.com
 * @package Libraries\Word
 */
class Agreement
{
    /**
     * @var TemplateProcessor
     */
    private $templateProcessor;

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
     * Agreement constructor.
     *
     * @param Course $course
     * @param string $type
     */
    public function __construct(Course $course, string $type='agreement_free')
    {
        $this->contract             = new Contract();
        $this->course               = $course;
        $this->type                 = $type;
        $this->number               = $this->getNumber();
        $this->author               = $this->course->user;
        $this->author_info          = $this->author->author_info;
        $this->course_attachments   = $this->course->attachments;
    }

    /**
     * Генерация договора
     *
     * @return Contract
     */
    public function generate(): Contract
    {
        $source     = 'contracts/templates/agreements/'. $this->type .'.docx';
        $savePath   = 'contracts/templates/agreements/'. $this->type .'_'. $this->number.'.docx';

        try {
            $this->templateProcessor = new TemplateProcessor(public_path($source));
        } catch (CopyFileException $e) {
            abort(500);
        } catch (CreateTemporaryFileException $e) {
            abort(500);
        }

        $this
            ->setGeneral()
            ->setRequisites()
            ->setCourseInfo()
            ->setCourseDetail();

        $this->templateProcessor->saveAs(public_path($savePath));

        return $this->save($savePath);
    }

    /**
     * Резервируем id создавая запись
     *
     * @return int
     */
    private function getNumber(): int
    {
        $this->contract = $this->contract->create([
            'course_id' => $this->course->id
        ]);

        return $this->contract->id;
    }

    /**
     * Сохраняем договор
     *
     * @param string $savePath
     * @return Contract
     */
    private function save(string $savePath): Contract
    {
        $this->contract->link = $savePath;
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
        $this->templateProcessor->setValue('month', Carbon::now()->getTranslatedMonthName('Do MMMM'));
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
        $this->templateProcessor->setValue('company_name', $this->author->company_name);
        $this->templateProcessor->setValue('type_of_ownership', $this->author->type_ownership->name_ru);
        $this->templateProcessor->setValue('fio', $this->author_info->surname .' '. $this->author_info->name .' '. $this->author_info->surname);
        $this->templateProcessor->setValue('position', $this->author->position);
        $this->templateProcessor->setValue('fio_director', $this->author->fio_director);
        $this->templateProcessor->setValue('iin', $this->author->iik_kz);
        $this->templateProcessor->setValue('iik', $this->author->iik_kz);
        $this->templateProcessor->setValue('kbe', $this->author->kbe);
        $this->templateProcessor->setValue('bik', $this->author->bik);
        $this->templateProcessor->setValue('bank_name', $this->author->bank->name_ru);
        $this->templateProcessor->setValue('legal_address', $this->author->legal_address);
        $this->templateProcessor->setValue('base', $this->author->base->name_ru);

        return $this;
    }

    /**
     * Заполняем информацию о курсе
     *
     * @return self
     */
    private function setCourseInfo(): self
    {
        $this->templateProcessor->setValue('course_name', $this->course->name);
        $this->templateProcessor->setValue('course_professional_areas', $this->course->professional_areas->pluck('name_ru')->unique()->implode(', '));
        $this->templateProcessor->setValue('course_professions', $this->course->professional_areas->pluck('name_ru')->unique()->implode(', '));
        $this->templateProcessor->setValue('course_skills', $this->course->skills->pluck('name_ru')->unique()->implode(', '));

        return $this;
    }

    /**
     * Заполняем информацию о курсе
     *
     * @return void
     */
    private function setCourseDetail(): void
    {
        $this->templateProcessor->setValue('teaser', $this->course->teaser);
        $this->templateProcessor->setValue('description', $this->course->description);
        $this->templateProcessor->setValue('profit_desc', $this->course->profit_desc);
        $this->templateProcessor->setValue('videos_link', $this->course->videos_link);
        $this->templateProcessor->setValue('duration', (new CalculateQuotaCostService())->courseDurationService($this->course));
        $this->templateProcessor->setValue('lang', $this->course->lang === 1 ? 'Нет' : 'Да');
        $this->templateProcessor->setValue('attachments', $this->allAttachments($this->course_attachments));
        $this->templateProcessor->setValue('attachments_poor', $this->allAttachmentsPoor($this->course_attachments));

        $this->templateProcessor->setValue('practice_status', __('default.pages.calculator.practice_section_'. (new CalculateQuotaCostService())->practice_status($this->course)));
        $this->templateProcessor->setValue('attachments_forms_count', __('default.pages.calculator.format_section_'. (new CalculateQuotaCostService())->attachments_forms_count($this->course)));
        $this->templateProcessor->setValue('poor_status', $this->getPoorStatus($this->course_attachments));

        $this->templateProcessor->setValue('sum', CalculateQuotaCost::calculate_quota_cost($this->course));
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
     * @return string
     */
    private function getPoorStatus($course_attachments): string
    {
        $videos_poor_hearing_link   = json_decode($course_attachments->videos_poor_hearing_link);
        $audios_poor_vision         = json_decode($course_attachments->audios_poor_vision);

        if ($this->attachmentExist($videos_poor_hearing_link) or $this->attachmentExist($audios_poor_vision)) {
            $adaptive = __('default.pages.calculator.poor_opportunities_not_full_adaptive');
        } elseif ($this->attachmentExist($videos_poor_hearing_link) and $this->attachmentExist($audios_poor_vision)) {
            $adaptive = __('default.pages.calculator.poor_opportunities_full_adaptive');
        }  else {
            $adaptive = __('default.pages.calculator.poor_opportunities_not_adaptive');
        }

        return $adaptive;
    }
}
