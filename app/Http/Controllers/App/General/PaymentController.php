<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\PaymentHistory;
use App\Models\StudentCourse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Exception;


class PaymentController extends Controller
{
    public function createPaymentOrder(Request $request, Course $item)
    {
        if ($request->input('action') != 'by_qouta') {

            if ($item->is_paid == 0) {
                $student_course = new StudentCourse;
                $student_course->paid_status = 1;
                $student_course->course_id = $item->id;
                $student_course->student_id = Auth::user()->id;
                $student_course->save();

//                $this->syncUserLessons($item->id);

                return redirect()->back()->with('status', __('default.pages.courses.pay_course_success'));
            } else {

                $payment = new PaymentHistory;
                $payment->save();

                $data = array("merchantId" => $item->user->payment_info->merchant_login,
                    "callbackUrl" => config('payment.callbackUrl'),
                    "description" => config('payment.description'),
                    "returnUrl" => env('APP_URL').'/'.app()->getLocale().'/course-catalog/course/'.$item->id,
                    "amount" => $item->cost,
                    "orderId" => $payment->id,
                    "metadata" => array("course_id" => $item->id,
                        "student_id" => Auth::user()->id),
                    "demo" => config('payment.demo'));

                $dataArray = array(
                    "merchantId" => strval($data["merchantId"]),
                    "callbackUrl" => strval($data["callbackUrl"]),
                    "orderId" => strval($data['orderId']),
                    "description" => strval($data['description']),
                    "demo" => $data['demo'] === 'false' ? false : true,
                    "returnUrl" => strval($data['returnUrl']),
                    "amount" => (int)$data["amount"] * 100,
                    "metadata" => $data["metadata"]
                );

                if (isset($data['email']) || isset($data['phone'])) {
                    $dataArray['customerData'] = array(
                        "email" => isset($data['email']) ? $data['email'] : "",
                        "phone" => isset($data['phone']) ? $data['phone'] : ""
                    );
                }
                if (isset($data['metadata'])) {
                    $dataArray["metadata"] = $data['metadata'];
                }

                $data_string = json_encode($dataArray, JSON_UNESCAPED_UNICODE);
                $curl = curl_init("https://ecommerce.pult24.kz/payment/create");
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                $headers = array(
                    "Content-Type: application/json",
                    "Authorization: Basic " . base64_encode($item->user->payment_info->merchant_login . ':' . $item->user->payment_info->merchant_password),
                    'Content-Length: ' . strlen($data_string)
                );
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($result, true);

                if (!empty($result["url"])) {
                    return redirect($result["url"]);
                } else {
                    return redirect()->back()->with('error', __('default.pages.courses.pay_course_error'));
                }
            }
        } else {
            $student_info = Auth::user()->student_info()->first();
            if ($student_info->quota_count > 0) {
                $student_course = new StudentCourse;
                $student_course->paid_status = 2;
                $student_course->course_id = $item->id;
                $student_course->student_id = Auth::user()->id;
                $student_course->save();

//                $this->syncUserLessons($item->id);

                $student_info->quota_count = $student_info->quota_count - 1;
                $student_info->save();

                return redirect()->back()->with('status', __('default.pages.courses.course_quota_activate_success'));
            } else {
                return redirect()->back()->with('error', __('default.pages.courses.course_quota_activate_error'));
            }
        }



    }



    public function callbackPaymentOrder(Request $request)
    {

        $json = json_decode(file_get_contents('php://input'));

        if ($request->ip() != "35.157.105.64") {

            return 'failed';
        }
        $out = true;
        header('HTTP/1.1 200 OK');
        if (gettype($out) == "boolean") {

            $item = PaymentHistory::where('id', '=', $json->orderId)->first();
            $item->amount = $json->amount / 100;
            $item->order_id = $json->id;
            $item->status = $json->status;
            $item->save();


            if ($json->status == 1) {
                $student_course_model = StudentCourse::where('payment_id', '=', $item->id)->first();
                if (empty($student_course_model)) {
                    $student_course = new StudentCourse;
                    $student_course->paid_status = 1;
                    $student_course->course_id = $json->metadata->course_id;
                    $student_course->payment_id = $item->id;
                    $student_course->student_id = $json->metadata->student_id;
                    $student_course->save();

                }

            }


            return '{"accepted":' . (($out) ? 'true' : 'false') . '}';
        } else {
//            throw  new  Exception($out);
            return 0;
        }

    }

    public function syncUserLessons(Int $course_id){

        $course = Course::where('id', '=', $course_id)->first();
        $lessons = Lesson::where('theme_id','!=', null)->orderBy('index_number', 'asc')->where('course_id', '=', $course->id)->get();
        $lessons_tests = Lesson::whereIn('type', [3,4])->orderBy('index_number', 'asc')->where('course_id', '=', $course->id)->get();
        $lessons = $lessons->concat($lessons_tests);

        $lesson_ids = [];

        foreach ($lessons as $key => $lesson) {
            if ($course->is_access_all == false) {
                if ($key == 0) {
                    $lesson_ids[$lesson->id] = ['is_access' => true];
                } else {
                    $lesson_ids[$lesson->id] = ['is_access' => false];
                }
            } else {
                if(!in_array($lesson->type, [3,4])) {
                    $lesson_ids[$lesson->id] = ['is_access' => true];
                }else if(in_array($lesson->type, [3,4]) and $key == 0){
                    $lesson_ids[$lesson->id] = ['is_access' => true];
                }else{
                    $lesson_ids[$lesson->id] = ['is_access' => false];
                }


            }
        }

        Auth::user()->student_lesson()->sync($lesson_ids, false);
    }

}
