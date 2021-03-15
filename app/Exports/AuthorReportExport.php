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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AuthorReportExport implements FromArray, WithHeadings, WithColumnWidths, ShouldAutoSize, WithStyles, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }

    public function columnWidths(): array
    {
        return [
            'B' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2:B999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C2:C999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
    }


    public function array(): array
    {
        return $this->invoices;
    }


    public function headings(): array
    {
        return [
            __('admin.pages.reports.name_title'),
            __('admin.pages.reports.specialization'),
            __('admin.pages.reports.rating'),
            __('admin.pages.reports.courses_count'),
            __('admin.pages.reports.courses_paid_count'),
            __('admin.pages.reports.courses_free_count'),
            __('admin.pages.reports.courses_by_quota_count'),
            __('admin.pages.reports.courses_students_count'),
            __('admin.pages.reports.courses_certificates_students_count'),
//            __('admin.pages.reports.courses_students_confirm_qualification_count')
        ];
    }

}
