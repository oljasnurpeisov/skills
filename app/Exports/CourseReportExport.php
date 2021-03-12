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
            'E' => 50,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B2:B999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('C2:C999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('D3:D999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('E3:E999')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:V1')->getFont()->setBold(true);
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
            __('default.pages.reporting.professional_area'),
            __('default.pages.reporting.profession'),
            __('default.pages.reporting.skills'),
            __('default.pages.reporting.course_rate'),
            __('default.pages.reporting.course_status'),
            __('default.pages.reporting.course_type'),
            __('default.pages.reporting.course_cost'),
            __('default.pages.reporting.is_quota'),
            __('default.pages.reporting.cost_by_quota'),
            __('default.pages.reporting.members_free'),
            __('default.pages.reporting.certificate_free'),
            __('default.pages.reporting.qualificated_free'),
            __('default.pages.reporting.members_paid'),
            __('default.pages.reporting.certificate_paid'),
            __('default.pages.reporting.qualificated_paid'),
            __('default.pages.reporting.total_get_paid'),
            __('default.pages.reporting.members_quota'),
            __('default.pages.reporting.certificate_quota'),
            __('default.pages.reporting.qualificated_quota'),
            __('default.pages.reporting.total_get_quota')
        ];
    }

}
