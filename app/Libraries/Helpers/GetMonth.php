<?php

namespace Libraries\Helpers;

class GetMonth
{
    public function kk(int $month)
    {
        switch ($month) {
            case 1:
                return 'Қаңтар';
            case 2:
                return 'Ақпан';
            case 3:
                return 'Наурыз';
            case 4:
                return 'Сәуір';
            case 5:
                return 'Мамыр';
            case 6:
                return 'Маусым';
            case 7:
                return 'Шілде';
            case 8:
                return 'Тамыз';
            case 9:
                return 'Қыркүйек';
            case 10:
                return 'Қазан';
            case 11:
                return 'Қараша';
            case 12:
                return 'Желтоқсан';
        }
    }
}
