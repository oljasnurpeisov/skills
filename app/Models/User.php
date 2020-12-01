<?php

namespace App\Models;

use App\Permissions\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\VerifyEmail;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $patronymic
 * @property string|null $iin
 * @property int|null $type_of_ownership
 * @property string|null $company_name
 * @property string|null $company_logo
 * @property string|null $password
 * @property int|null $is_activate 0 - Не активирован 1 - Активирован администратором 2 - Отклонен администратором
 * @property string|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read \App\Models\UserInformation $author_info
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Course[] $courses
 * @property-read int|null $courses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Dialog[] $dialogs
 * @property-read int|null $dialogs_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Notification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Skill[] $skills
 * @property-read int|null $skills_count
 * @property-read \App\Models\StudentInformation $student_info
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Lesson[] $student_lesson
 * @property-read int|null $student_lesson_count
 * @property-read \App\Models\Type_of_ownership|null $type_ownership
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyLogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActivate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePatronymic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTypeOfOwnership($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
//    use HasFactory;
    use Notifiable;
    use HasPermissionsTrait;

    protected $table = 'users';

//    protected $fillable = [
//        'id', 'email', 'name', 'iin', 'type_of_ownership', 'company_name', 'company_logo', 'password', 'email_verified_at'
//    ];
    protected $guarded = [];

    public $timestamps = true;

    public function type_ownership()
    {
        return $this->belongsTo(Type_of_ownership::class, 'type_of_ownership', 'id');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role','permission_id', 'role_id');
    }

    public function getAvatar()
    {
        if ($this->company_logo === null) {
            return '/images/img/default.png';
        }

        return $this->company_logo;
    }

    public function courses() {

        return $this->hasMany(Course::class,'author_id');

    }

    public function notifications() {

        return $this->belongsToMany(Notification::class,'user_notifications');

    }

    public function skills() {

        return $this->belongsToMany(Skill::class,'student_skills','user_id', 'skill_id');

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
        return $this->belongsToMany(Lesson::class,'student_lesson','student_id', 'lesson_id');
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    public function dialogs()
    {
        return $this->belongsToMany(Dialog::class, "dialog_members", "user_id", "dialog_id");
    }

}
