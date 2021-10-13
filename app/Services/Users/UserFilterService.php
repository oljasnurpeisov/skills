<?php

namespace App\Services\Users;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class UserFilterService
 *
 * @author kgurovoy@gmail.com
 * @package App\Services\Users
 */
class UserFilterService
{
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
        if (!empty($request['type']) and $request['type'] === 'authors') {
            $users = $this->searchAuthorsByFIO($users, $request);
            $users = $this->searchAuthorsByBIIN($users, $request);
        }

        if (!empty($request['type']) and $request['type'] === 'students') {
            $users = $this->searchStudentsByFIO($users, $request);
            $users = $this->searchStudentsByIIN($users, $request);
        }

        if (empty($request['type'])) {
            $users = $this->searchByFIO($users, $request);
        }

        $users = $this->searchByEmail($users, $request);
        $users = $this->searchByCompanyName($users, $request);

        return $users;
    }

    /**
     * Поиск авторов по FIO
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchAuthorsByFIO(Builder $users, array $request): Builder
    {
        if (!empty($request['fio'])) {
            $users = $users->whereHas('author_info', function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('surname', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('patronymic', 'like', '%'. $request['fio'] .'%');
            });
        }

        return $users;
    }

    /**
     * Поиск авторов по ИИН/БИН
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchAuthorsByBIIN(Builder $users, array $request): Builder
    {
        if (!empty($request['iin_bin'])) {
            $users = $users->whereHas('author_info', function($q) use ($request) {
                return $q->where('iin', '=', $request['iin_bin']);
            });
        }

        return $users;
    }

    /**
     * Поиск обучающихся по FIO
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchStudentsByFIO(Builder $users, array $request): Builder
    {
        if (!empty($request['fio'])) {
            $users = $users->whereHas('student_info', function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('surname', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('patronymic', 'like', '%'. $request['fio'] .'%');
            });
        }

        return $users;
    }

    /**
     * Поиск обучающихся по ИИН
     *
     * @param Builder $users
     * @param array $request
     * @return Builder
     */
    private function searchStudentsByIIN(Builder $users, array $request): Builder
    {
        if (!empty($request['iin'])) {
            $users = $users->whereHas('student_info', function($q) use ($request) {
                return $q->where('iin', '=', $request['iin']);
            });
        }

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
            $users = $users->whereHas('student_info', function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('surname', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('patronymic', 'like', '%'. $request['fio'] .'%');
            })->orWhereHas('author_info', function($q) use ($request) {
                return $q->where('name', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('surname', 'like', '%'. $request['fio'] .'%')
                    ->orWhere('patronymic', 'like', '%'. $request['fio'] .'%');
            })->orWhere(function($q) use ($request) {
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
