<?php

namespace App\Http\Controllers\App\General;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use Illuminate\Http\Request;
use PHPUnit\Framework\Exception;


class PaymentController extends Controller
{

    public function createPaymentOrder(Request $request)
    {

        $data = array("merchantId" => "11847720288158136",
            "callbackUrl" => config('payment.callbackUrl'),
            "description" => config('payment.description'),
            "returnUrl" => config('payment.returnUrl'),
            "amount" => 1000,
            "orderId" => "fdg23",
            "demo" => config('payment.demo'));

        $dataArray = array(
            "merchantId" => strval($data["merchantId"]),
            "callbackUrl" => strval($data["callbackUrl"]),
            "orderId"   =>      strval($data['orderId']),
            "description"=>     strval($data['description']),
            "demo"      =>      $data['demo']=== 'false'? false: true,
            "returnUrl" =>      strval($data['returnUrl']),
            "amount"  =>        (int)$data["amount"]
        );

        if (isset($data['email'])|| isset($data['phone'])){
            $dataArray['customerData']=array(
                "email"     =>      isset($data['email'])?$data['email']:"",
                "phone"     =>      isset($data['phone'])?$data['phone']:""
            );
        }
        if (isset($data['metadata'])){
            $dataArray["metadata"]=$data['metadata'];
        }

        $data_string = json_encode ($dataArray, JSON_UNESCAPED_UNICODE);
        $curl = curl_init( "https://ecommerce.pult24.kz/payment/create");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        $headers = array(
            "Content-Type: application/json",
            "Authorization: Basic " . base64_encode('11847720288158136:h51tYQck9QN2m15xfHce'),
            'Content-Length: ' . strlen($data_string)
        );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);
        $result=json_decode($result, true);

//        if(!empty($result["url"])){
//            return redirect($result["url"]) ;
//        }
//
        return $result;

    }

    public function callbackPaymentOrder(Request $request){

        $json = file_get_contents('php://input');

        if ($request->ip() != "35.157.105.64") {

            return 'failed';
        }
        $out=true;
        header( 'HTTP/1.1 200 OK' );
        if(gettype($out)=="boolean"){

            $item = new PaymentHistory;
            $item->data = json_encode($json);
            $item->save();

            return '{"accepted":'.(($out) ? 'true' : 'false').'}';
        }else{
//            throw  new  Exception($out);
            return 0;
        }

    }

}
