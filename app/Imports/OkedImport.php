<?php

namespace App\Imports;

use App\Models\OkedActivities;
use App\Models\OkedIndustries;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class OkedImport implements ToCollection, WithHeadingRow
{
    private $industry;

    /**
     * OkedImport constructor.
     */
    public function __construct()
    {
        $this->industry = null;
    }

    /**
     * Парсер ОКЕД
     *
     * @param Collection $rows
     */
    public function collection(Collection $rows)
    {

        foreach ($rows as $row) {
            if (!empty($row['naimenovanie_otrasli'])) {
                $this->industry = OkedIndustries::create([
                    'name_ru' => $row['naimenovanie_otrasli'],
                    'name_kk' => $row['salanyn_atauy'],
                ]);
            }

            if (!empty($row['vidy_deyatelnosti'])) {
                OkedActivities::create([
                    'oked_industries_id'    => $this->industry->id,
                    'name_ru'               => $row['vidy_deyatelnosti'],
                    'name_kk'               => $row['qyzmetter_turleri'],
                ]);
            }
        }
    }
}
