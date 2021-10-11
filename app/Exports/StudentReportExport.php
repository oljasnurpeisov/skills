<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StudentReportExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles, WithStrictNullComparison, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2:B999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C2:C999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:N1')->getFont()->setBold(true);
        $sheet->getStyle('A2:A99999')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_TEXT);
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_TEXT,
        ];
    }

    public function array(): array
    {
        return $this->invoices;
    }


    public function headings(): array
    {
        return [
            __('admin.pages.reports.iin'),
            __('admin.pages.reports.name_student'),
            __('admin.pages.reports.area'),
            __('admin.pages.reports.coduoz'),
            __('admin.pages.reports.region'),
            __('admin.pages.reports.unemployed'),
            __('admin.pages.reports.course_name'),
            __('admin.pages.reports.course_type'),
            __('admin.pages.reports.course_date'),
            __('admin.pages.reports.first_lesson_date'),
            __('admin.pages.reports.first_failed_test_date'),
            __('admin.pages.reports.second_failed_test_date'),
            __('admin.pages.reports.third_failed_test_date'),
            __('admin.pages.reports.certificate_date'),
//            __('admin.pages.reports.quotas_count'),
        ];
    }

}
