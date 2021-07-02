<?php

namespace Libraries\Word;

use App\Models\AVR;
use App\Models\Course;
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
    private $number;

    /**
     * @var AVR
     */
    private $avr;

    /**
     * AVR constructor.
     *
     * @param Course $course
     * @param bool $save
     */
    public function __construct(Course $course, bool $save = true)
    {
        $this->avr      = new AVR();
        $this->course   = $course;
        $this->save     = $save;
    }

    /**
     * Генерация АВР
     */
    public function generate()
    {
        $source     = 'avr/templates/avr.docx';
        $savePath   = 'avr/templates/123.docx';

        $this->setData();

        $this->readTemplate($source);
        $this->TPSaveAs($savePath);

        if ($this->save) {
            $result = $this->save($savePath);
        } else {
            $phpWord = IOFactory::load($savePath, "Word2007");
            $writer = IOFactory::createWriter($phpWord, "HTML");

            $result =  $writer->getContent();

            unlink($savePath);
        }

        return $result;
    }

    /**
     * Set data
     *
     * @return void
     */
    private function setData(): void
    {
        $this->number = $this->getNumber();
    }

    /**
     * Резервируем id создавая запись
     *
     * @return string
     */
    private function getNumber(): string
    {
        if ($this->save) {
            $this->avr = $this->avr->create([
                'course_id'     => $this->course->id,
                'contract_id'   => $this->course->contract_quota->id,
                'status'        => 1
            ]);
        }

        return $contract_id ?? 'XXX';
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
        $this->avr->number = $this->number;
        $this->avr->update();

        return $this->avr;
    }
}
