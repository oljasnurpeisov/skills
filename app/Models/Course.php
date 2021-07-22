<?php

namespace App\Models;

use App\Extensions\CalculateQuotaCost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class Course
 * @package App\Models
 *
 * @property int $id
 * @property int $status
 * @property string $published_at
 */
class Course extends Model
{
    // Статусы курса

    const draft = 0;
    const onCheck = 1;
    const unpublished = 2;
    const published = 3;
    const deleted = 4;

    protected $table = 'courses';

    protected $fillable = ['contract_status', 'contract_quota_status', 'status', 'publish_at', 'quota_status'];

    protected $dates = ['publish_at'];

    public $timestamps = true;


    public function user()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'author_id');
    }

    public function themes()
    {
        return $this->hasMany(Theme::class, 'course_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'course_id', 'id');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'course_skill', 'course_id', 'skill_id');
    }

    public function professions()
    {
        return $this->belongsToMany(Professions::class, 'course_skill', 'course_id', 'profession_id');
    }

    public function professional_areas()
    {
        return $this->belongsToMany(ProfessionalArea::class, 'course_skill', 'course_id', 'professional_area_id');
    }

    public function course_members()
    {
        return $this->hasMany(StudentCourse::class, 'course_id', 'id');
    }

    public function rate()
    {
        return $this->hasMany(CourseRate::class, 'course_id', 'id');
    }

    public function attachments()
    {
        return $this->hasOne(CourseAttachments::class, 'course_id', 'id');
    }

    public function getAvatar()
    {
        if ($this->image === null) {
            return '/assets/img/course-thumbnail.jpg';
        }
        return $this->image;
    }

    public function courseWork()
    {
        return $this->lessons()->where('type', '=', 3)->first();
    }

    public function finalTest()
    {
        return $this->lessons()->where('type', '=', 4)->first();
    }

    public function professionsBySkills()
    {
        $professions_group = Professions::whereHas('skills', function ($q) {
            $q->whereIn('skills.id', $this->skills->pluck('id')->toArray());
        })->pluck('id');
        $professions = Professions::whereIn('parent_id', $professions_group)->get();

        return $professions;
    }

    public function groupProfessionsBySkills()
    {
        $professions_group = Professions::whereHas('skills', function ($q) {
            $q->whereIn('skills.id', $this->skills->pluck('id')->toArray());
        })->get();

        return $professions_group;
    }

    public function studentCertificate()
    {

        $certificate = StudentCertificate::where('course_id', '=', $this->id)
            ->where('user_id', '=', \Auth::user()->id)->first();

        return $certificate;

    }

    public function quotaCost()
    {
        return $this->hasMany(CourseQuotaCost::class, 'course_id', 'id');
    }

    public function calculateQuotaCost()
    {
        return CalculateQuotaCost::calculate_quota_cost($this);
    }

    /**
     * Актуальные договоры
     *
     * @return hasMany
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class, 'course_id', 'id')->whereIn('status', [1, 2]);
    }

    /**
     * Договор на бесплатный курс
     *
     * @return HasOne
     */
    public function contract_free(): HasOne
    {
        return $this->hasOne(Contract::class, 'course_id', 'id')->whereType(1)->whereIn('status', [1, 2]);
    }

    /**
     * Договор на платный курс
     *
     * @return HasOne
     */
    public function contract_paid(): HasOne
    {
        return $this->hasOne(Contract::class, 'course_id', 'id')->whereType(2)->whereIn('status', [1, 2]);
    }

    /**
     * Договор на курс по квоте
     *
     * @return HasOne
     */
    public function contract_quota(): HasOne
    {
        return $this->hasOne(Contract::class, 'course_id', 'id')->whereType(3)->whereIn('status', [1, 2]);
    }

    /**
     * На подписании у автора
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigningAuthor($query): Builder
    {

//        return $query->whereContractStatus(1);

//        $userRoutes = Route::whereRoleId(\Auth::user()->role->role_id)->pluck('id');
//
//        return $query->whereHas('contracts', function($q) use ($userRoutes) {
//            return $q->whereIn('route_id', $userRoutes);
//        });

        return $query->whereHas('contracts', function ($q) {
            return $q->notRejectedByAuthor()->whereHas('current_route', function ($e) {
                return $e->whereRoleId(4);
            });
        });
    }

    /**
     * На подписании у текущего автора
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigningThisAuthor($query): Builder
    {
        return $query
            ->with(['contracts' => function ($q) {
                return $q->pending()->notRejectedByAuthor()
                    ->whereHas('current_route', function ($e) {
                        return $e->whereRoleId(\Auth::user()->role->role_id);
                    });
            }])
            ->whereHas('contracts', function ($q) {
                return $q->pending()->notRejectedByAuthor()
                    ->whereHas('current_route', function ($e) {
                        return $e->whereRoleId(\Auth::user()->role->role_id);
                    });
            });
    }

    /**
     * На подписании у администрации
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigningAdmin($query): Builder
    {
        return $query->whereHas('contracts', function ($q) {
            return $q->pending()->whereHas('current_route', function ($e) {
                return $e->where('role_id', '!=', 4);
            });
        });
    }

    /**
     * На проверке договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeCheckContracts($query): Builder
    {
        return $query->courseCheck()->whereDoesntHave('contracts');
    }

    /**
     * Курсы прошедшие проверку
     *
     * @param $q
     * @return mixed
     */
    public function scopeCourseCheck($q): Builder
    {
        return $q->whereStatus(5);
    }

    /**
     * Бесплатные курсы
     *
     * @param $query
     * @return Builder
     */
    public function scopeFree($query): Builder
    {
        return $query->whereIsPaid(0);
    }

    /**
     * Платные курсы
     *
     * @param $query
     * @return Builder
     */
    public function scopePaid($query): Builder
    {
        return $query->whereIsPaid(1);
    }

    /**
     * Курсы по квоте
     *
     * @param $query
     * @return Builder
     */
    public function scopeQuota($query): Builder
    {
        return $query->whereIsPaid(1)->where('quota_status', '!=', 0);
    }

    /**
     * Тип курса
     *
     * @return string
     */
    public function getTypeName(): string
    {
        switch (true) {
            case $this->isQuota():
                return 'Доступен при гос. поддержке';
                break;
            case $this->isPaid():
                return 'Платный';
                break;
            default;
                return 'Бесплатный';
                break;
        }
    }

    /**
     * Тип курса
     *
     * @return string
     */
    public function getTypeContractName(): string
    {
        switch (true) {
            case $this->isQuota():
                return 'agreement_quota';
                break;
            case $this->isPaid():
                return 'agreement_paid';
                break;
            default;
                return 'agreement_free';
                break;
        }
    }

    /**
     * Курс бесплатный
     *
     * @return bool
     */
    public function isFree(): bool
    {
        return !$this->isPaid();
    }

    /**
     * Курс платный?
     *
     * @return bool
     */
    public function isPaid(): bool
    {
        return $this->is_paid === 1;
    }

    /**
     * Курс доступен по квоте?
     *
     * @return bool
     */
    public function isQuota(): bool
    {
        return $this->isPaid() && $this->quota_status !== 0;
    }

    /**
     * Договор (бесплатный) существует
     *
     * @return bool
     */
    public function isFreeContractCreated(): bool
    {
        return Contract::where(function ($q) {
            return $q->where(function ($e) {
                return $e->pending();
            })->orWhere(function ($e) {
                return $e->rejectedByAdmin();
            })->orWhere(function ($e) {
                return $e->signed();
            });
        })->whereType(1)->whereCourseId($this->id)->exists();
    }

    /**
     * Договор (платный) существует
     *
     * @return bool
     */
    public function isPaidContractCreated(): bool
    {
        return Contract::where(function ($q) {
            return $q->where(function ($e) {
                return $e->pending();
            })->orWhere(function ($e) {
                return $e->rejectedByAdmin();
            })->orWhere(function ($e) {
                return $e->signed();
            });
        })->whereType(2)->whereCourseId($this->id)->exists();
    }

    /**
     * Договор (по квоте) существует
     *
     * @return bool
     */
    public function isQuotaContractCreated(): bool
    {
        return Contract::where(function ($q) {
            return $q->where(function ($e) {
                return $e->pending();
            })->orWhere(function ($e) {
                return $e->rejectedByAdmin();
            })->orWhere(function ($e) {
                return $e->signed();
            });
        })->whereType(3)->whereCourseId($this->id)->exists();
    }

    /**
     * Договор (бесплатный)
     *
     * @return HasOne
     */
    public function free_contract(): HasOne
    {
        return $this->hasOne(Contract::class)->free()->latest();
    }

    /**
     * Договор (платный)
     *
     * @return HasOne
     */
    public function paid_contract(): HasOne
    {
        return $this->hasOne(Contract::class)->paid()->latest();
    }

    /**
     * Договор (квота)
     *
     * @return HasOne
     */
    public function quota_contract(): HasOne
    {
        return $this->hasOne(Contract::class)->quota()->latest();
    }

    /**
     * Сертификат
     *
     * @return HasMany
     */
    public function certificate()
    {
        return $this->hasMany(StudentCertificate::class, 'course_id', 'id');
    }
}
