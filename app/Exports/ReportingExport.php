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

class ReportingExport implements FromArray, WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles, WithStrictNullComparison
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
            'C' => 50,
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
            __('default.pages.reporting.course_name'),
            __('default.pages.reporting.skills'),
            __('default.pages.reporting.professions_group'),
            __('default.pages.reporting.course_rate'),
            __('default.pages.reporting.course_status'),
            __('default.pages.reporting.is_quota'),
            __('default.pages.reporting.is_paid'),
            __('default.pages.reporting.course_cost'),
            __('default.pages.reporting.course_members_count'),
            __('default.pages.reporting.got_certificate_members_count'),
            __('default.pages.reporting.confirmed_qualifications'),
        ];
    }

}
