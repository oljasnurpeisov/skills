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

class CourseReportExport implements FromArray, WithHeadings, WithColumnWidths, ShouldAutoSize, WithStyles, WithStrictNullComparison
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
            'C' => 50,
            'D' => 50,
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
            __('admin.pages.reports.course_name'),
            __('admin.pages.reports.author_name'),
            __('admin.pages.reports.skills'),
            __('admin.pages.reports.group_profession'),
            __('admin.pages.reports.course_rate'),
            __('admin.pages.reports.course_status'),
            __('admin.pages.reports.quota_access'),
            __('admin.pages.reports.paid_or_free'),
            __('admin.pages.reports.course_members'),
            __('admin.pages.reports.course_members_certificates'),
            __('admin.pages.reports.course_members_qualification'),
        ];
    }

}
