<?php

namespace Libraries\Helpers;

class Num2string
{
    /**
     * @var int
     */
    private $num;

    /**
     * Num2string constructor.
     *
     * @param int $num
     */
    public function __construct(int $num)
    {
        $this->num = $num;
    }

    /**
     * Возвращает сумму прописью (ру)
     *
     * @return string
     * @author Oljasnurpeisov
     */
    public function kk(): string
    {
        $null = 'нөл';
        $ten = ['', 'бір', 'екі', 'үш', 'төрт', 'бес', 'алты', 'жеті', 'сегіз', 'тоғыз'];
        $tens = [1=> 'он', 'жиырма', 'отыз', 'қырық', 'елу', 'алпыс', 'жетпіс', 'сексен', 'тоқсан'];
        $hundred = 'жүз';
        $out = [];
        list($sum,$kop) = explode('.',sprintf("%015.2f", floatval($this->num)));
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
        else return $null;
//        $out[] = $unit[1]; // kzt
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    /**
     * Возвращает сумму прописью (ру)
     *
     * @return string
     * @author runcore
     */
    public function ru(): string
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
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($this->num)));
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
                if ($uk>1) $out[]= $this->morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            } //foreach
        }
        else $out[] = $nul;


        return trim(implode(' ', $out));
    }

    /**
     * Склоняем словоформу
     * @author runcore
     */
    private function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }
}
