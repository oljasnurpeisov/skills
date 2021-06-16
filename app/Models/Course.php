<?php

namespace App\Models;

use App\Extensions\CalculateQuotaCost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Course extends Model
{
    // Статусы курса
    const draft = 0;
    const onCheck = 1;
    const unpublished = 2;
    const published = 3;
    const deleted = 4;

    protected $table = 'courses';

    protected $fillable = ['contract_status', 'status'];

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
     * Последний договор
     *
     * @return HasOne
     */
    public function contract(): HasOne
    {
        return $this->hasOne(Contract::class, 'course_id', 'id')->latest();
    }

    /**
     * На подписании у автора
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigningAuthor($query): Builder
    {
        return $query->whereContractStatus(1);
    }

    /**
     * На подписании у администрации
     *
     * @param $query
     * @return Builder
     */
    public function scopeSigningAdmin($query): Builder
    {
        return $query->whereContractStatus(2);
    }

    /**
     * На проверке договора
     *
     * @param $query
     * @return Builder
     */
    public function scopeCheckContracts($query): Builder
    {
        return $query->whereContractStatus(3);
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
                return 'Доступен по квоте';
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
}
