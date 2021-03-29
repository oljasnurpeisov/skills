<?php

namespace App\Extensions;

class YoutubeParse
{

    public static function parseYoutube($link)
    {
        preg_match('/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i', $link, $results);

        return $results[6] ?? '';
    }

}
