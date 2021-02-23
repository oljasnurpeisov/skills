<?php

namespace App\Extensions;

class RandomStringGenerator
{

    public function generateString(){
        $upperLetterAlphabet = 'ABCDEFGHJKLMNOPQRSTUVWXYZ';
        $lowerLetterAlphabet = 'abcdefghjklmnopqrstuvwxyz';
        $numbersAlphabet = '0123456789';
        $symbolsAlphabet = '(_[^\]).+$/';
        $password_str = '';

        for ($i = 1; $i <= rand(2,4); $i++) {
            $random_upper = $upperLetterAlphabet[mt_rand(0, strlen($upperLetterAlphabet) - 1)];
            $random_lower = $lowerLetterAlphabet[mt_rand(0, strlen($lowerLetterAlphabet) - 1)];
            $random_number = $numbersAlphabet[mt_rand(0, strlen($numbersAlphabet) - 1)];
            $random_symbol = $symbolsAlphabet[mt_rand(0, strlen($symbolsAlphabet) - 1)];
            $password_str = $password_str . $random_upper . $random_lower . $random_number . $random_symbol;
        }

        return str_shuffle($password_str);
    }

}
