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


}
