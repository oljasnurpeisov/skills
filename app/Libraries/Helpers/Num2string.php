<?php

namespace Libraries\Helpers;

class Num2string
{
    /**
     * Возвращает сумму прописью (ру)
     *
     * @param int $num
     * @return string
     * @author Oljasnurpeisov
     */
    public function kk(int $num)
    {
        $null = 'нөл';
        $ten = ['', 'бір', 'екі', 'үш', 'төрт', 'бес', 'алты', 'жеті', 'сегіз', 'тоғыз'];
        $tens = [1=> 'он', 'жиырма', 'отыз', 'қырық', 'елу', 'алпыс', 'жетпіс', 'сексен', 'тоқсан'];
        $hundred = 'жүз';
        $out = [];
        list($sum,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $unit = [
            array('копейка' ,'копейки' ,'копеек',  1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        ];
        $unit = ['тиын', 'теңге', 'мың', 'миллион', 'миллиард'];
        if (intval($sum) > 0) {
            foreach (str_split($sum, 3) as $uk => $v) {
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1; // unit key
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                if ($i1>0) $out[] = $ten[$i1] . ' ' .$hundred; # 1xx-9xx
                if ($i2>0) $out[] = $tens[$i2].' '.$ten[$i3]; # 10-99
                else $out[] = $ten[$i3]; # 1-9
                if ($uk>1) $out[] = $unit[$uk];
            }
        }
        else $out = $null;
        $out[] = $unit[1]; // kzt
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    /**
     * Возвращает сумму прописью (ру)
     *
     * @param int $num
     * @return string
     * @author runcore
     */
    public function ru(int $num)
    {
        $nul='ноль';
        $ten=array(
            array('','один','два','три','четыре','пять','шесть','семь', 'восемь','девять'),
            array('','одна','две','три','четыре','пять','шесть','семь', 'восемь','девять'),
        );
        $a20=array('десять','одиннадцать','двенадцать','тринадцать','четырнадцать' ,'пятнадцать','шестнадцать','семнадцать','восемнадцать','девятнадцать');
        $tens=array(2=>'двадцать','тридцать','сорок','пятьдесят','шестьдесят','семьдесят' ,'восемьдесят','девяносто');
        $hundred=array('','сто','двести','триста','четыреста','пятьсот','шестьсот', 'семьсот','восемьсот','девятьсот');
        $unit=array( // Units
            array('копейка' ,'копейки' ,'копеек',	 1),
            array('рубль'   ,'рубля'   ,'рублей'    ,0),
            array('тысяча'  ,'тысячи'  ,'тысяч'     ,1),
            array('миллион' ,'миллиона','миллионов' ,0),
            array('миллиард','милиарда','миллиардов',0),
        );
        //
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) { // by 3 symbols
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1; // unit key
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                // mega-logic
                $out[] = $hundred[$i1]; # 1xx-9xx
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3]; # 20-99
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3]; # 10-19 | 1-9
                // units without rub & kop
                if ($uk>1) $out[]= morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;
        $out[] = morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]); // rub
        $out[] = $kop.' '.morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]); // kop
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }
}
