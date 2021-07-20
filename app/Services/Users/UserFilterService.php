<?php

namespace App\Services\Users;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserFilterService
 *
 * @author kgurovoy@gmail.com
 * @package App\Services\Users
 */
class UserFilterService
{
    public function __construct()
    {
    }

    /**
     * Получение Юзеров
     *
     * @TODO REMOVE LIKE!
     *
     * @param Builder $user
     * @param array $request
     * @return Builder
     */
    public function getOrSearch(Builder $user, array $request): Builder
    {
        return $this->search($user, $request);
    }

    /**
     * Поиск/фильтрация
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    public function search(Builder $users, array $request): Builder
    {
        $users = $this->searchByFIO($users, $request);
        $users = $this->searchByEmail($users, $request);
        $users = $this->searchByCompanyName($users, $request);

        return $users;
    }

    /**
     * Поиск по FIO
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchByFIO(Builder $users, array $request): Builder
    {
        if (!empty($request['fio'])) {
            $users = $users->where(function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('surname', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('patronymic', 'like', '%'. $request['fio'] .'%');
            });
        }

        return $users;
    }

    /**
     * Поиск по email
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchByEmail(Builder $users, array $request): Builder
    {
        if (!empty($request['email'])) {
            $users = $users->where('email', 'like', '%'. $request['email'] .'%');
        }

        return $users;
    }

    /**
     * Поиск по названию компании
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchByCompanyName(Builder $users, array $request): Builder
    {
        if (!empty($request['company_name'])) {
            $users = $users->where('company_name', 'like', '%'. $request['company_name'] .'%');
        }

        return $users;
    }
}
