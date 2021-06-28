<?php

namespace Services\Course;

use App\Models\Course;
use App\Models\StudentCertificate;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

/**
 * Class CourseCertificateService
 *
 * @author oljasnurpeisov@gmail.com
 * @package Service\Course
 */
class CourseCertificateService
{
    public function saveCertificates(Course $course)
    {
        $user = Auth::user();
        $languages = ["ru"];

        $certificate = new StudentCertificate;
        $certificate->user_id = $user->id;
        $certificate->course_id = $course->id;
        $certificate->save();
        $data = [
            'author_name' => $course->user->company_name . '/' . $course->user->author_info->name . ' ' . $course->user->author_info->surname,
            'author' => $course->user->author_info->name . ' ' . $course->user->author_info->surname,
            'company' => $course->user->company_name,
            'company_logo' => $course->user->company_logo,
            'student_name' => $user->student_info->name,
            'duration' => $course->lessons->sum('duration'),
            'course_name' => $course->name,
            'skills' => $course->skills,
            'certificate_id' => sprintf("%012d", $certificate->id) . '-' . date('dmY')
        ];

        $filePath = '/users/user_' . $user->id;

        foreach ($languages as $language) {
            try {
                $template = 'app.pages.page.pdf.certificate_' . $course->certificate_id . '_' . $language;
                $pdf = PDF::loadView($template, ['data' => $data]);
                $pdf = $pdf->setPaper('A4', 'landscape');

                File::ensureDirectoryExists(public_path('/users/user_' . $user->id));

                $path = public_path('users/user_' . $user->id . '');
                $pdfPath = $path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf';
                $pdf->save($pdfPath);
            } catch (\InvalidArgumentException $e) {
                $e->getMessage();
            }
        }

        $certificate->pdf_ru = $filePath . '/' . 'course_' . $course->id . '_certificate_ru.pdf';
        $certificate->pdf_kk = $filePath . '/' . 'course_' . $course->id . '_certificate_kk.pdf';
        $certificate->save();

        foreach ($languages as $language) {
            try {
                $path = public_path('users/user_' . $user->id . '');
                $pdfPath = $path . '/' . 'course_' . $course->id . '_certificate_' . $language . '.pdf';
                $pdfToImage = new \Spatie\PdfToImage\Pdf($pdfPath);
                $pngPath = $path . '/' . 'course_' . $course->id . '_image_' . $language . '.png';
                $pdfToImage->saveImage($pngPath);
            } catch (\InvalidArgumentException $e) {
                $e->getMessage();
            }
        }

        $certificate->png_ru = $filePath . '/' . 'course_' . $course->id . '_image_ru.png';
        $certificate->png_kk = $filePath . '/' . 'course_' . $course->id . '_image_kk.png';
        $certificate->save();

        try {
            $path = env('APP_URL', 'https://skills.enbek.kz') . $certificate->png_ru;
            $cert = base64_encode(file_get_contents($path));
            $this->putNewSkills($user->student_info->uid, $course, $cert);
        } catch (\ErrorException $e) {
            $e->getMessage();
        } catch (FileNotFoundException $e) {
            $e->getMessage();
        }
    }
}
