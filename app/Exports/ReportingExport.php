<?php

namespace App\Exports;

use App\Models\Course;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ReportingExport implements FromArray, WithHeadings, ShouldAutoSize, WithStrictNullComparison
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $invoices;

    public function __construct(array $invoices)
    {
        $this->invoices = $invoices;
    }


    public function array(): array
    {
        return $this->invoices;
    }


    public function headings(): array
    {
        return [
            __('default.pages.reporting.id_course_title'),
            __('default.pages.reporting.author_name'),
            __('default.pages.reporting.course_name'),
            __('default.pages.reporting.is_paid'),
            __('default.pages.reporting.is_quota'),
            __('default.pages.reporting.course_cost'),
            __('default.pages.reporting.course_members_count'),
            __('default.pages.reporting.members_quota_count'),
            __('default.pages.reporting.finished_course_members_count'),
            __('default.pages.reporting.got_certificate_members_count'),
            __('default.pages.reporting.rates_count'),
            __('default.pages.reporting.average_rate'),
            '1',
            '2',
            '3',
            '4',
            '5'
        ];
    }

}
