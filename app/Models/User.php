<?php

namespace App\Models;

use App\Permissions\HasPermissionsTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string $email
 * @property string $name
 * @property string $iin
 * @property string $position_ru
 * @property string $position_kk
 * @property string $created_at
 * @property string $updated_at
 *
 * @property RoleUser $role
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    use HasPermissionsTrait;

    protected $table = 'users';

    protected $fillable = ['email', 'name', 'surname', 'patronymic', 'iin', 'type_of_ownership', 'company_name', 'company_logo',
        'base_id', 'iik_kz', 'bik', 'kbe', 'bank_id', 'position_ru', 'position_kk', 'fio_director', 'base_file',
        'legal_address_ru', 'legal_address_kk', 'is_activate', 'email_verified_at'];

    protected $guarded = [];

    public $timestamps = true;

    public function type_ownership()
    {
        return $this->belongsTo(Type_of_ownership::class, 'type_of_ownership', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role', 'permission_id', 'role_id');
    }

    public function getAvatar()
    {
        if ($this->company_logo === null) {
            return '/images/img/default.png';
        }

        return $this->company_logo;
    }

    public function courses()
    {

        return $this->hasMany(Course::class, 'author_id');

    }

    public function notifications()
    {

        return $this->belongsToMany(Notification::class, 'user_notifications');

    }

    public function skills()
    {

        return $this->belongsToMany(Skill::class, 'student_skills', 'user_id', 'skill_id');

    }

    public function student_info()
    {
        return $this->belongsTo(StudentInformation::class, 'id', 'user_id');
    }

    public function author_info()
    {
        return $this->belongsTo(UserInformation::class, 'id', 'user_id');
    }

    public function payment_info()
    {
        return $this->belongsTo(PayInformation::class, 'id', 'user_id');
    }

    public function student_lesson()
    {
        return $this->belongsToMany(Lesson::class, 'student_lesson', 'student_id', 'lesson_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function dialogs()
    {
        return $this->belongsToMany(Dialog::class, "dialog_members", "user_id", "dialog_id");
    }

    public function certificates()
    {
        return $this->belongsTo(StudentCertificate::class, 'id', 'user_id');
    }

    public function student_course()
    {
        return $this->hasMany(StudentCourse::class, 'student_id');
    }

    public function author_rates()
    {
        return $this->hasManyThrough(CourseRate::class, Course::class, 'author_id', 'course_id');
    }

    public function professions()
    {
        return $this->belongsToMany(Professions::class, 'student_professions', 'user_id', 'profession_id');
    }

    /**
     * Role
     *
     * @return HasOne
     */
    public function role(): HasOne
    {
        return $this->hasOne(RoleUser::class, 'user_id', 'id');
    }

    /**
     * Bank
     *
     * @return HasOne
     */
    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    /**
     * Base
     *
     * @return HasOne
     */
    public function base(): HasOne
    {
        return $this->hasOne(Base::class, 'id', 'base_id');
    }

    /**
     * Виды деятельности
     *
     * @return HasMany
     */
    public function oked_activities(): HasMany
    {
        return $this->hasMany(UserOkedActivity::class, 'user_id', 'id')->with('oked_activity');
    }

    /**
     * Отрасли
     *
     * @return HasMany
     */
    public function oked_industries(): HasMany
    {
        return $this->hasMany(UserOkedIndustry::class, 'user_id', 'id')->with('oked_industry');
    }
}
