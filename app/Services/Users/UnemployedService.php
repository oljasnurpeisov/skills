<?php

namespace App\Services\Users;

use Libraries\Requests\SendRequest;

/**
 * Class UnemployedService
 *
 * @author oljasnurpeisov@gmail.com
 * @package App\Services\Users
 */
class UnemployedService
{
    /**
     * Получение Статуса безработного
     *
     * @param string $iin
     * @return array
     */
    public static function getStatus(string $iin, string $token) :array
    {
        $request = (new SendRequest(config('enbek.base_url') . '/ru/api/bezrab-by-iin/' . $iin, $token))->get();
        $result = json_decode($request, true);
        return $result['response'] ?? [];
    }

}
