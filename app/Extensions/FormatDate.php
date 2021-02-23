<?php

namespace App\Extensions;

class FormatDate
{

    public static function formatDate($str)
    {
        $today = date('d');
        $yesterday = date('d', strtotime("-1 days"));
        $day = date('d', strtotime($str));

        $hour = ', ' . date('H:i', strtotime($str));
        if ($hour == ', 00:00') {
            $hour = '';
        }

        if ($day == $today) {
            return __('default.pages.calendar.today') . $hour;
        } elseif ($day == $yesterday) {
            return __('default.pages.calendar.yesterday') . $hour;
        }

        return $str;
    }

    public static function convertMunitesToTime(int $minutes)
    {
        //Конверт числа во время
        $format = '%02d:%02d';

        $hours = floor($minutes / 60);
        $minutes = ($minutes % 60);

        $time = sprintf($format, $hours, $minutes);

        return $time;
    }

}
