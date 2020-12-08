<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Models\StudentCertificate
 *
 * @property int $id
 * @property int $user_id
 * @property int $course_id
 * @property string|null $pdf_ru
 * @property string|null $pdf_kk
 * @property string|null $png_ru
 * @property string|null $png_kk
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate query()
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate whereCourseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate wherePdfKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate wherePdfRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate wherePngKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate wherePngRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|StudentCertificate whereUserId($value)
 * @mixin \Eloquent
 */
class StudentCertificate extends Model
{

    protected $table = 'student_certificates';

    public $timestamps = true;


}
